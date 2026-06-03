<?php

namespace App\Livewire\Tenants\Teachers;

use App\Jobs\JobToCreateTeacher;
use App\Models\ImportTask;
use Illuminate\Support\Facades\Bus;
use Livewire\Component;

class TeachersCreationMonitorComponent extends Component
{

    public ?string $batchId = null;
    public string $tenantId;

    public function mount(?string $batchId = null): void
    {
        $this->tenantId = tenant('id');
        
        // Récupère le dernier batch en cours si pas passé en prop
        $this->batchId = $batchId ?? ImportTask::whereNotNull('batch_id')
            ->latest()
            ->value('batch_id');
    }

    public function getListeners(): array
    {
        $listeners = [
            "echo-private:tenant.{$this->tenantId},TeachersCreationTaskStartedEvent" => 'onBatchStarted',
            "echo-private:tenant.{$this->tenantId},ATeacherCreationFailedEvent" => 'teacherCreationFailed',
        ];

        if ($this->batchId) {
            $listeners["echo-private:tenant.{$this->tenantId},TeachersCreationProcessProgressEvent"] = 'refreshTask';
            $listeners["echo-private:tenant.{$this->tenantId},ProcessToCreateTeachersCompletedSuccesfullyEvent"] = '$refresh';
        }

        return $listeners;
    }

    public function onBatchStarted(array $data): void
    {
        $this->batchId = $data['batchId'];

        session(['current_batch_id' => $this->batchId]);
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "La file d'attente est initialisée",
            'delay'       => 0,
            'description' => "C'est partie...."
        ]);
        // Les listeners se mettent à jour automatiquement car getListeners() est rappelé
    }

    public function teacherCreationFailed(array $data)
    {
        $this->notification()->send([
            'icon'        => 'error',
            'title'       => "L'insertion de " . $data['userName'] . " a échoué",
            'delay'       => 0,
            'description' => "Les raisons: " . $data['error']
        ]);
    }


    public function render()
    {
        if(session()->has('current_batch_id')){

            $this->batchId = session(['current_batch_id']);

        }
        $this->batchId = ImportTask::whereNotNull('batch_id')
            ->latest()
            ->value('batch_id');

        $tasks = $this->batchId
        ? ImportTask::where('batch_id', $this->batchId)->orderBy('status')->get()
        : collect();

        $batch = $this->batchId
        ? Bus::findBatch($this->batchId)
        : null;

        return view('livewire.tenants.teachers.teachers-creation-monitor-component', compact('tasks', 'batch'));
    }

    public function refreshTask(array $data): void
    {
        $this->dispatch('task-updated', id: $data['task']['id']);

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "Mise à jour",
            'delay'       => 0,
            'description' => "C'est partie...."
        ]);
    }

    public function retryAll(): void
    {
        $tasks = ImportTask::where('batch_id', $this->batchId)
            ->where('status', 'failed')
            ->get();

        $jobs = $tasks->map(fn($t) => new JobToCreateTeacher(tenant('id'), $t->id));

        $newBatch = Bus::batch($jobs)->allowFailures()->dispatch();

        $this->batchId = $newBatch->id;

        $tasks->each->update(['batch_id' => $newBatch->id, 'status' => 'pending']);
    }

    public function retryOne(int $taskId): void
    {
        $task = ImportTask::findOrFail($taskId);
        $task->update(['status' => 'pending']);

        dispatch(new JobToCreateTeacher(tenant('id'), $task->id));
    }

    public function deleteOne(int $taskId): void
    {
        ImportTask::destroy($taskId);
    }

}

