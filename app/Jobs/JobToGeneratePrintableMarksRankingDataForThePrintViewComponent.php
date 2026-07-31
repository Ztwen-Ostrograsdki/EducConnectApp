<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\MarksServices\MarkRankingPrintColumns;
use App\Services\MarksServices\MarkRankingPrintQuery;
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
class JobToGeneratePrintableMarksRankingDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $period,
        public string  $targeted,     // 'best' | 'worst'
        public int     $limit,
        public string  $groupedBy,    // 'classe_id' | 'filiar_id' | 'serial_id' | 'promotion_id' | 'promotionInGroups'
        public ?int    $school_year_id = null,
        public ?int    $subject_id = null,
        public string  $docTitle = 'classement des apprenants',
        public array   $config = [
            'classe_id'         => null,
            'filiar_id'         => null,
            'serial_id'         => null,
            'promotion_id'      => null,
            'promotionInGroups' => null,
            'level'             => null,
            'leavesConfig'      => 'onlyActives',
            'gender'            => null,
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

        $tableColumns = MarkRankingPrintColumns::resolve($this->config['tableColumns'] ?? null, $subjectTargeted);

        $rows = MarkRankingPrintQuery::getFormattedRows(
            $this->config,
            $this->school_year_id,
            $this->period,
            $tableColumns,
            $devoirsType,
            $this->subject_id,
            $this->targeted,
            $this->limit,
            $this->groupedBy
        );

        $printed_at = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $viewData = [
            'rows'            => $rows,
            'printed_at'      => $printed_at,
            'allStudents'     => count($rows),
            'pdf_title'       => $this->docTitle,
            'target'          => 'marks_ranking',
            'eventName'       => 'MarksRankingPDFCompletedSuccessfullyLiveEvent',
            'tableColumns'    => $tableColumns,
            'period'          => $this->period,
            'schoolYear'      => $this->schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.classes.mark-ranking-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'marks',
            overrides:       ['landscape' => true],
            documentType:    'marks_ranking_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
        );
    }
}