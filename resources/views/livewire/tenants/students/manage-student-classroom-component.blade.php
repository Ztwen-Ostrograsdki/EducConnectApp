<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- ========== CARTE APPRENANT ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="relative p-5 sm:p-6">
                <div
                    class="absolute top-0 right-0 w-48 h-48 bg-sky-500/10 rounded-full blur-[60px]
                            -translate-y-1/2 translate-x-1/3 pointer-events-none">
                </div>

                <a wire:navigate href="{{ $this->student->toProfilRoute() }}"
                    class="relative flex items-center gap-4 group">
                    <div
                        class="w-16 h-16 rounded-2xl overflow-hidden border border-white/[0.08]
                                group-hover:border-sky-500/40 transition-all shrink-0 shadow-inner">
                        <img src="{{ $this->student->profil_photo_url }}" alt="{{ $this->student->getFullName() }}"
                            class="w-full h-full object-cover" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500 mb-0.5">
                            Apprenant
                        </p>
                        <h2 class="text-lg font-bold text-white group-hover:text-sky-400 transition-colors truncate">
                            {{ $this->student->getFullName() }}
                        </h2>
                    </div>
                    <x-lucide-chevron-right
                        class="w-4 h-4 text-slate-600 group-hover:text-sky-400
                                                   ml-auto shrink-0 transition-colors" />
                </a>

                <div class="relative mt-4 flex flex-wrap gap-2">
                    @if ($this->currentClasse)
                        <span
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                     bg-sky-500/15 text-sky-400 border border-sky-500/25 font-mono uppercase">
                            <x-lucide-school class="w-3.5 h-3.5" />
                            {{ $this->currentClasse->name }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                     bg-amber-500/10 text-amber-400 border border-amber-500/20 font-mono">
                            <x-lucide-hash class="w-3.5 h-3.5" />
                            {{ $this->student->matricule }}
                        </span>
                        @if ($this->student->educMaster)
                            <span
                                class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                         bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                <x-lucide-user class="w-3.5 h-3.5" />
                                {{ $this->student->educMaster }}
                            </span>
                        @endif
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                     bg-rose-500/15 text-rose-400 border border-rose-500/25">
                            <x-lucide-alert-circle class="w-3.5 h-3.5" />
                            Aucune classe assignée
                        </span>
                    @endif
                </div>
            </div>
        </section>

        {{-- ========== RETIRER CLASSE ========== --}}
        @if ($this->currentClasse)
            <div class="flex justify-end">
                <button type="button" wire:click="removeStudentFromCurrent" wire:loading.attr="disabled"
                    wire:target="removeStudentFromCurrent"
                    class="inline-flex items-center gap-1.5 h-9 px-4 rounded-xl text-xs font-medium
                               bg-rose-500/15 text-rose-400 border border-rose-500/25
                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                               transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="removeStudentFromCurrent"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-user-x class="w-3.5 h-3.5" />
                        Retirer la classe actuelle
                    </span>
                    <span wire:loading wire:target="removeStudentFromCurrent" class="inline-flex items-center gap-1.5">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        Traitement…
                    </span>
                </button>
            </div>
        @endif

        {{-- ========== ERREURS ========== --}}
        @if ($errors->any())
            <div
                class="rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-3
                        flex items-center gap-2.5 text-sm text-rose-300 animate-pulse">
                <x-lucide-alert-circle class="w-4 h-4 shrink-0" />
                Le formulaire est incorrect
            </div>
        @endif

        {{-- ========== FORMULAIRE MIGRATION ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6 relative">
            <div wire:loading wire:target="migrateStudent"
                class="absolute inset-0 z-10 flex items-center justify-center
                        rounded-2xl bg-[#070a12]/70 backdrop-blur-sm">
                <x-lucide-loader-2 class="w-7 h-7 text-sky-400 animate-spin" />
            </div>

            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    {{ $this->currentClasse ? 'Migrer vers' : 'Assigner à' }} une classe
                </h2>
            </div>

            <label
                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                <x-lucide-school class="w-3.5 h-3.5 text-sky-400" />
                Classe cible
            </label>

            <x-select searchable wire:model="selectedClasseId" :options="$this->availableClasses" option-label="name" option-value="id"
                placeholder="Rechercher une classe…">
                @foreach ($this->availableClasses as $classe)
                    <x-select.option :value="$classe->id" :label="$classe->name" :disabled="$this->currentClasse && $classe->id === $this->currentClasse->id" />
                @endforeach
            </x-select>

            <div class="mt-5 flex justify-end">
                <button type="button" wire:click="confirmMigration" wire:loading.attr="disabled"
                    wire:target="confirmMigration"
                    class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                               bg-sky-500 hover:bg-sky-400 text-white
                               shadow-lg shadow-sky-500/20
                               transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="confirmMigration" class="inline-flex items-center gap-2">
                        <x-lucide-arrow-right-left class="w-4 h-4" />
                        {{ $this->currentClasse ? "Migrer l'apprenant" : 'Assigner la classe' }}
                    </span>
                    <span wire:loading wire:target="confirmMigration" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        Traitement…
                    </span>
                </button>
            </div>
        </section>

    </div>
</div>
