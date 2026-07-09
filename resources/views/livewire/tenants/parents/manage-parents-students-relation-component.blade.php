<div class="space-y-6" wire:key="link-student-tutor-{{ $tutor->id }}">

    {{-- En-tête parent --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-400">Gestion des apprenants liés ou apparentés à</p>
            <h2 class="text-2xl font-bold text-white">
                {{ $tutor->getFullName() }}
            </h2>
        </div>
        <a wire:navigate
            class="rounded-2xl p-2 bg-indigo-600/35 text-white hover:text-black hover:bg-indigo-500 active:scale-95 flex items-center gap-2 px-5 text-sm"
            href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent_uuid]) }}">
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
                placeholder="Rechercher par matricule, n° EducMaster, nom ou prénoms..."
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
                            <p class="text-sm font-medium text-slate-200 flex items-center gap-x-3">
                                <span>{{ $result['fullName'] }}</span>
                                <span class="text-yellow-500/60 font-mono">{{ $result['classe'] }}</span>
                            </p>
                            <p class="text-xs text-slate-500">
                                Matricule : {{ $result['matricule'] }} · EducMaster : {{ $result['educMaster'] }}
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
                            <button type="button" wire:click="selectStudent({{ $result['id'] }})"
                                wire:loading.attr="disabled" wire:target="selectStudent({{ $result['id'] }})"
                                class="rounded-lg bg-indigo-600/50 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 disabled:opacity-50 hover:text-black active:scale-95">
                                <span wire:loading.remove
                                    wire:target="selectStudent({{ $result['id'] }})">Ajouter</span>
                                <span wire:loading wire:target="selectStudent({{ $result['id'] }})">...</span>
                            </button>
                        @endif
                    </li>
                @endforeach
            </ul>
        @elseif (mb_strlen(trim($searchQuery)) >= 2)
            <p class="mt-3 text-sm text-slate-500">Aucun apprenant trouvé.</p>
        @endif
    </div>

    {{-- Tableau des sélections en attente de validation --}}
    @if (count($pendingSelections) > 0)
        <div x-data x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-xl border border-indigo-800/50 bg-indigo-950/20 p-5 space-y-4 w-full">

            <div class="flex items-center justify-between">
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
                <table class=" divide-y divide-slate-800 text-sm w-full">
                    <thead class="bg-slate-900/60">
                        <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                            <th class="px-4 py-3">Apprenant</th>
                            <th class="px-4 py-3">Lien de parenté</th>
                            <th class="px-4 py-3 text-center">Contact principal</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ($pendingSelections as $studentId => $selection)
                            <tr wire:key="pending-{{ $studentId }}" class="bg-slate-900/30">
                                <td class="px-4 py-3 align-top truncate">
                                    <p class="font-medium text-slate-200 flex gap-x-3 items-center">
                                        <span>{{ $selection['data']['fullName'] }}</span>
                                        <span
                                            class="text-yellow-500/60 text-xs font-mono">{{ $selection['data']['classe'] }}</span>
                                    </p>
                                    <p class="text-xs text-slate-500 flex-col flex gap-y-1">
                                        <span> Matricule : {{ $selection['data']['matricule'] }}</span>
                                        <span>EducMaster :
                                            {{ $selection['data']['educMaster'] }}</span>
                                    </p>
                                </td>

                                <td class="px-4 py-3 align-top truncate">
                                    <select wire:model="pendingSelections.{{ $studentId }}.parentRelation"
                                        class="w-full rounded-xl border-slate-700 bg-slate-800 text-sm text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-2 ">
                                        <option class="w-full" value="">-- Sélectionner --</option>
                                        @foreach ($relationTypes as $value => $label)
                                            <option class="w-full" value="{{ $label }}">{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-3 align-top text-center">
                                    <label class="inline-flex cursor-pointer items-center">
                                        <span class="relative inline-flex shrink-0 items-center">
                                            <input type="checkbox"
                                                wire:model="pendingSelections.{{ $studentId }}.isPrimaryContact"
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
                                        <button type="button" wire:click="validateSingle({{ $studentId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="validateSingle({{ $studentId }})"
                                            class="inline-flex items-center gap-1 rounded-lg bg-indigo-600/40 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 disabled:opacity-50 hover:text-black active:scale-95">
                                            <svg wire:loading wire:target="validateSingle({{ $studentId }})"
                                                class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                            </svg>
                                            Valider
                                        </button>

                                        <button type="button" wire:click="removePending({{ $studentId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="removePending({{ $studentId }})"
                                            class="rounded-lg border border-slate-700 px-3 py-1.5 text-xs text-slate-400rounded-2xl text-white bg-red-500/30 hover:bg-red-500 hover:text-black active:scale-95 disabled:opacity-50">
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

    {{-- Liste des apprenants déjà liés --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
        <h3 class="mb-3 text-sm font-semibold text-slate-300 border-b border-b-slate-700 py-3">
            Apprenants déjà en relation ({{ $this->linkedRelations->count() }})
        </h3>

        @forelse ($this->linkedRelations as $relation)
            <div class="flex justify-between items-center gap-3">

                <span class="rounded-2xl p-2 text-center text-black bg-slate-400 font-mono w-1/12">
                    {{ $loop->iteration }}
                </span>
                <div wire:key="linked-{{ $relation->id }}"
                    class="grid grid-cols-8 items-center justify-between border-b border-slate-800 py-3 last:border-b-0 gap-3 w-11/12">
                    <a wire:navigate
                        href="{{ route('tenant.student.profil', ['student_uuid' => $relation->student->uuid]) }}"
                        class="group hover:bg-sky-600/50 hover:text-black flex flex-col rounded-2xl py-1 px-5 bg-sky-400/15 col-span-6">
                        <p class=" text-slate-200 flex gap-3 items-center font-mono text-xs">
                            <span>{{ $relation->student->getFullName() }}</span>

                            @if ($relation->student->currentClasse() && $relation->student->currentClasse()->classe)
                                @php
                                    $rel = $relation->student->currentClasse()->classe;
                                @endphp
                                <span>{{ $rel->code ? $rel->code : $rel->name }}</span>
                            @else
                                <span class="flex gap-1 justify-center text-xs text-slate-600 group-hover:text-black">
                                    <span>Pas encore de</span>
                                    <span>classe en {{ $this->activeYear?->slug }}</span>
                                </span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-500 group-hover:text-black">
                            {{ $relationTypes[$relation->parent_relation] ?? $relation->parent_relation }}
                            @if ($relation->is_primary_contact)
                                · <span class="text-indigo-400">Contact principal</span>
                            @endif
                            @if (!$relation->is_active || $relation->locked)
                                · <span class="text-red-400">Inactif</span>
                            @endif
                        </p>
                    </a>
                    <button type="button" wire:click="removeRelation({{ $relation->student_id }})"
                        wire:loading.attr="disabled" wire:target="removeRelation({{ $relation->student_id }})"
                        class="rounded-2xl px-3 py-3 text-xs text-white bg-red-500/30 hover:bg-red-500 hover:text-black active:scale-95 disabled:opacity-50 col-span-2">
                        <span wire:loading.remove
                            wire:target='removeRelation({{ $relation->student_id }})'>Retirer</span>
                        <span wire:loading wire:target='removeRelation({{ $relation->student_id }})'
                            class="flex items-center gap-x-2">
                            <span class="flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500">Aucun apprenant lié pour l'instant.</p>
        @endforelse
    </div>
</div>

