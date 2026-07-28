<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\MarksServices\MarkPrintColumns;
use App\Services\MarksServices\MarkPrintQuery;
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
class JobToGeneratePrintableMarksDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $period,
        public ?int    $school_year_id = null,
        public ?int    $subject_id = null,
        public string  $docTitle = 'liste des notes',
        public array   $config = [
            'classe_id'         => null,
            'filiar_id'         => null,
            'serial_id'         => null,
            'promotion_id'      => null,
            'promotionInGroups' => null,
            'level'             => null,
            'leavesConfig'      => 'onlyActives',
            'tableColumns'      => [],
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

            if ($schoolYear) {
                $this->schoolYear = $schoolYear;
                $this->school_year_id = $schoolYear->id;
            }
        } else {
            $schoolYear = SchoolYear::firstWhere('id', $this->school_year_id);

            if ($schoolYear && $schoolYear->is_active) {
                $this->schoolYear = $schoolYear;
                $this->school_year_id = $schoolYear->id;
            } else {
                $director = User::firstWhere('tenant_id', $this->tenantId);

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA GENERATION DU DOCUMENT",
                    message:   "Année scolaire non définie ou introuvable",
                    type:      'error',
                ));

                $this->fail("Année scolaire non définie ou introuvable");
            }
        }
    }

    public function factoryBuilder(): void
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        $subjectTargeted = (bool) $this->subject_id;

        $tableColumns = MarkPrintColumns::resolve($this->config['tableColumns'] ?? null, $devoirsType, $subjectTargeted);

        $rows = MarkPrintQuery::getFormattedRows(
            $this->config,
            $this->school_year_id,
            $this->period,
            $tableColumns,
            $devoirsType,
            $this->subject_id
        );

        $printed_at = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $pdf_title = MarkPrintQuery::resolveDocTitle($this->config, $this->subject_id, $this->period, $this->school_year_id);

        $viewData = [
            'rows'            => $rows,
            'printed_at'      => $printed_at,
            'allStudents'     => count($rows),
            'pdf_title'       => $pdf_title,
            'target'          => 'marks',
            'eventName'       => 'MarksPDFCompletedSuccessfullyLiveEvent',
            'tableColumns'    => $tableColumns,
            'period'          => $this->period,
            'schoolYear'      => $this->schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.students.marks-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'marks',
            overrides:       ['landscape' => true],
            documentType:    'marks_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
            docDBInfos:      [
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