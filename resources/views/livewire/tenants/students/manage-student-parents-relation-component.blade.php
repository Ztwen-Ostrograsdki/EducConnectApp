<div class="space-y-6" wire:key="link-parent-student-{{ $student->id }}">

    {{-- En-tête apprenant --}}
    <div class="rounded-xl border w-full flex items-center justify-between border-slate-800 bg-slate-900/60 p-5">
        <div>
            <p class="text-sm text-slate-400">Gestion des parents / tuteurs de l'apprenant(e)</p>
            <h2 class="text-2xl font-bold text-white">
                {{ $student->getFullName() }}
            </h2>
        </div>
        <a wire:navigate
            class="rounded-2xl p-2 bg-indigo-600/35 text-white hover:text-black hover:bg-indigo-500 active:scale-95 flex items-center gap-2 px-5 text-sm"
            href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}">
            <x-lucide-user class="w-4 h-4 " />
            <span>
                <span class="md:inline-flex hidden">Aller à la page</span>
                profil</span>
        </a>
    </div>

    {{-- Barre de recherche unique, multi-colonnes --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5 space-y-3">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104 4a7.5 7.5 0 0012.65 12.65z" />
            </svg>

            <input type="text" wire:model.live.debounce.400ms="searchQuery"
                placeholder="Rechercher un parent par nom, prénoms, email ou téléphone..."
                class="w-full rounded-2xl border-slate-700 bg-slate-800 py-4 pl-10 pr-10 text-sm text-slate-200 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">

            <div wire:loading wire:target="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="h-4 w-4 animate-spin text-indigo-400" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
            </div>
        </div>

        {{-- Résultats --}}
        @if (count($searchResults) > 0)
            <ul class="mt-3 divide-y divide-slate-800 rounded-lg border border-slate-800">
                @foreach ($searchResults as $result)
                    <li wire:key="result-{{ $result['id'] }}"
                        class="flex items-center justify-between gap-3 px-4 py-3 {{ $result['already_linked'] || $result['already_pending'] ? 'opacity-50' : 'hover:bg-slate-800/60' }}">
                        <div>
                            <p class="text-sm font-medium text-slate-200">{{ $result['fullName'] }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $result['email'] ?? '—' }} · {{ $result['phone'] ?? '—' }}
                            </p>
                        </div>

                        @if ($result['already_linked'])
                            <span class="rounded-full bg-slate-700 px-3 py-1 text-xs text-slate-300">
                                Déjà lié
                            </span>
                        @elseif ($result['already_pending'])
                            <span class="rounded-full bg-amber-900/40 px-3 py-1 text-xs text-amber-300">
                                En attente
                            </span>
                        @else
                            <button type="button" wire:click="selectTutor({{ $result['id'] }})"
                                wire:loading.attr="disabled" wire:target="selectTutor({{ $result['id'] }})"
                                class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove wire:target="selectTutor({{ $result['id'] }})">Ajouter</span>
                                <span wire:loading wire:target="selectTutor({{ $result['id'] }})">...</span>
                            </button>
                        @endif
                    </li>
                @endforeach
            </ul>
        @elseif (mb_strlen(trim($searchQuery)) >= 2)
            <p class="mt-3 text-sm text-slate-500">Aucun parent trouvé.</p>
        @endif
    </div>

    {{-- Tableau des sélections en attente de validation --}}
    @if (count($pendingSelections) > 0)
        <div x-data x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-xl border border-indigo-800/50 bg-indigo-950/20 p-5 space-y-4 w-full">

            <div class="flex items-center justify-between w-full">
                <h3 class="text-sm font-semibold text-indigo-300">
                    Sélections en attente ({{ count($pendingSelections) }})
                </h3>

                <button type="button" wire:click="validateAll" wire:loading.attr="disabled" wire:target="validateAll"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 disabled:opacity-50">
                    <svg wire:loading wire:target="validateAll" class="h-4 w-4 animate-spin" viewBox="0 0 24 24"
                        fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    Valider tout
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-800 w-full">
                <table class="w-full divide-y divide-slate-800 text-sm">
                    <thead class="bg-slate-900/60">
                        <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                            <th class="px-4 py-3">Parent</th>
                            <th class="px-4 py-3">Lien de parenté</th>
                            <th class="px-4 py-3 text-center">Contact principal</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ($pendingSelections as $tutorId => $selection)
                            <tr wire:key="pending-{{ $tutorId }}" class="bg-slate-900/30">
                                <td class="px-4 py-3 align-top">
                                    <p class="font-medium text-slate-200">{{ $selection['data']['fullName'] }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $selection['data']['email'] ?? '—' }} ·
                                        {{ $selection['data']['phone'] ?? '—' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <select wire:model="pendingSelections.{{ $tutorId }}.parentRelation"
                                        class="w-full rounded-xl border-slate-700 bg-slate-800 text-sm text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-2">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($relationTypes as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-3 align-top text-center">
                                    <label class="inline-flex cursor-pointer items-center">
                                        <span class="relative inline-flex shrink-0 items-center">
                                            <input type="checkbox"
                                                wire:model="pendingSelections.{{ $tutorId }}.isPrimaryContact"
                                                class="peer sr-only">
                                            <span
                                                class="h-6 w-11 rounded-full bg-slate-600 transition-colors duration-200 peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-500/50 peer-focus:ring-offset-2 peer-focus:ring-offset-slate-900"></span>
                                            <span
                                                class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-5"></span>
                                        </span>
                                    </label>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" wire:click="validateSingle({{ $tutorId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="validateSingle({{ $tutorId }})"
                                            class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 disabled:opacity-50">
                                            <svg wire:loading wire:target="validateSingle({{ $tutorId }})"
                                                class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                            </svg>
                                            Valider
                                        </button>

                                        <button type="button" wire:click="removePending({{ $tutorId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="removePending({{ $tutorId }})"
                                            class="rounded-lg border border-slate-700 px-3 py-1.5 text-xs text-slate-400 hover:bg-slate-800 hover:text-red-400 disabled:opacity-50">
                                            Retirer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Liste des parents déjà liés --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
        <h3 class="mb-3 text-lg font-semibold text-amber-600 border-b uppercase pb-2">
            Parents / tuteurs liés <span class="font-mono">({{ $this->linkedRelations->count() }})</span>
        </h3>

        @forelse ($this->linkedRelations as $relation)
            <div wire:key="linked-{{ $relation->id }}" class="border-b border-slate-800 py-3 last:border-b-0">

                @if ($editingRelationId === $relation->id)
                    {{-- Formulaire d'édition inline --}}
                    <div x-data x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="rounded-lg border border-indigo-800/50 bg-indigo-950/30 p-4 space-y-3">

                        <p class="text-sm font-semibold text-white">{{ $relation->tutor->getFullName() }}</p>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs text-slate-400">Lien de parenté</label>
                                <select wire:model="editParentRelation"
                                    class="w-full rounded-xl border-slate-700 bg-slate-800 text-sm text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-2">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($relationTypes as $value => $label)
                                        <option value="{{ $label }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('editParentRelation')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col justify-center gap-2">
                                <label
                                    class="flex items-center justify-between rounded-xl border border-slate-700/60 bg-slate-800/40 px-3 py-2">
                                    <span class="text-xs text-slate-300">Contact principal</span>
                                    <span class="relative inline-flex shrink-0 items-center">
                                        <input type="checkbox" wire:model="editIsPrimaryContact"
                                            class="peer sr-only">
                                        <span
                                            class="h-5 w-9 rounded-full bg-slate-600 transition-colors duration-200 peer-checked:bg-indigo-600"></span>
                                        <span
                                            class="absolute left-1 top-1 h-3 w-3 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-4"></span>
                                    </span>
                                </label>

                                <label
                                    class="flex items-center justify-between rounded-xl border border-slate-700/60 bg-slate-800/40 px-3 py-2">
                                    <span class="text-xs text-slate-300">Accès actif</span>
                                    <span class="relative inline-flex shrink-0 items-center">
                                        <input type="checkbox" wire:model="editIsActive" class="peer sr-only">
                                        <span
                                            class="h-5 w-9 rounded-full bg-slate-600 transition-colors duration-200 peer-checked:bg-emerald-600"></span>
                                        <span
                                            class="absolute left-1 top-1 h-3 w-3 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-4"></span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-1 justify-end">
                            <button type="button" wire:click="updateRelation" wire:loading.attr="disabled"
                                wire:target="updateRelation"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-500 disabled:opacity-50 px-4 py-3 active:scale-95">
                                <svg wire:loading wire:target="updateRelation" class="h-3 w-3 animate-spin"
                                    viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                </svg>
                                Enregistrer
                            </button>
                            <button type="button" wire:click="cancelEdit"
                                class="rounded-lg border border-slate-700 px-4 py-3 text-xs text-slate-300 hover:bg-slate-800 active:scale-95">
                                Annuler
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Affichage standard de la ligne --}}
                    <div class="grid grid-cols-9 items-center justify-between gap-3 w-full">
                        <a wire:navigate
                            href="{{ route('tenant.parent.profil', ['parent_uuid' => $relation->tutor->uuid]) }}"
                            class="group active:scale-95 rounded-2xl p-2 hover:bg-green-700/25 col-span-5">
                            <p class="text-sm text-slate-200 ">
                                {{ $relation->tutor->getFullName() }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $relationTypes[$relation->parent_relation] ?? $relation->parent_relation }}
                                @if ($relation->is_primary_contact)
                                    · <span class="text-indigo-400">Contact principal</span>
                                @endif
                                @if ($relation->locked)
                                    · <span class="text-amber-400">Verrouillé</span>
                                @elseif (!$relation->is_active)
                                    · <span class="text-red-400">Inactif</span>
                                @else
                                    · <span class="text-emerald-400">Actif</span>
                                @endif
                            </p>
                        </a>

                        <div class="flex items-center justify-end gap-2 col-span-4 text-center">
                            <button type="button" wire:click="startEdit({{ $relation->id }})"
                                wire:loading.attr="disabled" wire:target="startEdit({{ $relation->id }})"
                                @if ($relation->locked) disabled @endif
                                class="rounded-lg  px-6 py-3.5 active:scale-95 text-xs text-white hover:text-black bg-indigo-600/40 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-40 flex items-center gap-x-2 w-1/2 justify-center">
                                <x-lucide-user-pen class="w-4 h-4" />
                                <span>Éditer</span>
                            </button>

                            <button type="button" wire:click="confirmDeleteRelation({{ $relation->id }})"
                                wire:loading.attr="disabled" wire:target="confirmDeleteRelation({{ $relation->id }})"
                                @if ($relation->locked) disabled @endif
                                class="rounded-lg bg-red-500/30 px-6 py-3.5 text-white active:scale-95 text-xs hover:text-black  hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-40 flex items-center gap-x-2 w-1/2 justify-center">
                                <x-lucide-user-x class="w-4 h-4" />
                                <span>Délier</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-slate-500">Aucun parent lié pour l'instant.</p>
        @endforelse
    </div>
</div>

