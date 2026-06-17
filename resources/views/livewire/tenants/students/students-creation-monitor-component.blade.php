<div class="w-full mx-auto max-w-7xl border border-slate-700 rounded-3xl p-6 bg-slate-950/50">

    {{-- HEADER --}}
    <section class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-6 mb-8 shadow-xl border border-slate-700/50">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-indigo-500/10 to-violet-500/10 
                            border border-indigo-500/30 flex items-center justify-center shadow-inner">
                    <x-lucide-user-plus class="h-10 w-10 text-indigo-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight font-mono text-white">
                        Aperçu des tâches lancées
                    </h1>
                    <p class="text-slate-400 text-sm mt-1">
                        Gestion des migrations des apprenants <span class="hidden">{{ $renderKey }}</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- ACTIONS GLOBALES --}}
        <div class="flex flex-wrap gap-3 mt-6">
            <a href="{{ route('tenant.students.create') }}" class="p-2 rounded-2xl bg-indigo-600 hover:bg-indigo-500 transition-all active:scale-95 font-medium gap-2 inline-flex items-center">
                <x-lucide-plus class="w-4 h-4" />
                Ajouter des apprenants
            </a>

            <button wire:click="clearAllBatches" wire:loading.attr="disabled" wire:target="clearAllBatches" class="p-2 rounded-2xl bg-red-600/90 hover:bg-red-600 text-white transition-all active:scale-95 inline-flex items-center gap-2">
                <span wire:loading.remove wire:target="clearAllBatches" class="inline-flex items-center gap-2">
                    <x-lucide-trash class="w-4 h-4" />
                    <span>Vider tous les batches</span>
                </span>
                <span wire:loading wire:target="clearAllBatches" class="inline-flex items-center gap-2">
                    <x-lucide-loader class="w-4 h-4 animate-spin" />
                    <span>Suppression...</span>
                </span>
            </button>

            <button wire:click="deleteAllSuccess" wire:loading.attr="disabled" wire:target="deleteAllSuccess" class="p-2 rounded-2xl bg-red-600/90 hover:bg-red-600 text-white transition-all active:scale-95">
                <span wire:loading.remove wire:target="deleteAllSuccess">Supprimer tous les succès</span>
                <span wire:loading wire:target="deleteAllSuccess">
                    <x-lucide-loader class="w-4 h-4 animate-spin" />
                    <span> En cours...</span>
                </span>
            </button>

            <button wire:click="deleteAllFailures" wire:loading.attr="disabled" wire:target="deleteAllFailures" class="p-2 rounded-2xl bg-red-600/90 hover:bg-red-600 text-white transition-all active:scale-95">
                <span wire:loading.remove wire:target="deleteAllFailures">Supprimer tous les échecs</span>
                <span wire:loading wire:target="deleteAllFailures">
                    <x-lucide-loader class="w-4 h-4 animate-spin" />
                    <span> En cours...</span>
                </span>
            </button>
        </div>
    </section>

    {{-- GRID --}}
    @if ($batches->isEmpty())
        <div class="bg-slate-900/70 border border-slate-700 rounded-3xl p-16 text-center">
            <x-lucide-inbox class="mx-auto h-16 w-16 text-slate-500 mb-4" />
            <p class="text-slate-400 text-lg">Aucun batch trouvé</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($batches as $batchData)
                <div class="group bg-slate-900 border border-slate-700 rounded-3xl overflow-hidden 
                            hover:border-indigo-500/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

                    <!-- Contenu de la carte (inchangé) -->
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h2 class="font-bold text-lg text-white">Import apprenants</h2>
                                <p class="text-xs text-slate-500 font-mono break-all mt-1">
                                    {{ $batchData['id'] }}
                                </p>
                            </div>

                            @if ($batchData['batch']?->finished())
                                <span class="px-4 py-1.5 bg-green-500/10 text-green-400 text-xs font-medium rounded-2xl border border-green-500/20">
                                    Terminé
                                </span>
                            @else
                                <span class="px-4 py-1.5 bg-amber-500/10 text-amber-400 text-xs font-medium rounded-2xl border border-amber-500/20">
                                    En cours
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <div class="flex justify-between text-xs text-slate-400 mb-2">
                            <span>{{ $batchData['batch']?->processedJobs() ?? 0 }} / {{ $batchData['batch']?->totalJobs ?? 0 }}</span>
                            <span class="font-medium">{{ $batchData['batch']?->progress() ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all duration-700 ease-out" style="width: {{ $batchData['batch']?->progress() ?? 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 px-6 pb-6">
                        <div class="bg-slate-950/70 rounded-2xl p-4 text-center border border-slate-700/50">
                            <div class="text-xs text-slate-500">Succès</div>
                            <div class="font-bold text-2xl text-green-400 mt-1">{{ $batchData['success'] }}</div>
                        </div>
                        <div class="bg-slate-950/70 rounded-2xl p-4 text-center border border-slate-700/50">
                            <div class="text-xs text-slate-500">Échecs</div>
                            <div class="font-bold text-2xl text-red-400 mt-1">{{ $batchData['failed'] }}</div>
                        </div>
                        <div class="bg-slate-950/70 rounded-2xl p-4 text-center border border-slate-700/50">
                            <div class="text-xs text-slate-500">En attente</div>
                            <div class="font-bold text-2xl text-amber-400 mt-1">{{ $batchData['pending'] }}</div>
                        </div>
                    </div>

                    <div wire:key='actions-card-{{ $batchData['id'] }}' class="border-t border-slate-700 p-4 flex-wrap gap-2 space-x-3 space-y-3 text-sm">
                        <button wire:click="showBatch('{{ $batchData['id'] }}')" wire:loading.attr="disabled" wire:target="showBatch('{{ $batchData['id'] }}')"
                            class="p-2 rounded-2xl bg-blue-600 hover:bg-blue-700 inline-flex items-center gap-1 transition-all">
                            <x-lucide-eye class="w-4 h-4" />
                            Voir les détails
                        </button>
                        <button wire:click="retryBatchFailures('{{ $batchData['id'] }}')" wire:loading.attr="disabled" wire:target="retryBatchFailures('{{ $batchData['id'] }}')"
                            class="flex-1 p-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-black font-semibold transition-all active:scale-95">
                            <span wire:loading.remove wire:target="retryBatchFailures('{{ $batchData['id'] }}')" class="flex items-center justify-center gap-2">
                                <x-lucide-refresh-cw class="w-4 h-4" />
                                Relancer les échecs
                            </span>
                            <span wire:loading wire:target="retryBatchFailures('{{ $batchData['id'] }}')" class="flex items-center gap-2">
                                <x-lucide-loader class="w-4 h-4 animate-spin" />
                                <span> En cours...</span>
                            </span>
                        </button>

                        <button wire:click="deleteBatchFailures('{{ $batchData['id'] }}')" wire:loading.attr="disabled" wire:target="deleteBatchFailures('{{ $batchData['id'] }}')"
                            class="p-2 rounded-2xl bg-red-600 hover:bg-red-700 transition-all">
                            <span wire:loading.remove wire:target="deleteBatchFailures('{{ $batchData['id'] }}')">Suppr. les échecs</span>
                            <span wire:loading wire:target="deleteBatchFailures('{{ $batchData['id'] }}')">
                                <x-lucide-loader class="w-4 h-4 animate-spin" />
                                <span> En cours...</span>
                            </span>
                        </button>

                        <button wire:click="deleteBatchSuccess('{{ $batchData['id'] }}')" wire:loading.attr="disabled" wire:target="deleteBatchSuccess('{{ $batchData['id'] }}')"
                            class="p-2 rounded-2xl bg-red-600 hover:bg-red-700 transition-all">
                            <span wire:loading.remove wire:target="deleteBatchSuccess('{{ $batchData['id'] }}')">Suppr. les succès</span>
                            <span wire:loading wire:target="deleteBatchSuccess('{{ $batchData['id'] }}')">
                                <x-lucide-loader class="w-4 h-4 animate-spin" />
                                <span> En cours...</span>
                            </span>
                        </button>

                        <button wire:click="deleteBatch('{{ $batchData['id'] }}')" wire:loading.attr="disabled" wire:target="deleteBatch('{{ $batchData['id'] }}')" class="p-2 rounded-2xl bg-rose-700 hover:bg-rose-800 transition-all">
                            <span wire:loading.remove wire:target="deleteBatch('{{ $batchData['id'] }}')">Suppr. le batch</span>
                            <span wire:loading wire:target="deleteBatch('{{ $batchData['id'] }}')">
                                <x-lucide-loader class="w-4 h-4 animate-spin" />
                                <span> En cours...</span>
                            </span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- MODAL --}}
    <div x-data="{ open: @entangle('showBatchModal') }" x-show="open" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

        <!-- Backdrop -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeBatchModal" class="absolute inset-0 bg-black/70 backdrop-blur-xl">
        </div>

        <!-- Modal Content -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-4xl max-h-[92vh] flex flex-col shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="flex justify-between items-center p-6 border-b border-slate-700">
                <div>
                    <h2 class="text-2xl font-bold font-mono">Détails du batch</h2>
                    <p class="text-xs text-slate-500 font-mono break-all">{{ $selectedBatchId }}</p>
                </div>
                <button wire:click="closeBatchModal" class="h-10 w-10 rounded-2xl bg-slate-800 hover:bg-slate-700 transition-colors flex items-center justify-center">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>

            @if ($selectedBatchStats)
                {{-- Stats Bar --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-slate-950/50">
                    <!-- Stats cards (inchangées) -->
                    <div class="bg-slate-900 rounded-2xl p-5 text-center">
                        <div class="text-xs text-slate-500">Total</div>
                        <div class="text-3xl font-bold mt-2">{{ $selectedBatchStats['total'] }}</div>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-5 text-center">
                        <div class="text-xs text-slate-500">Succès</div>
                        <div class="text-3xl font-bold text-green-400 mt-2">{{ $selectedBatchStats['success'] }}</div>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-5 text-center">
                        <div class="text-xs text-slate-500">Échecs</div>
                        <div class="text-3xl font-bold text-red-400 mt-2">{{ $selectedBatchStats['failed'] }}</div>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-5 text-center">
                        <div class="text-xs text-slate-500">En attente</div>
                        <div class="text-3xl font-bold text-amber-400 mt-2">{{ $selectedBatchStats['pending'] }}</div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-6 pb-6 flex flex-wrap gap-3">
                    <button wire:click="retryBatchFailures" wire:loading.attr="disabled" wire:target="retryBatchFailures"
                        class="flex-1 md:flex-none px-6 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-black font-semibold transition-all active:scale-95">
                        <span wire:loading.remove wire:target="retryBatchFailures" class="flex items-center justify-center gap-2">
                            <x-lucide-refresh-cw class="w-4 h-4" />
                            Relancer les échecs
                        </span>
                        <span wire:loading wire:target="retryBatchFailures" class="flex items-center justify-center gap-2">
                            <x-lucide-loader class="w-4 h-4 animate-spin" />
                            En cours...
                        </span>
                    </button>

                    <button wire:click="deleteBatchFailures" wire:loading.attr="disabled" wire:target="deleteBatchFailures" class="px-6 py-3 rounded-2xl bg-red-600 hover:bg-red-700 transition-all">
                        <span wire:loading.remove wire:target="deleteBatchFailures">Supprimer les échecs</span>
                        <span wire:loading wire:target="deleteBatchFailures">
                            <x-lucide-loader class="w-4 h-4 animate-spin" />
                            <span> En cours...</span>
                        </span>
                    </button>

                    <button wire:click="deleteBatchSuccess" wire:loading.attr="disabled" wire:target="deleteBatchSuccess" class="px-6 py-3 rounded-2xl bg-red-600 hover:bg-red-700 transition-all">
                        <span wire:loading.remove wire:target="deleteBatchSuccess">Supprimer les succès</span>
                        <span wire:loading wire:target="deleteBatchSuccess">
                            <x-lucide-loader class="w-4 h-4 animate-spin" />
                            <span> En cours...</span>
                        </span>
                    </button>

                    <button wire:click="deleteBatch" wire:loading.attr="disabled" wire:target="deleteBatch" class="px-6 py-3 rounded-2xl bg-rose-700 hover:bg-rose-800 transition-all">
                        <span wire:loading.remove wire:target="deleteBatch">Supprimer le batch</span>
                        <span wire:loading wire:target="deleteBatch">
                            <x-lucide-loader class="w-4 h-4 animate-spin" />
                            <span> En cours...</span>
                        </span>
                    </button>
                </div>
            @endif

            {{-- Liste des tâches --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
                @foreach ($selectedBatchTasks as $task)
                    <div class="border border-slate-700 bg-slate-950 rounded-3xl p-6 hover:border-slate-600 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">
                                    {{ $task->payload['name'] }} {{ $task->payload['prenames'] }}
                                </h3>
                                <p class="text-slate-400">{{ $task->payload['email'] }}</p>
                            </div>

                            @if ($task->status === 'success')
                                <span class="px-4 py-1 bg-green-500/10 text-green-400 rounded-2xl text-sm font-medium">Succès</span>
                            @elseif ($task->status === 'failed')
                                <span class="px-4 py-1 bg-red-500/10 text-red-400 rounded-2xl text-sm font-medium">Échec</span>
                            @else
                                <span class="px-4 py-1 bg-amber-500/10 text-amber-400 rounded-2xl text-sm font-medium">En cours</span>
                            @endif
                        </div>

                        <div class="flex gap-3 mt-6">
                            @if ($task->status === 'failed')
                                <button wire:click="retryOne({{ $task->id }})" wire:loading.attr="disabled" wire:target="retryOne({{ $task->id }})"
                                    class="px-5 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-black text-sm font-medium transition-all">
                                    <span wire:loading.remove wire:target="retryOne({{ $task->id }})">Relancer</span>
                                    <span wire:loading wire:target="retryOne({{ $task->id }})">
                                        <x-lucide-loader class="w-4 h-4 animate-spin" />
                                        <span> En cours...</span>
                                    </span>
                                </button>
                            @endif

                            <button wire:click="deleteOne({{ $task->id }})" wire:loading.attr="disabled" wire:target="deleteOne({{ $task->id }})"
                                class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium transition-all">
                                <span wire:loading.remove wire:target="deleteOne({{ $task->id }})">Supprimer</span>
                                <span wire:loading wire:target="deleteOne({{ $task->id }})">
                                    <x-lucide-loader class="w-4 h-4 animate-spin" />
                                    <span> En cours...</span>
                                </span>
                            </button>
                        </div>

                        @if ($task->error)
                            <div class="mt-4 text-red-400 text-sm bg-red-950/50 border border-red-900/50 p-4 rounded-2xl">
                                {{ $task->error }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 20px;
    }
</style>

