<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\TeachersCreationTaskStartedEvent;
use App\Jobs\JobToCreateTeacher;
use App\Models\ImportTask;
use Illuminate\Support\Facades\Bus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Tâches de migrations enseignants")]
class TeachersCreationMonitorComponent extends Component
{
    use WireUiActions;

    public ?string $batchId   = null;
    public string  $tenantId;

    public bool $showBatchModal = false;

    public ?string $selectedBatchId = null;

    public $selectedBatchTasks = [];

    public ?array $selectedBatchStats = null;

    // Compteur pour forcer le re-render — doit changer à chaque fois
    public int $renderKey = 0;

    public function mount(?string $batchId = null): void
    {
        $this->tenantId = tenant('id');

        $this->batchId = $batchId
            ?? session('current_batch_id')
            ?? ImportTask::whereNotNull('batch_id')->latest()->value('batch_id');
    }

    /**
     * Force un re-render propre — increment garanti différent
     */
    private function forceRefresh(): void
    {
        $this->renderKey++;
    }

    #[On('TeachersCreationsTasksStartedLiveEvent')]
    public function teachersTasksStarted(string $batchId, int $totalJobs): void
    {
        $this->batchId = $batchId;

        // session(['current_batch_id' => $this->batchId]);

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "CREATION D'ENSEIGNANTS : File d'attente initialisée",
            'description' => "{$totalJobs} insertion(s) d'enseignant(s) lancée(s) !",
        ]);

        $this->forceRefresh();
    }

    #[On('TeachersCreationProgressLiveEvent')]
    public function teacherCreationProgress(string $batchId, string $tenantId, int $totalJobs, int $processed, int $failed, $percentage): void
    {
        $this->forceRefresh();
    }

    #[On('TeacherCreatedSucessfullyLiveEvent')]
    public function teacherCreated(string $teacherName, ?string $message = 'Un nouvelle enseignant inséré avec succès'): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "CREATION ENSEIGNANT REUSSIE!" ,
            'description' => "L'enseignant " . $teacherName . " a été créé avec succès!",
        ]);

        $this->forceRefresh();
    }

    #[On('ATeacherCreationFailedLiveEvent')]
    public function TeacherCreationFailed(string $teacherName, ?string $error = 'Une erreur est survenue'): void
    {
        $this->notification()->send([
            'icon'        => 'error',
            'title'       => "La création de l'enseignant " . $teacherName . " a échoué!" ,
            'description' => "Erreurs : " . $error,
        ]);

        $this->forceRefresh();
    }

  
    #[On('ProcessToCreateTeachersCompletedSuccesfullyLiveEvent')]
    public function tasksCompleted(string $batchId, string $tenantId, int $totalJobs, int $processed, int $failed, $percentage): void
    {
        $this->notification()->send([
            'icon'        => $failed > 0 ? 'warning' : 'success',
            'title'       => "PROCESSUS DE CREATION ENSEGNANTS TERMINES",
            'timeout'     => 0,
            'description' => "Total : {$totalJobs} — Réussis : {$processed} — Échecs : {$failed} — Progression : {$percentage}% ",
        ]);

        $this->forceRefresh();
    }


    public function render()
    {

        $this->renderKey = randomNumber();

        $batchIds = ImportTask::query()
            ->selectRaw('batch_id, MAX(created_at) as last_created')
            ->whereNotNull('batch_id')
            ->where('task_name', 'teachers-creation')
            ->groupBy('batch_id')
            ->orderByDesc('last_created')
            ->get();

        $batches = $batchIds->map(function ($item) {

            $tasks = ImportTask::where(
                'batch_id',
                $item->batch_id
            )->get();

            $batch = Bus::findBatch(
                $item->batch_id
            );

            return [

                'id' => $item->batch_id,

                'batch' => $batch,

                'total' => $tasks->count(),

                'success' => $tasks->where('status', 'success')->count(),

                'failed' => $tasks->where('status', 'failed')->count(),

                'pending' => $tasks->where('status', 'pending')->count(),

                'last_created' => $item->last_created,
            ];
        });

        return view(
            'livewire.tenants.teachers.teachers-creation-monitor-component',
            compact('batches')
        );
    }

    public function showBatch(string $batchId): void
    {
        $tasks = ImportTask::where('batch_id', $batchId)
            ->latest()
            ->get();

        $this->selectedBatchId = $batchId;

        $this->selectedBatchTasks = $tasks;

        $this->selectedBatchStats = [
            'total' => $tasks->count(),
            'success' => $tasks->where('status', 'success')->count(),
            'failed' => $tasks->where('status', 'failed')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
        ];

        $this->showBatchModal = true;
    }

    public function closeBatchModal(): void
    {
        $this->showBatchModal = false;
    }

    public function retryBatchFailures(?string $batchId = null): void
    {
        $targetBatchId = null;

        if($batchId){

            $targetBatchId = $batchId;

        }
        elseif($this->selectedBatchId){

            $targetBatchId = $this->selectedBatchId;

        }

        if($targetBatchId){

            $tasks = ImportTask::where('batch_id', $targetBatchId)
            ->where('status', 'failed')
            ->get();

            if ($tasks->isEmpty()) {
                return;
            }

            $domain = request()->getSchemeAndHttpHost();

            $jobs = $tasks->map(fn($t) => new JobToCreateTeacher(tenant('id'), $t->id, $domain));

            $newBatch = Bus::batch([])->allowFailures()->dispatch();

            $tasks->each->update([
                'batch_id' => $newBatch->id,
                'status'   => 'pending',
            ]);

            TeachersCreationTaskStartedEvent::dispatch(
                tenantId:  tenant('id'),
                batchId:   $newBatch->id,
                totalJobs: $jobs->count(),
            );

            $newBatch->add($jobs);
            $this->forceRefresh();
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Erreur",
                'description' => "Veuillez sélectionner le cible de tâches à relancer!",
            ]);


        }

    }

    public function deleteBatchFailures(?string $batchId = null): void
    {
        //
    }

    public function deleteBatchSuccess(?string $batchId = null): void
    {
        //
    }

    public function deleteBatch(?string $batchId = null): void
    {
        //
    }

    public function retryAll(): void
    {
        
    }

    public function retryOne(int $taskId): void
    {
        $task = ImportTask::findOrFail($taskId);

        $task->update(['status' => 'pending', 'error' => null]);

        $domain = request()->getSchemeAndHttpHost();

        dispatch(new JobToCreateTeacher(tenant('id'), $task->id, $domain));

        $this->forceRefresh();
    }

    public function deleteOne(int $taskId): void
    {
        ImportTask::destroy($taskId);

        $this->forceRefresh();
    }
}