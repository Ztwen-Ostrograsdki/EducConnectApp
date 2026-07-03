<div class="mx-auto p-6" wire:key="manage-filiar-chiefs-{{ $filiar->id }}">

    <div class="mb-6" x-data x-init="$el.classList.add('opacity-100')" class="transition-opacity duration-500 opacity-0">
        <h2 class="text-xl font-semibold text-slate-400">Chef d'atelier (CA) — {{ $filiar->name }}</h2>
        <p class="text-sm text-slate-500 mt-1">Un enseignant ne peut occuper qu'un seul poste (CA principal ou CA
            adjoint).</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- CA Principal --}}
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
            x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-2xl border border-green-200/20 bg-green-800/30 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-green-500 text-white text-xs font-medium px-3 py-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2l2.39 4.84L18 7.64l-4 3.9.94 5.46L10 14.5l-4.94 2.5L6 11.54l-4-3.9 5.61-.8z" />
                    </svg>
                    CA Principal
                </span>
            </div>

            <select wire:model.live="principalId"
                class="w-full rounded-lg border border-green-600 text-sm transition-colors p-3 bg-green-800">
                <option class="p-3" value="">— Sélectionner un enseignant —</option>
                @foreach ($this->availableTeachers as $teacher)
                    <option
                        @if ($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) title="{{ $teacher->getFullName() }} a déjà au moins un rôle de PP ou de AE" @endif
                        @disabled($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) class="p-3" value="{{ $teacher->id }}">
                        {{ $teacher->getFullName() }}</option>
                @endforeach
            </select>

            @error('principalId')
                <p class="text-xs text-red-600 mt-2 flex items-center gap-1"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- CA Adjoint --}}
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 150)" x-show="show"
            x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-2xl border border-sky-600 bg-sky-800/30 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-sky-500 text-white text-xs font-medium px-3 py-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    CA Adjoint
                </span>
            </div>

            <select wire:model.live="adjointId"
                class="w-full p-3 rounded-lg border border-sky-500 text-sm transition-colors bg-sky-900">
                <option class="p-3" value="">— Sélectionner un enseignant —</option>
                @foreach ($this->availableTeachers as $teacher)
                    <option
                        @if ($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) title="{{ $teacher->getFullName() }} a déjà au moins un rôle de PP ou de AE" @endif
                        @disabled($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()) class="p-3" value="{{ $teacher->id }}">
                        {{ $teacher->getFullName() }}</option>
                @endforeach
            </select>

            @error('adjointId')
                <p class="text-xs text-red-600 mt-2" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col gap-4 my-5">
        @if ($this->principalTeacher && $this->adjointTeacher)
            <h5 class="border-b border-b-slate-600 mt-5 uppercase text-slate-500 flex justify-between py-3">
                <span>
                    Voici les choix en cours ou actifs
                </span>
                <button wire:click="removeChiefs" wire:loading.attr="disabled" wire:target="removeChiefs"
                    class="flex items-center gap-1.5 py-2 rounded-2xl bg-red-500/60 hover:bg-red-500 px-10 text-xs text-black hover:text-red-900 transition-colors disabled:opacity-50">
                    <svg wire:loading wire:target="removeChiefs" class="animate-spin h-3.5 w-3.5" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="removeChiefs">Tout retirer</span>
                    <span wire:loading wire:target="removeChiefs">Nettoyage...</span>
                </button>
            </h5>
        @endif

        @if (!$this->principalTeacher && !$this->adjointTeacher)
            <h5 class=" py-3 text-center text-rose-600/80 font-semibold ls-1 text-xl animate-pulse">
                Aucun choix encore effectué!
            </h5>
        @endif

        @if ($this->principalTeacher)
            <div wire:key="principal-active-{{ $principalId }}" x-data="{ show: false }" x-init="setTimeout(() => show = true, 30)"
                x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="flex items-center justify-between rounded-lg bg-green-900/40 border border-green-700/40 p-4">
                <span class="text-sm text-slate-200 font-medium">
                    <span class="text-slate-500 mr-3">CA PRINCIPAL</span>
                    <span>{{ $this->principalTeacher->getFullName() }}</span>
                </span>

                <button wire:click="removeChief('principal')" wire:loading.attr="disabled"
                    wire:target="removeChief('principal')"
                    class="inline-flex items-center gap-1.5 py-2 rounded-2xl bg-red-500/40 hover:bg-red-500 px-8 text-xs text-black hover:text-red-200 transition-colors disabled:opacity-50">
                    <svg wire:loading wire:target="removeChief('principal')" class="animate-spin h-3.5 w-3.5"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="removeChief('principal')">Retirer</span>
                    <span wire:loading wire:target="removeChief('principal')">Retrait…</span>
                </button>
            </div>
        @endif
        @if ($this->adjointTeacher)
            <div wire:key="adjoint-active-{{ $adjointId }}" x-data="{ show: false }" x-init="setTimeout(() => show = true, 30)"
                x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="flex items-center justify-between rounded-lg bg-sky-900/40 p-4 border border-sky-700/40 ">
                <span class="text-sm text-slate-200 font-medium">
                    <span class="text-slate-500 mr-3">CA ADJOINT</span>
                    <span>{{ $this->adjointTeacher->getFullName() }}</span>
                </span>

                <button wire:click="removeChief('adjoint')" wire:loading.attr="disabled"
                    wire:target="removeChief('adjoint')"
                    class="inline-flex items-center gap-1.5 py-2 rounded-2xl bg-red-500/40 hover:bg-red-500 px-8 text-xs text-black hover:text-red-200 transition-colors disabled:opacity-50">
                    <svg wire:loading wire:target="removeChief('adjoint')" class="animate-spin h-3.5 w-3.5"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="removeChief('adjoint')">Retirer</span>
                    <span wire:loading wire:target="removeChief('adjoint')">Retrait…</span>
                </button>
            </div>
        @endif
    </div>

    @if ($principalId || $adjointId)
        <div class="mt-6 flex justify-end">
            <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="save" class="animate-spin h-4 w-4" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                    </path>
                </svg>
                <span wire:loading.remove wire:target="save">Enregistrer les CA</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </button>
        </div>
    @endif
</div>

