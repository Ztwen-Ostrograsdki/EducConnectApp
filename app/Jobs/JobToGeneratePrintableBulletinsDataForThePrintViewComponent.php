<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\BulletinsServices\BulletinPrintQuery;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

#[Timeout(600)]
class JobToGeneratePrintableBulletinsDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $period,
        public ?int    $school_year_id = null,
        public string  $docTitle = 'bulletins',
        public array   $config = [
            'classe_id' => null, 'filiar_id' => null, 'student_id' => null, 'serial_id' => null,
            'promotion_id' => null, 'promotionInGroups' => null, 'level' => null, 'period' => null,
            'leavesConfig' => 'onlyActives',
        ],
    ) {}

    public function handle(): void
    {
        if ($this->tenantId) {
            tenancy()->initialize($this->tenantId);
            app('filesystem')->forgetDisk('public');
        }

        try {
            $this->checkSchoolYear();
            $this->factoryBuilder();
        } catch (\Throwable $th) {
            $director = User::firstWhere('tenant_id', $this->tenantId);

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC DE LA GENERATION DU DOCUMENT",
                message:   cutter($th->getMessage(), 20000),
                type:      'error',
            ));
        } finally {
            if ($this->tenantId) tenancy()->end();
        }
    }

    public function checkSchoolYear(): void
    {
        if (! $this->school_year_id) {
            $schoolYear = SchoolYear::current()->first();
            if ($schoolYear) { $this->schoolYear = $schoolYear; $this->school_year_id = $schoolYear->id; }
        } else {
            $schoolYear = SchoolYear::firstWhere('id', $this->school_year_id);
            if ($schoolYear && $schoolYear->is_active) {
                $this->schoolYear = $schoolYear; $this->school_year_id = $schoolYear->id;
            } else {
                $this->fail("Année scolaire non définie ou introuvable");
            }
        }
    }

    public function factoryBuilder(): void
    {
        $bulletins = BulletinPrintQuery::getBulletinsData($this->config, $this->school_year_id, $this->period, $this->schoolYear);

        $threshold = config('app.pdf_large_dataset_warning', 500);

        if (count($bulletins) > $threshold) {
            logger()->warning('Génération PDF bulletins sur un volume important', [
                'tenant_id' => $this->tenantId,
                'count'     => count($bulletins),
            ]);
        }

        $viewData = [
            'bulletins'   => $bulletins,
            'printed_at'  => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'   => $this->docTitle,
            'target'      => 'bulletins',
            'eventName'   => 'BulletinsPDFCompletedSuccessfullyLiveEvent',
            'period'      => $this->period,
            'schoolYear'  => $this->schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.reports.bulletins-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'bulletins',
            overrides:       ['landscape' => false], // PORTRAIT
            documentType:    'bulletins_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
            docDBInfos:      [
                'classe_id'                 => isset($this->config['classe_id']), 
                'period'                    => $this->period, 
                'for_student_id'            => isset($this->config['student_id']) ? 
                                                $this->config['student_id'] : null, 
                'filiar_id'                 => isset($this->config['filiar_id']) ? 
                                               $this->config['filiar_id'] : null,
                'promotion_id'              => isset($this->config['promotion_id']) ? 
                                               $this->config['promotion_id'] : null,
                'serial_id'                 => isset($this->config['serial_id']) ? 
                                               $this->config['serial_id'] : null,
                'promotionsGrouped'         => isset($this->config['promotionInGroups']) ? 
                                               $this->config['promotionInGroups'] : null,
            ],
            paginable: false,
        );
    }
}