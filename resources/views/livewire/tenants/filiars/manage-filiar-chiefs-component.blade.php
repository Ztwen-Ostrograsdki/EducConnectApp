<div class="min-h-screen bg-[#070a12] text-slate-100" wire:key="manage-filiar-chiefs-{{ $filiar->id }}">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-orange-400/80 mb-1">
                Organisation pédagogique
            </p>
            <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                Chef d’atelier
                <span class="text-orange-400">· {{ $filiar->name }}</span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Un enseignant ne peut occuper qu’un seul poste (principal ou adjoint)
            </p>
        </div>

        {{-- ========== SÉLECTEURS ========== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- CA Principal --}}
            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/[0.04] p-5 space-y-3">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-medium
                             bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                    <x-lucide-star class="w-3.5 h-3.5" />
                    CA Principal
                </span>

                <select wire:model.live="principalId"
                    class="w-full h-11 rounded-xl border border-emerald-500/25 bg-[#070a12]
                               px-3 text-sm text-white
                               focus:border-emerald-500/50 focus:outline-none transition-all">
                    <option value="">— Sélectionner un enseignant —</option>
                    @foreach ($this->availableTeachers as $teacher)
                        <option value="{{ $teacher->id }}" @disabled($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole())
                            @if ($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) title="{{ $teacher->getFullName() }} a déjà un rôle PP ou AE" @endif>
                            {{ $teacher->getFullName() }}
                        </option>
                    @endforeach
                </select>

                @error('principalId')
                    <p class="text-xs text-rose-400 flex items-center gap-1">
                        <x-lucide-alert-circle class="w-3.5 h-3.5" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- CA Adjoint --}}
            <div class="rounded-2xl border border-sky-500/20 bg-sky-500/[0.04] p-5 space-y-3">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-medium
                             bg-sky-500/15 text-sky-400 border border-sky-500/25">
                    <x-lucide-user-check class="w-3.5 h-3.5" />
                    CA Adjoint
                </span>

                <select wire:model.live="adjointId"
                    class="w-full h-11 rounded-xl border border-sky-500/25 bg-[#070a12]
                               px-3 text-sm text-white
                               focus:border-sky-500/50 focus:outline-none transition-all">
                    <option value="">— Sélectionner un enseignant —</option>
                    @foreach ($this->availableTeachers as $teacher)
                        <option value="{{ $teacher->id }}" @disabled($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole())
                            @if ($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) title="{{ $teacher->getFullName() }} a déjà un rôle PP ou AE" @endif>
                            {{ $teacher->getFullName() }}
                        </option>
                    @endforeach
                </select>

                @error('adjointId')
                    <p class="text-xs text-rose-400 flex items-center gap-1">
                        <x-lucide-alert-circle class="w-3.5 h-3.5" />
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- ========== CHOIX ACTIFS ========== --}}
        <section class="space-y-3">
            @if ($this->principalTeacher || $this->adjointTeacher)
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-orange-400"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Choix en cours
                        </h2>
                    </div>
                    @if ($this->principalTeacher && $this->adjointTeacher)
                        <button wire:click="removeChiefs" wire:loading.attr="disabled" wire:target="removeChiefs"
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-[11px] font-medium
                                       bg-rose-500/15 text-rose-400 border border-rose-500/25
                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="removeChiefs"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                Tout retirer
                            </span>
                            <span wire:loading wire:target="removeChiefs" class="inline-flex items-center gap-1.5">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                Nettoyage…
                            </span>
                        </button>
                    @endif
                </div>
            @else
                <div class="rounded-xl border border-dashed border-white/[0.08] bg-white/[0.02] py-8 text-center">
                    <p class="text-sm text-slate-500 animate-pulse">Aucun choix encore effectué</p>
                </div>
            @endif

            @if ($this->principalTeacher)
                <div wire:key="principal-active-{{ $principalId }}"
                    class="flex items-center justify-between gap-3 rounded-xl
                            border border-emerald-500/20 bg-emerald-500/[0.06] px-4 py-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span
                            class="shrink-0 w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/25
                                     flex items-center justify-center">
                            <x-lucide-star class="w-4 h-4 text-emerald-400" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500">CA Principal</p>
                            <p class="text-sm font-medium text-white truncate">
                                {{ $this->principalTeacher->getFullName() }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="removeChief('principal')" wire:loading.attr="disabled"
                        wire:target="removeChief('principal')"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                   bg-rose-500/15 text-rose-400 border border-rose-500/20
                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                   transition-all disabled:opacity-50 shrink-0">
                        <span wire:loading.remove wire:target="removeChief('principal')">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </span>
                        <span wire:loading wire:target="removeChief('principal')">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>
            @endif

            @if ($this->adjointTeacher)
                <div wire:key="adjoint-active-{{ $adjointId }}"
                    class="flex items-center justify-between gap-3 rounded-xl
                            border border-sky-500/20 bg-sky-500/[0.06] px-4 py-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span
                            class="shrink-0 w-8 h-8 rounded-lg bg-sky-500/15 border border-sky-500/25
                                     flex items-center justify-center">
                            <x-lucide-user-check class="w-4 h-4 text-sky-400" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500">CA Adjoint</p>
                            <p class="text-sm font-medium text-white truncate">
                                {{ $this->adjointTeacher->getFullName() }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="removeChief('adjoint')" wire:loading.attr="disabled"
                        wire:target="removeChief('adjoint')"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                   bg-rose-500/15 text-rose-400 border border-rose-500/20
                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                   transition-all disabled:opacity-50 shrink-0">
                        <span wire:loading.remove wire:target="removeChief('adjoint')">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </span>
                        <span wire:loading wire:target="removeChief('adjoint')">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>
            @endif
        </section>

        {{-- ========== SAVE ========== --}}
        @if ($principalId || $adjointId)
            <div class="flex justify-end pt-2">
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                               bg-indigo-500 hover:bg-indigo-400 text-white
                               shadow-lg shadow-indigo-500/20
                               transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <x-lucide-check class="w-4 h-4" />
                        Enregistrer les CA
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        Enregistrement…
                    </span>
                </button>
            </div>
        @endif

    </div>
</div>

