<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\PDFFactory;
use App\Services\TeachersServices\TeacherPrintColumns;
use App\Services\TeachersServices\TeacherPrintQuery;
use App\Services\TeachersServices\TeacherPrintSessionConfig;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

#[Timeout(300)]
class JobToGeneratePrintableTeachersDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public string  $docTitle = 'liste enseignants',
        public ?int    $school_year_id = null,
        public array   $config = [
            "trashedConfig"     => 'withoutTrashed',
            "accessesConfig"    => null,
            "ppConfig"          => null,
            "aeConfig"          => null,
            "hasClassesConfig"  => null,
            "classe_id"         => null,
            "filiar_id"         => null,
            "subject_id"        => null,
            "serial_id"         => null,
            "promotion_id"      => null,
            "promotionInGroups" => null,
            "gender"            => null,
            "city"              => null,
            "department"        => null,
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
        $tableColumns = TeacherPrintColumns::resolve($this->config['tableColumns'] ?? null);

        $totalCount = TeacherPrintQuery::count($this->config, $this->school_year_id);

        $threshold = config('app.pdf_large_dataset_warning', 5000);

        if ($totalCount > $threshold) {
            logger()->warning('Génération PDF enseignants sur un volume important', [
                'tenant_id' => $this->tenantId,
                'count'     => $totalCount,
            ]);
        }

        $rows = TeacherPrintQuery::getFormattedRows($this->config, $this->school_year_id, $tableColumns);

        $printed_at = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $pdf_title = TeacherPrintQuery::resolveDocTitle($this->config ? $this->config :  TeacherPrintSessionConfig::filterConfig());

        $viewData = [
            'rows'            => $rows,
            'printed_at'      => $printed_at,
            'allTeachers'     => $totalCount,
            'pdf_title'       => $pdf_title,
            'target'          => 'teachers',
            'eventName'       => 'TeachersPDFCompletedSuccessfullyLiveEvent',
            'tableColumns'    => $tableColumns,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.teachers.teachers-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'teachers',
            overrides:       ['landscape' => true],
            documentType:    'teacher_list',
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