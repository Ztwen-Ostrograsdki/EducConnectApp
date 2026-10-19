<div class="min-h-screen bg-[#070a12] text-slate-100" wire:key="link-student-tutor-{{ $tutor->id }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-sky-400/80 mb-1">
                        Vie scolaire
                    </p>
                    <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight truncate">
                        {{ $tutor->getFullName() }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Apprenants liés ou apparentés
                    </p>
                </div>
                <a wire:navigate href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent_uuid]) }}"
                    class="inline-flex items-center gap-2 h-9 px-4 rounded-xl text-xs font-medium
                          bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                          hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                          transition-all shrink-0">
                    <x-lucide-user class="w-3.5 h-3.5" />
                    Profil parent
                </a>
            </div>
        </section>

        {{-- ========== RECHERCHE ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Rechercher un apprenant
                </h2>
            </div>

            <div class="relative">
                <x-lucide-search
                    class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                <input type="text" wire:model.live.debounce.400ms="searchQuery"
                    placeholder="Matricule, EducMaster, nom ou prénoms…"
                    class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                              pl-10 pr-10 text-sm text-white placeholder:text-slate-600
                              focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/20
                              outline-none transition-all" />
                <div wire:loading wire:target="searchQuery" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                    <x-lucide-loader-2 class="w-4 h-4 text-sky-400 animate-spin" />
                </div>
            </div>

            @if (count($searchResults) > 0)
                <ul class="mt-2 divide-y divide-white/[0.04] rounded-xl border border-white/[0.06] overflow-hidden">
                    @foreach ($searchResults as $result)
                        <li wire:key="result-{{ $result['id'] }}"
                            class="flex items-center justify-between gap-3 px-4 py-3
                                   {{ $result['already_linked'] || $result['already_pending'] ? 'opacity-50' : 'hover:bg-white/[0.02]' }} transition-colors">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white flex flex-wrap items-center gap-2">
                                    <span class="truncate">{{ $result['fullName'] }}</span>
                                    @if (!empty($result['classe']))
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] font-mono
                                                     bg-amber-500/15 text-amber-400 border border-amber-500/20">
                                            {{ $result['classe'] }}
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Matricule · {{ $result['matricule'] }}
                                    · EducMaster · {{ $result['educMaster'] }}
                                </p>
                            </div>

                            @if ($result['already_linked'])
                                <span
                                    class="shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-medium
                                             bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                    Déjà lié
                                </span>
                            @elseif ($result['already_pending'])
                                <span
                                    class="shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-medium
                                             bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                    En attente
                                </span>
                            @else
                                <button type="button" wire:click="selectStudent({{ $result['id'] }})"
                                    wire:loading.attr="disabled" wire:target="selectStudent({{ $result['id'] }})"
                                    class="shrink-0 inline-flex items-center gap-1 h-8 px-3 rounded-lg text-[11px] font-medium
                                               bg-sky-500/15 text-sky-400 border border-sky-500/25
                                               hover:bg-sky-500 hover:text-white hover:border-sky-500
                                               transition-all disabled:opacity-50">
                                    <span wire:loading.remove wire:target="selectStudent({{ $result['id'] }})">
                                        Ajouter
                                    </span>
                                    <span wire:loading wire:target="selectStudent({{ $result['id'] }})">
                                        <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                    </span>
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @elseif (mb_strlen(trim($searchQuery)) >= 2)
                <p class="text-sm text-slate-500 text-center py-3">Aucun apprenant trouvé</p>
            @endif
        </section>

        {{-- ========== SÉLECTIONS EN ATTENTE ========== --}}
        @if (count($pendingSelections) > 0)
            <section class="rounded-2xl border border-sky-500/20 bg-sky-500/[0.04] p-5 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            En attente
                            <span class="text-sky-400 font-mono ml-1">{{ count($pendingSelections) }}</span>
                        </h2>
                    </div>
                    <button type="button" wire:click="validateAll" wire:loading.attr="disabled"
                        wire:target="validateAll"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                                   bg-emerald-500 hover:bg-emerald-400 text-white
                                   shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="validateAll" class="inline-flex items-center gap-1.5">
                            <x-lucide-check-check class="w-3.5 h-3.5" />
                            Valider tout
                        </span>
                        <span wire:loading wire:target="validateAll">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-white/[0.06]">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/[0.05]">
                                <th
                                    class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Apprenant</th>
                                <th
                                    class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Lien</th>
                                <th
                                    class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Principal</th>
                                <th
                                    class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach ($pendingSelections as $studentId => $selection)
                                <tr wire:key="pending-{{ $studentId }}"
                                    class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-white text-sm flex flex-wrap items-center gap-2">
                                            <span>{{ $selection['data']['fullName'] }}</span>
                                            @if (!empty($selection['data']['classe']))
                                                <span
                                                    class="px-1.5 py-0.5 rounded text-[10px] font-mono
                                                             bg-amber-500/15 text-amber-400 border border-amber-500/20">
                                                    {{ $selection['data']['classe'] }}
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Mat. {{ $selection['data']['matricule'] }}
                                            · EM {{ $selection['data']['educMaster'] }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <select wire:model="pendingSelections.{{ $studentId }}.parentRelation"
                                            class="w-full h-9 rounded-lg border border-white/[0.08] bg-[#070a12]
                                                       px-2 text-xs text-white
                                                       focus:border-sky-500/50 focus:outline-none transition-all">
                                            <option value="">— Sélectionner —</option>
                                            @foreach ($relationTypes as $value => $label)
                                                <option value="{{ $label }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="inline-flex cursor-pointer items-center">
                                            <input type="checkbox"
                                                wire:model="pendingSelections.{{ $studentId }}.isPrimaryContact"
                                                class="sr-only peer" />
                                            <div
                                                class="relative h-6 w-11 rounded-full bg-slate-700
                                                        peer-checked:bg-sky-500 transition-colors">
                                                <div
                                                    class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow
                                                            transition-transform peer-checked:translate-x-5">
                                                </div>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" wire:click="validateSingle({{ $studentId }})"
                                                wire:loading.attr="disabled"
                                                wire:target="validateSingle({{ $studentId }})"
                                                class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                           bg-sky-500/15 text-sky-400 border border-sky-500/25
                                                           hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                           transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="validateSingle({{ $studentId }})">
                                                    Valider
                                                </span>
                                                <span wire:loading wire:target="validateSingle({{ $studentId }})">
                                                    <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                                </span>
                                            </button>
                                            <button type="button" wire:click="removePending({{ $studentId }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removePending({{ $studentId }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                           bg-white/[0.04] text-slate-500 border border-white/[0.06]
                                                           hover:bg-rose-500/15 hover:text-rose-400 hover:border-rose-500/25
                                                           transition-all disabled:opacity-50">
                                                <x-lucide-x class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        {{-- ========== APPRENANTS LIÉS ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="px-5 py-4 border-b border-white/[0.05] flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Apprenants liés
                    <span class="text-amber-400 font-mono ml-1">{{ $this->linkedRelations->count() }}</span>
                </h2>
            </div>

            <div class="divide-y divide-white/[0.04]">
                @forelse ($this->linkedRelations as $relation)
                    <div wire:key="linked-{{ $relation->id }}"
                        class="flex flex-col sm:flex-row sm:items-center gap-3 p-4 sm:p-5">
                        <span
                            class="hidden sm:flex w-8 h-8 rounded-lg bg-white/[0.04] border border-white/[0.06]
                                     items-center justify-center text-xs font-mono text-slate-500 shrink-0">
                            {{ $loop->iteration }}
                        </span>

                        <a wire:navigate
                            href="{{ route('tenant.student.profil', ['student_uuid' => $relation->student->uuid]) }}"
                            class="flex-1 min-w-0 group rounded-xl p-2 -m-2 hover:bg-white/[0.03] transition-colors">
                            <p
                                class="text-sm font-medium text-white group-hover:text-sky-400 transition-colors
                                      flex flex-wrap items-center gap-2">
                                <span class="truncate">{{ $relation->student->getFullName() }}</span>
                                @if ($relation->student->currentClasse() && $relation->student->currentClasse()->classe)
                                    @php $rel = $relation->student->currentClasse()->classe; @endphp
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-mono
                                                 bg-amber-500/15 text-amber-400 border border-amber-500/20">
                                        {{ $rel->code ?: $rel->name }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-600">
                                        Pas de classe · {{ $this->activeYear?->slug }}
                                    </span>
                                @endif
                            </p>
                            <div class="mt-1 flex flex-wrap items-center gap-1.5 text-xs">
                                <span class="text-slate-500">
                                    {{ $relationTypes[$relation->parent_relation] ?? $relation->parent_relation }}
                                </span>
                                @if ($relation->is_primary_contact)
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                                 bg-violet-500/15 text-violet-400 border border-violet-500/20">
                                        Principal
                                    </span>
                                @endif
                                @if (!$relation->is_active || $relation->locked)
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                                 bg-rose-500/15 text-rose-400 border border-rose-500/20">
                                        Inactif
                                    </span>
                                @else
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                                 bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">
                                        Actif
                                    </span>
                                @endif
                            </div>
                        </a>

                        <button type="button" wire:click="removeRelation({{ $relation->student_id }})"
                            wire:loading.attr="disabled" wire:target="removeRelation({{ $relation->student_id }})"
                            class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                       bg-rose-500/15 text-rose-400 border border-rose-500/25
                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                       transition-all disabled:opacity-50 shrink-0">
                            <span wire:loading.remove wire:target="removeRelation({{ $relation->student_id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-user-x class="w-3.5 h-3.5" />
                                Retirer
                            </span>
                            <span wire:loading wire:target="removeRelation({{ $relation->student_id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/[0.04] mb-3">
                            <x-lucide-graduation-cap class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm text-slate-500">Aucun apprenant lié pour l’instant</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</div>
