<div class="space-y-6 p-3">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800 bg-slate-900/80 overflow-hidden">

        {{-- COVER --}}
        <div class="h-40 bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 relative">

            <div class="absolute inset-0 bg-black/20"></div>

            <div class="absolute right-6 top-6">

                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-emerald-500/20 border border-emerald-500/30
                             text-emerald-300 text-sm font-semibold">

                    <x-lucide-check-circle class="w-4 h-4" />

                    Abonnement actif

                </span>

            </div>

        </div>

        {{-- PROFILE --}}
        <div class="relative px-5 sm:px-6 pb-6">

            <div class="-mt-16 flex flex-col xl:flex-row xl:items-end xl:justify-between gap-6">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-end">

                    {{-- AVATAR --}}
                    <div class="relative shrink-0">

                        <img src="https://i.pravatar.cc/300?img=12" class="w-32 h-32 rounded-3xl border-4 border-slate-900 object-cover shadow-2xl">

                        <div class="absolute -bottom-2 -right-2 w-11 h-11 rounded-2xl
                                    bg-emerald-500 border-4 border-slate-900
                                    flex items-center justify-center">

                            <x-lucide-shield-check class="w-5 h-5 text-white" />

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">

                                M. Arnaud HOUNGBEDJI

                            </h1>

                            <span
                                class="inline-flex items-center gap-2
                                         px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         border border-indigo-500/20
                                         text-indigo-300 text-xs font-semibold">

                                <x-lucide-crown class="w-4 h-4" />

                                Tenant Premium

                            </span>

                        </div>

                        <p class="mt-2 text-slate-400 text-lg">

                            Directeur Général — CEG Horizon Technique

                        </p>

                        <div class="mt-5 flex flex-wrap gap-3">

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-mail class="w-4 h-4 text-sky-400" />

                                <span class="text-sm">
                                    direction@horizon-school.com
                                </span>

                            </div>

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-phone class="w-4 h-4 text-emerald-400" />

                                <span class="text-sm">
                                    +229 01 97 00 00 00
                                </span>

                            </div>

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-map-pinned class="w-4 h-4 text-amber-400" />

                                <span class="text-sm">
                                    Cotonou, Bénin
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4">

        @foreach ([['Pack actuel', 'Premium+', 'badge-check', 'sky'], ['Jours restants', '184', 'calendar-clock', 'emerald'], ['Dernière connexion', 'Aujourd’hui', 'activity', 'amber'], ['État plateforme', 'Actif', 'shield-check', 'indigo']] as $card)
            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 relative overflow-hidden">

                <div class="absolute -right-5 -top-5 opacity-10">

                    @if ($card[2] === 'badge-check')
                        <x-lucide-badge-check class="w-28 h-28" />
                    @elseif($card[2] === 'calendar-clock')
                        <x-lucide-calendar-clock class="w-28 h-28" />
                    @elseif($card[2] === 'activity')
                        <x-lucide-activity class="w-28 h-28" />
                    @else
                        <x-lucide-shield-check class="w-28 h-28" />
                    @endif

                </div>

                <div class="relative">

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-sm text-slate-400">
                                {{ $card[0] }}
                            </p>

                            <h2 class="mt-3 text-3xl font-black">
                                {{ $card[1] }}
                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl
                                    bg-{{ $card[3] }}-500/10
                                    flex items-center justify-center">

                            @if ($card[2] === 'badge-check')
                                <x-lucide-badge-check class="w-7 h-7 text-sky-400" />
                            @elseif($card[2] === 'calendar-clock')
                                <x-lucide-calendar-clock class="w-7 h-7 text-emerald-400" />
                            @elseif($card[2] === 'activity')
                                <x-lucide-activity class="w-7 h-7 text-amber-400" />
                            @else
                                <x-lucide-shield-check class="w-7 h-7 text-indigo-400" />
                            @endif

                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </section>

    {{-- ACTIONS --}}
    <section class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">
        <div class="flex flex-wrap gap-3">

            <button
                class="h-12 px-5 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-400
                                   text-white font-semibold
                                   flex items-center gap-2 transition-all">

                <x-lucide-badge-plus class="w-5 h-5" />

                Étendre abonnement

            </button>

            <button
                class="h-12 px-5 rounded-2xl
                                   bg-amber-500/10 hover:bg-amber-500/20
                                   text-amber-400 font-semibold
                                   flex items-center gap-2 border border-amber-500/20">

                <x-lucide-bell-ring class="w-5 h-5" />

                Notifier

            </button>

            <button
                class="h-12 px-5 rounded-2xl
                                   bg-rose-500/10 hover:bg-rose-500/20
                                   text-rose-400 font-semibold
                                   flex items-center gap-2 border border-rose-500/20">

                <x-lucide-ban class="w-5 h-5" />

                Bloquer accès

            </button>

        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- INFOS + STATS --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 2xl:grid-cols-3 gap-6">

        {{-- DETAILS --}}
        <div class="2xl:col-span-2 rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h2 class="text-xl font-bold">
                        Informations du tenant
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Détails du souscripteur et de l’établissement
                    </p>

                </div>

                <button class="h-11 px-4 rounded-xl
                               border border-slate-700
                               bg-slate-800 hover:bg-slate-700
                               text-sm flex items-center gap-2">

                    <x-lucide-pencil class="w-4 h-4" />

                    Modifier

                </button>

            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                @foreach ([['Nom établissement', 'CEG Horizon Technique', 'school'], ['Type enseignement', 'Technique', 'graduation-cap'], ['Pack abonnement', 'Premium+', 'badge-check'], ['Date début', '12 Janvier 2026', 'calendar'], ['Date expiration', '18 Novembre 2026', 'calendar-range'], ['Base de données', '3.8 GB utilisées', 'database'], ['Nom domaine', 'horizon.educconnect.app', 'globe'], ['Statut', 'Plateforme active', 'shield-check']] as $info)
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-2xl
                                        bg-indigo-500/10
                                        flex items-center justify-center">

                                @switch($info[2])
                                    @case('school')
                                        <x-lucide-school class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('graduation-cap')
                                        <x-lucide-graduation-cap class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('badge-check')
                                        <x-lucide-badge-check class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('calendar')
                                        <x-lucide-calendar class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('calendar-range')
                                        <x-lucide-calendar-range class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('database')
                                        <x-lucide-database class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('globe')
                                        <x-lucide-globe class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @default
                                        <x-lucide-shield-check class="w-5 h-5 text-indigo-400" />
                                @endswitch

                            </div>

                            <div>

                                <p class="text-xs text-slate-500">
                                    {{ $info[0] }}
                                </p>

                                <p class="mt-1 font-semibold">
                                    {{ $info[1] }}
                                </p>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- GLOBAL STATS --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6">

            <h2 class="text-xl font-bold">
                Statistiques établissement
            </h2>

            <div class="mt-6 space-y-4">

                @foreach ([['Apprenants', '2 842', 'users'], ['Enseignants', '126', 'briefcase'], ['Classes', '38', 'layout-grid'], ['Promotions', '7', 'layers-3'], ['Filières', '5', 'network'], ['Séries', '9', 'git-branch']] as $stat)
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="w-11 h-11 rounded-2xl
                                            bg-sky-500/10
                                            flex items-center justify-center">

                                    @switch($stat[2])
                                        @case('users')
                                            <x-lucide-users class="w-5 h-5 text-sky-400" />
                                        @break

                                        @case('briefcase')
                                            <x-lucide-briefcase class="w-5 h-5 text-sky-400" />
                                        @break

                                        @case('layout-grid')
                                            <x-lucide-layout-grid class="w-5 h-5 text-sky-400" />
                                        @break

                                        @case('layers-3')
                                            <x-lucide-layers-3 class="w-5 h-5 text-sky-400" />
                                        @break

                                        @case('network')
                                            <x-lucide-network class="w-5 h-5 text-sky-400" />
                                        @break

                                        @default
                                            <x-lucide-git-branch class="w-5 h-5 text-sky-400" />
                                    @endswitch

                                </div>

                                <div>

                                    <p class="text-sm text-slate-400">
                                        {{ $stat[0] }}
                                    </p>

                                    <h3 class="text-2xl font-black mt-1">
                                        {{ $stat[1] }}
                                    </h3>

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </section>

</div>

