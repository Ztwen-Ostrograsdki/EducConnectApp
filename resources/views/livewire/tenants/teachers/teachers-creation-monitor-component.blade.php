<div class="w-full mx-auto border border-slate-700 rounded-2xl p-2">
    <section class=" bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5">

        <div class="w-full px-4 py-1">

            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1 p-2 my-2">

                    {{-- ICON --}}
                    <div class="shrink-0 self-start">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            <x-lucide-user-plus class="h-10 w-10 font-extrabold text-slate-600" />
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-2">

                            <h1
                                class="text-xl sm:text-xl lg:text-xl
                                           font-bold
                                           leading-tight
                                           break-words font-mono">

                                Apperçue des tâches lancées

                            </h1>
                        </div>

                        <p class="mt-3 text-sm sm:text-base
                                      text-slate-400
                                      break-words">

                            Gestion des migrations sur les utilisateurs enseignants

                        </p>

                    </div>

                </div>

            </div>

        </div>
        {{-- ACTIONS --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto justify-end my-2">
            <button class="py-1.5 px-2.5 bg-red-400 text-black hover:bg-red-500 rounded-xl flex items-center gap-x-1">
                <x-lucide-trash-2 class="w-4 h-4" />
                <span>Toutes les tâches</span>
            </button>
            <button class="py-1.5 px-2.5 bg-red-400 text-black hover:bg-red-500 rounded-xl flex items-center gap-x-1">
                <x-lucide-trash-2 class="w-4 h-4" />
                Toutes les tâches succès
            </button>
            <button class="py-1.5 px-2.5 bg-red-400 text-black hover:bg-red-500 rounded-xl flex items-center gap-x-1">
                <x-lucide-trash-2 class="w-4 h-4" />
                Toutes les tâches échouées
            </button>
        </div>

    </section>
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
            <div class="bg-slate-900 border border-sky-600 shadow-sm shadow-sky-700 rounded-lg px-4 py-3" wire:key="task-{{ $task->id }}">
                <div class="border-b text-slate-500 border-gray-600 py-1 w-full flex justify-between items-center">
                    <div>
                        Insertion de l'utilisateur {{ $task->payload['name'] }} — {{ $task->payload['prenames'] }}
                        <div class="text-sky-700">
                            <span class="flex items-center gap-x-1">
                                <x-lucide-mail class="h-4 w-4" />
                                {{ $task->payload['email'] }}
                            </span>
                            <span class="flex items-center gap-x-1">
                                <x-lucide-user class="h-4 w-4" />
                                {{ 'Enseignant' }}
                            </span>
                        </div>
                    </div>
                    <div class="mx-4 w-auto flex">
                        @if ($task->status == 'success')
                            <span class="text-xs text-green-800 font-semibold bg-green-500 rounded-2xl py-2 px-4 flex items-center">✓ Créé</span>
                        @elseif ($task->status == 'failed')
                            <span class="text-xs text-red-800 font-semibold bg-red-500 rounded-2xl py-2 px-4 flex items-center">✗ Échoué</span>
                        @else
                            <span class="text-xs text-slate-800 bg-slate-500 rounded-2xl py-2 px-4 flex items-center">En cours...</span>
                        @endif
                    </div>
                </div>

                {{-- Infos user --}}
                <div class="flex items-center justify-between rounded-lg px-4 py-3">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-600">
                            Détails
                        </p>
                        @if ($task->error)
                            <p class="text-xs text-rose-400 mt-0.5">{{ $task->error }}</p>
                        @elseif($task->status == 'success')
                            <p class="text-sm text-green-400 mt-0.5">
                                La tâche s'est bien déroulée!
                            </p>
                        @endif
                    </div>

                    {{-- Actions --}}

                    <div class="flex gap-2">
                        @if ($task->status === 'failed')
                            <x-mini-button wire:click="retryOne({{ $task->id }})" color="amber" icon="arrow-path" title="Relancer" />
                        @endif
                        <x-mini-button wire:click="deleteOne({{ $task->id }})" wire:confirm="Supprimer cette tâche ?" color="rose" icon="trash" title="Supprimer" />
                    </div>
                </div>
                <div class="flex justify-end p-2 border-t border-gray-700 text-xs text-purple-500 font-mono">
                    Terminée le {{ __formatDateTime($task->updated_at) }}
                </div>
            </div>
        @endforeach
    </div>
</div>

