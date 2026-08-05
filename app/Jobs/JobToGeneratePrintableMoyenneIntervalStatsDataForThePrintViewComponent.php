<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\ClassesServices\MoyenneIntervalStatsQuery;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

#[Timeout(300)]
class JobToGeneratePrintableMoyenneIntervalStatsDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $period,
        public string  $groupedBy,
        public array   $breakpoints,
        public ?int    $school_year_id = null,
        public string  $docTitle = 'statistiques des moyennes',
        public array   $config = [],
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
        $result = MoyenneIntervalStatsQuery::computeRows(
            $this->config, $this->school_year_id, $this->period, $this->breakpoints, $this->groupedBy
        );

        $viewData = [
            'rows'           => $result['rows'],
            'intervalLabels' => $result['intervalLabels'],
            'printed_at'     => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'      => $this->docTitle,
            'target'         => 'moyenne_stats',
            'eventName'      => 'MoyenneStatsPDFCompletedSuccessfullyLiveEvent',
            'period'         => $this->period,
            'schoolYear'     => $this->schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.stats.moyenne-interval-stats-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'documents',
            overrides:       ['landscape' => true],
            documentType:    'moyenne_stats_list',
            downloadableByOthers:    true,
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
            docDBInfos:      [
                'for_teachers'              => isset($this->config['for_teachers']) ? 
                                               $this->config['for_teachers'] : true, 
                'classe_id'                 => isset($this->config['classe_id']) ? 
                                               $this->config['classe_id'] : null, 
                'filiar_id'                 => isset($this->config['filiar_id']) ? 
                                               $this->config['filiar_id'] : null,
                'promotion_id'              => isset($this->config['promotion_id']) ? 
                                               $this->config['promotion_id'] : null,
                'serial_id'                 => isset($this->config['serial_id']) ? 
                                               $this->config['serial_id'] : null,
                'promotionsGrouped'         => isset($this->config['promotionInGroups']) ? 
                                               $this->config['promotionInGroups'] : null,
            ],
        );
    }
}