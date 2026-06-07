<?php

namespace App\Livewire\Tenants\Students;

use App\Events\StudentsCreationTaskStartedEvent;
use App\Jobs\JobToCreateStudent;
use App\Models\ImportTask;
use Illuminate\Support\Facades\Bus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentsCreationMonitorComponent extends Component
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

    #[On('StudentsCreationsTasksStartedLiveEvent')]
    public function studentsTasksStarted(array $data): void
    {
        $this->batchId = $data['batchId'];

        session(['current_batch_id' => $this->batchId]);

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "File d'attente initialisée",
            'description' => "{$data['totalJobs']} insertion(s) lancée(s) !",
        ]);

        $this->forceRefresh();
    }

    #[On('HandleAnyLiveEvent')]
    public function reloadForAny(array $data): void
    {
        $this->forceRefresh();
    }
    
    #[On('StudentCreatedSucessfullyLiveEvent')]
    public function studentCreated(array $data): void
    {
        $this->forceRefresh();
    }

    #[On('AStudentCreationFailedLiveEvent')]
    public function studentCreationFailed(array $data): void
    {
        $this->forceRefresh();
    }

    #[On('StudentsCreationsTasksProgressLiveEvent')]
    public function studentCreationProgress(array $data): void
    {
        $this->forceRefresh();
    }

    #[On('StudentsCreationsCompletedLiveEvent')]
    public function taskCompleted(array $data): void
    {
        $failed  = $data['failed'] ?? 0;
        $total   = $data['totalJobs'] ?? 0;
        $success = $total - $failed;

        $this->notification()->send([
            'icon'        => $failed > 0 ? 'warning' : 'success',
            'title'       => "Traitement terminé",
            'timeout'     => 0,
            'description' => "Total : {$total} — Réussis : {$success} — Échecs : {$failed}",
        ]);

        $this->forceRefresh();
    }

    public function render()
    {

        $this->renderKey = randomNumber();

        $batchIds = ImportTask::query()
            ->selectRaw('batch_id, MAX(created_at) as last_created')
            ->whereNotNull('batch_id')
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
            'livewire.tenants.students.students-creation-monitor-component',
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

            $jobs = $tasks->map(fn($t) => new JobToCreateStudent(tenant('id'), $t->id));

            $newBatch = Bus::batch([])->allowFailures()->dispatch();

            $tasks->each->update([
                'batch_id' => $newBatch->id,
                'status'   => 'pending',
            ]);

            StudentsCreationTaskStartedEvent::dispatch(
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

        dispatch(new JobToCreateStudent(tenant('id'), $task->id));

        $this->forceRefresh();
    }

    public function deleteOne(int $taskId): void
    {
        ImportTask::destroy($taskId);

        $this->forceRefresh();
    }
}