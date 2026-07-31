<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\MarksServices\MarkDiagnosticColumns;
use App\Services\MarksServices\MarkDiagnosticQuery;
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
class JobToGeneratePrintableMarksDiagnosticDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $period,
        public string  $status,       // 'hasMarks' | 'hasntMarks' | 'both'
        public array   $markTypes,
        public ?int    $school_year_id = null,
        public string  $docTitle = 'diagnostic des notes',
        public array   $config = [
            'classe_id' => null, 'filiar_id' => null, 'serial_id' => null,
            'promotion_id' => null, 'promotionInGroups' => null, 'subject_id' => null,
            'tableColumns' => [],
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
        $tableColumns = MarkDiagnosticColumns::resolve($this->config['tableColumns'] ?? null);

        $rows = MarkDiagnosticQuery::getFormattedRows(
            $this->config, $this->school_year_id, $this->period, $tableColumns, $this->markTypes, $this->status
        );

        $viewData = [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allRows'      => count($rows),
            'pdf_title'    => $this->docTitle,
            'target'       => 'marks_diagnostic',
            'eventName'    => 'MarksDiagnosticPDFCompletedSuccessfullyLiveEvent',
            'tableColumns' => $tableColumns,
            'period'       => $this->period,
            'schoolYear'   => $this->schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.reports.marks-diagnostic-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'marks',
            overrides:       ['landscape' => true],
            documentType:    'marks_diagnostic_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
        );
    }
}