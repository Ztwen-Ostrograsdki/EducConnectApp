<div>
    {{-- Barre de progression globale --}}
    <div class="mb-6">
        <div class="flex justify-between text-sm text-slate-400 mb-1">
            <span>{{ $batch?->processedJobs() }} / {{ $batch?->totalJobs }} traitées</span>
            <span>{{ $batch?->failedJobs }} échec(s)</span>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $batch?->progress() }}%">
            </div>
        </div>
    </div>

    {{-- Bouton relancer tous les échecs --}}
    @if ($tasks->where('status', 'failed')->count() > 0)
        <div class="mb-4 flex justify-end">
            <x-button wire:click="retryAll" color="amber" icon="arrow-path">
                Relancer tous les échecs ({{ $tasks->where('status', 'failed')->count() }})
            </x-button>
        </div>
    @endif

    {{-- Tableau des tâches --}}
    <div class="space-y-2">
        @foreach ($tasks as $task)
            <div class="flex items-center justify-between bg-slate-800 rounded-lg px-4 py-3" wire:key="task-{{ $task->id }}">

                {{-- Infos user --}}
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-100">
                        {{ $task->payload['name'] }} — {{ $task->payload['email'] }}
                    </p>
                    @if ($task->error)
                        <p class="text-xs text-rose-400 mt-0.5">{{ $task->error }}</p>
                    @endif
                </div>

                {{-- Statut --}}
                <div class="mx-4">
                    @match($task->status)
                        'success' => '<span class="text-xs text-emerald-400 font-semibold">✓ Créé</span>',
                        'failed' => '<span class="text-xs text-rose-400 font-semibold">✗ Échoué</span>',
                        default => '<span class="text-xs text-slate-400">En cours...</span>',
                    @endmatch
                </div>

                {{-- Actions --}}
                @if ($task->status === 'failed')
                    <div class="flex gap-2">
                        <x-mini-button wire:click="retryOne({{ $task->id }})" color="amber" icon="arrow-path" title="Relancer" />
                        <x-mini-button wire:click="deleteOne({{ $task->id }})" wire:confirm="Supprimer cette tâche ?" color="rose" icon="trash" title="Supprimer" />
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

