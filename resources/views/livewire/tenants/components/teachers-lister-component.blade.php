<div class="p-2 bg-indigo-900/10">
    <div class="overflow-x-auto">
        @if (count($this->teachers))
            <table class="z-table-border w-full">
                <thead class="bg-slate-950 border-b border-slate-800">

                    <tr>

                        <th class="px-3 py-4 text-left text-sm text-slate-400">
                            N°
                        </th>
                        <th class="px-3 py-4 text-left text-sm text-slate-400">
                            Enseignant
                        </th>

                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                            Matière
                        </th>

                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                            Classes
                        </th>

                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                            Heures/Sem
                        </th>

                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-800">

                    @foreach ($this->teachers as $teacher)
                        <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }}
                            class="hover:bg-slate-800/40 transition-all">
                            <td class="px-3 py-5 text-center whitespace-nowrap">

                                {{ __zero($this->teachers->firstItem() + $loop->iteration - 1) }}

                            </td>

                            {{-- PROFILE --}}
                            <td class="px-6 py-5 text-slate-400">

                                <a title="Charger le profil de l'enseignant {{ $teacher->getFullName() }}"
                                    href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                    class="flex items-center gap-4 group">

                                    <img src="{{ $teacher->profil_photo_url() }}"
                                        alt="Photo de profil de {{ $teacher->fullName() }}"
                                        class="w-14 h-14 rounded-full object-cover border-4 border-slate-700 group-hover:border-sky-500">
                                    <div class="min-w-0">

                                        <h3
                                            class="font-medium group-hover:underline underline-offset-4 group-hover:text-sky-600">

                                            {{ $teacher->getFullName() }}

                                        </h3>

                                        <p
                                            class="mt-1 text-sm text-slate-400 group-hover:text-sky-700 flex items-center gap-x-1.5">
                                            <x-lucide-mail class="w-3.5 h-3.5 " />
                                            <span>
                                                {{ $teacher->user->email }}
                                            </span>

                                        </p>
                                        <p
                                            class="mt-1 text-sm text-slate-400 group-hover:text-sky-700 font-mono flex items-center gap-x-1.5">

                                            <x-lucide-phone class="w-3.5 h-3.5" />
                                            <span>
                                                {{ $teacher->user->contacts }}
                                            </span>

                                        </p>

                                    </div>

                                </a>
                                @if (!$teacher->hasValidAccessForYear())
                                    <span
                                        class="px-3 rounded-full  bg-red-500/10 text-red-400 animate-pulse border border-slate-600 w-full flex text-xs py-1 mt-2 text-center items-center justify-center gap-x-1">
                                        <span>Accès
                                            {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}</span>
                                        <span> non accordé</span>
                                    </span>
                                @endif

                            </td>

                            {{-- SUBJECT --}}
                            <td class="px-3 py-5 text-center whitespace-nowrap">

                                <div class="mt-1 font-medium flex flex-col gap-2 text-sm justify-center">
                                    @foreach ($teacher->getYearlySubjects() as $yearly_subject)
                                        <span
                                            class="rounded-xl p-1 px-3 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform border border-amber-600/40 uppercase">{{ $yearly_subject->subject->code }}</span>
                                    @endforeach
                                </div>

                            </td>

                            {{-- CLASSES --}}
                            <td class="px-3 py-5 text-center truncate">

                                @php
                                    $teacher_classes = $teacher->getTeacherClassesForThisSchoolYear([]);

                                @endphp
                                @if (count($teacher_classes))
                                    <span class="flex flex-col gap-2 items-center">
                                        @foreach ($teacher_classes as $cl)
                                            <span
                                                class="px-2 py-1 rounded-xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
                                                {{ $cl?->code ?? $cl->name }}
                                            </span>
                                        @endforeach
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-xl text-slate-400 ls-2 italic text-xs flex justify-center flex-col">
                                        <span>Aucune</span>
                                        <span>classe assignée</span>
                                    </span>
                                @endif

                            </td>

                            {{-- HOURS --}}
                            <td class="px-3 py-5 text-center text-gray-500">

                                -

                            </td>

                            <td class="px-3 py-5">
                                <div class="flex flex-wrap items-center gap-2 text-xs">

                                    {{-- Matières --}}
                                    @if ($teacher->hasValidAccessForYear())
                                        <a title="Définir les matières de {{ $teacher->getFullName() }}" wire:navigate
                                            href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-indigo-600/50 hover:bg-indigo-800/50 text-indigo-400 transition-all whitespace-nowrap">
                                            <span>⚙️</span>
                                            <span>Matières</span>
                                        </a>
                                    @endif

                                    {{-- Envoyer credentials --}}
                                    <button title="Envoyer les données de connexion à {{ $teacher->getFullName() }}"
                                        wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 transition-all whitespace-nowrap disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-send class="w-3.5 h-3.5 shrink-0" />
                                            <span>Envoyer</span>
                                        </span>
                                        <span wire:loading
                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>

                                    {{-- Bloquer / Débloquer --}}
                                    <button
                                        title="{{ $teacher->blocked ? 'Débloquer' : 'Bloquer' }} {{ $teacher->getFullName() }}"
                                        wire:click="{{ $teacher->blocked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                        wire:loading.attr="disabled"
                                        wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->blocked ? 'bg-lime-600/50 hover:bg-lime-800/50 text-lime-400' : 'bg-amber-600/50 hover:bg-amber-800/50 text-amber-400' }}">
                                        <span wire:loading.remove
                                            wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            @if ($teacher->blocked)
                                                <x-lucide-lock-keyhole-open class="w-3.5 h-3.5 shrink-0" />
                                                <span>Débloquer</span>
                                            @else
                                                <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                                <span>Bloquer</span>
                                            @endif
                                        </span>
                                        <span wire:loading
                                            wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        @else
            <div class="rounded-3xl p-16 text-center">
                <p class="text-slate-400 text-sm flex-col flex gap-2.5 items-center justify-center">
                    <span class="text-4xl">
                        <x-lucide-user-star class="w-6 h-6" />
                    </span>
                    <span>Aucun enseigant trouvé</span>
                </p>
                @if ($teachers_gender || $teachers_classe_id || $teachers_promotion_id)
                    <button wire:click="resetTeachersFilters"
                        class="mt-4 px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                        <span wire:loading.remove wire:target='resetTeachersFilters'>Réinitialiser
                            les
                            filtres</span>
                        <span wire:loading wire:target='resetTeachersFilters'
                            class="inline-flex justify-center gap-3.5 items-center">
                            <span class="inline-flex justify-center gap-3.5 items-center">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>
                @endif
            </div>

        @endif

    </div>

    {{-- PAGINATION --}}
    @if ($this->teachers->hasPages())
        <section class="">
            <div class="border border-slate-800 bg-slate-900 p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-slate-400">
                        Affichage {{ $this->teachers->firstItem() }} à
                        {{ $this->teachers->lastItem() }} sur
                        {{ $this->teachers->total() }} enseignants
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if (!$this->teachers->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                Précédent
                            </button>
                        @endif

                        @foreach ($this->teachers->getUrlRange(1, $this->teachers->lastPage()) as $page => $url)
                            <button @disabled($page === $this->teachers->currentPage()) wire:click="gotoPage({{ $page }})"
                                class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->teachers->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if ($this->teachers->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                Suivant
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>

