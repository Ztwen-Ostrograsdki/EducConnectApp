<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\ClassesServices\ClassePrintColumns;
use App\Services\ClassesServices\ClassePrintQuery;
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
class JobToGeneratePrintableClassesDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public string  $docTitle = 'liste classes',
        public ?int    $school_year_id = null,
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
        $tableColumns = ClassePrintColumns::resolve($this->config['tableColumns'] ?? null);

        $totalCount = ClassePrintQuery::count($this->config, $this->school_year_id);

        $rows = ClassePrintQuery::getFormattedRows($this->config, $this->school_year_id, $tableColumns);

        $printed_at = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $viewData = [
            'rows'            => $rows,
            'printed_at'      => $printed_at,
            'allClasses'      => $totalCount,
            'pdf_title'       => $this->docTitle,
            'target'          => 'classes',
            'eventName'       => 'ClassesPDFCompletedSuccessfullyLiveEvent',
            'tableColumns'    => $tableColumns,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.classes.classes-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'classes',
            overrides:       ['landscape' => true],
            documentType:    'classe_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
        );
    }
}