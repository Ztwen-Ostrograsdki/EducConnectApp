<div class="overflow-x-auto">

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

            @foreach ($teachers as $teacher)
                <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }}
                    class="hover:bg-slate-800/40 transition-all">
                    <td class="px-3 py-5 text-center whitespace-nowrap">

                        {{ __zero($teachers->firstItem() + $loop->iteration - 1) }}

                    </td>

                    {{-- PROFILE --}}
                    <td class="px-6 py-5 text-slate-400">

                        <a title="Charger le profil de l'enseignant {{ $teacher->getFullName() }}"
                            href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                            class="flex items-center gap-4 underline-offset-4 hover:underline hover:text-amber-600">

                            <img src="{{ $teacher->profil_photo_url() }}"
                                alt="Photo de profil de {{ $teacher->fullName() }}"
                                class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                            <div class="min-w-0">

                                <h3 class="font-medium ">

                                    {{ $teacher->getFullName() }}

                                </h3>

                                <p class="mt-1 text-sm text-slate-400 flex items-center gap-x-1.5">
                                    <x-lucide-mail class="w-3.5 h-3.5" />
                                    <span>
                                        {{ $teacher->user->email }}
                                    </span>

                                </p>
                                <p class="mt-1 text-sm text-slate-400 font-mono flex items-center gap-x-1.5">

                                    <x-lucide-phone class="w-3.5 h-3.5" />
                                    <span>
                                        {{ $teacher->user->contacts }}
                                    </span>

                                </p>

                            </div>

                        </a>
                        <span
                            class="px-3 rounded-full @if ($teacher->hasValidAccessForYear()) bg-emerald-500/10 text-emerald-400 @else  bg-red-500/10 text-red-400 animate-pulse @endif border border-slate-600 w-full flex text-xs py-1 mt-2 text-center items-center justify-center gap-x-1">
                            <span>Accès
                                {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}</span>
                            @if ($teacher->hasValidAccessForYear())
                                <span> accordé</span>
                            @else
                                <span> non accordé</span>
                            @endif
                        </span>

                    </td>

                    {{-- SUBJECT --}}
                    <td class="px-3 py-5 text-center whitespace-nowrap">

                        <div class="mt-1 font-medium flex gap-2 text-sm justify-center">
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
                            @foreach ($teacher_classes as $cl)
                                <span
                                    class="px-2 py-1 rounded-xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
                                    {{ $cl?->code ?? $cl->name }}
                                </span>
                            @endforeach
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
                            @if (!$teacher->user->credentials_sent)
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
                            @endif

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

                            {{-- Accorder / Retirer accès année --}}
                            @if (!$teacher->deleted_at)
                                <button
                                    title="{{ $teacher->hasValidAccessForYear() ? 'Retirer' : 'Accorder' }} l'accès à {{ $teacher->getFullName() }}"
                                    wire:click="{{ $teacher->hasValidAccessForYear() ? 'removeAccessForThisSchoolYear(' . $teacher->id . ')' : 'giveAccessForThisSchoolYear(' . $teacher->id . ')' }}"
                                    wire:loading.attr="disabled"
                                    wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->hasValidAccessForYear() ? 'bg-orange-600/50 hover:bg-orange-800/50 text-orange-400' : 'bg-emerald-600/50 hover:bg-emerald-800/50 text-emerald-400' }}">
                                    <span wire:loading.remove
                                        wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        @if ($teacher->hasValidAccessForYear())
                                            <x-lucide-user-lock class="w-3.5 h-3.5 shrink-0" />
                                            <span>Retirer accès</span>
                                        @else
                                            <x-lucide-key class="w-3.5 h-3.5 shrink-0" />
                                            <span>Accorder accès</span>
                                        @endif
                                    </span>
                                    <span wire:loading
                                        wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                        <span>En cours...</span>
                                    </span>
                                </button>
                            @endif

                            {{-- Corbeille / Restaurer --}}
                            <button
                                title="{{ $teacher->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }} {{ $teacher->getFullName() }}"
                                wire:click="{{ $teacher->deleted_at ? 'restoreTeacher(' . $teacher->id . ')' : 'deleteTeacher(' . $teacher->id . ')' }}"
                                wire:loading.attr="disabled"
                                wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->deleted_at ? 'bg-violet-600/50 hover:bg-violet-800/50 text-violet-400' : 'bg-rose-600/50 hover:bg-rose-800/50 text-rose-400' }}">
                                <span wire:loading.remove
                                    wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                    class="inline-flex items-center gap-1.5">
                                    @if ($teacher->deleted_at)
                                        <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                                        <span>Restaurer</span>
                                    @else
                                        <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                                        <span>Corbeille</span>
                                    @endif
                                </span>
                                <span wire:loading
                                    wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                    <span>En cours...</span>
                                </span>
                            </button>

                            {{-- Supprimer définitivement --}}
                            @if ($teacher->deleted_at)
                                <button title="Supprimer définitivement {{ $teacher->getFullName() }}"
                                    wire:click="forceDeleteTeacher({{ $teacher->id }})" wire:loading.attr="disabled"
                                    wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-red-600/50 hover:bg-red-800/50 text-red-400 transition-all whitespace-nowrap disabled:opacity-50">
                                    <span wire:loading.remove wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5 shrink-0" />
                                        <span>Suppr. déf.</span>
                                    </span>
                                    <span wire:loading wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                        <span>En cours...</span>
                                    </span>
                                </button>
                            @endif

                        </div>
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>

{{-- PAGINATION --}}
@if ($teachers->hasPages())
    <section class="py-6">
        <div class="border border-slate-800 bg-slate-900 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-slate-400">
                    Affichage {{ $teachers->firstItem() }} à {{ $teachers->lastItem() }} sur
                    {{ $teachers->total() }} enseignants
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    @if (!$teachers->onFirstPage())
                        <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                            class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                            Précédent
                        </button>
                    @endif

                    @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})"
                            class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $teachers->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                            {{ $page }}
                        </button>
                    @endforeach

                    @if ($teachers->hasMorePages())
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
