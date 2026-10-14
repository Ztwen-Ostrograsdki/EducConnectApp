<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\PDFFactory;
use App\Services\TimePlanServices\TimePlanPrintBuilder;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

#[Timeout(300)]
class JobToGeneratePrintableTimePlans implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear = null;

    /**
     * @param  string  $mode  classes|teachers|subject
     * @param  array{
     *     classe_ids?: array<int>,
     *     teacher_ids?: array<int>,
     *     subject_id?: int|null,
     *     plan_status?: string|null
     * }  $config
     */
    public function __construct(
        public string $tenantId,
        public int $notifiableId,
        public string $mode = 'classes',
        public ?int $school_year_id = null,
        public string $docTitle = 'emplois-du-temps',
        public array $config = [],
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
                tenantId: $this->tenantId,
                title: 'ECHEC DE LA GENERATION DU DOCUMENT',
                message: cutter($th->getMessage(), 20000),
                type: 'error',
            ));
        } finally {
            if ($this->tenantId) {
                tenancy()->end();
            }
        }
    }

    public function checkSchoolYear(): void
    {
        if (!$this->school_year_id) {
            $schoolYear = SchoolYear::current()->first();

            if ($schoolYear) {
                $this->schoolYear = $schoolYear;
                $this->school_year_id = $schoolYear->id;
            } else {
                $this->fail('Année scolaire non définie ou introuvable');
            }

            return;
        }

        $schoolYear = SchoolYear::query()->find($this->school_year_id);

        if ($schoolYear) {
            $this->schoolYear = $schoolYear;
            $this->school_year_id = $schoolYear->id;
        } else {
            $this->fail('Année scolaire non définie ou introuvable');
        }
    }

    public function factoryBuilder(): void
    {
        $builder = TimePlanPrintBuilder::forYear((int) $this->school_year_id);
        $status = $this->config['plan_status'] ?? 'published';

        $plans = match ($this->mode) {
            'teachers' => $builder->buildForTeachers($this->config['teacher_ids'] ?? []),
            'subject' => $builder->buildForSubjectTeachers((int) ($this->config['subject_id'] ?? 0)),
            default => $builder->buildForClasses($this->config['classe_ids'] ?? [], $status),
        };

        if ($plans === []) {
            throw new \RuntimeException('Aucun emploi du temps à générer pour les critères sélectionnés.');
        }

        $subjectName = null;
        if ($this->mode === 'subject' && !empty($this->config['subject_id'])) {
            $subjectName = Subject::query()->find($this->config['subject_id'])?->name;
        }

        $pdf_title = $builder->resolveDocTitle($this->mode, [
            'subject_name' => $subjectName,
        ]);

        $teacherIds = $this->config['teacher_ids'] ?? [];
        $context_label = match ($this->mode) {
            'subject' => $subjectName ? "Matière : {$subjectName}" : null,
            'teachers' => count($teacherIds) === 1
                ? ($plans[0]['title'] ?? 'Emploi du temps enseignant')
                : 'Enseignants sélectionnés',
            default => 'Classes sélectionnées',
        };

        // PDF d’un seul enseignant : titre plus précis
        if ($this->mode === 'teachers' && count($teacherIds) === 1 && !empty($plans[0]['title'])) {
            $pdf_title = 'Emploi du temps — ' . $plans[0]['title']
                . ($this->schoolYear?->slug ? ' (' . $this->schoolYear->slug . ')' : '');
        }

        $viewData = [
            'plans'         => $plans,
            'printed_at'    => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'     => $pdf_title,
            'context_label' => $context_label,
            'schoolYear'    => $this->schoolYear,
            'isPdf'         => true,
            'target'        => 'timeplans',
            'repeatHeader' => false,
            'eventName'     => 'TimePlansPDFCompletedSuccessfullyLiveEvent',
        ];

        PDFFactory::dispatch(
            view: 'livewire.tenants.timeplans.time-plan-printable-list-component',
            data: $viewData,
            filename: Str::slug($this->docTitle ?: $pdf_title),
            category: 'timeplans',
            overrides: [
                'landscape' => true,
                'format'    => 'A4',
            ],
            documentType: 'time_plan',
            tenantId: $this->tenantId,
            notifiableId: $this->notifiableId,
            docDBInfos: [
                'subject_id'     => $this->config['subject_id'] ?? null,
                'for_teachers'   => $this->mode !== 'classes',
                'for_teacher_id' => ($this->mode === 'teachers' && count($teacherIds) === 1)
                    ? (int) $teacherIds[0]
                    : null,
            ],
            repeatHeader: false,
        );
    }
}
