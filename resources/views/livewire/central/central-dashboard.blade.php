<div class="space-y-6 p-3">

    {{-- ===================================================== --}}
    {{-- PAGE HEADER --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col xl:flex-row
                   xl:items-center xl:justify-between
                   gap-6">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-indigo-500/10 border border-indigo-500/20
                           text-indigo-300 text-xs font-medium">

                    <x-lucide-shield-check class="w-4 h-4" />

                    Administration Centrale
                    <span>{{ session('school_year_selected') }}</span>

                </div>

                <h1 class="mt-4 text-2xl sm:text-4xl
                           font-black tracking-tight">

                    Dashboard Central

                </h1>

                <p class="mt-3 text-sm sm:text-base
                           text-slate-400 max-w-3xl">

                    Contrôle global des établissements, abonnements,
                    demandes d'inscription, statistiques et supervision
                    des accès de la plateforme.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       gap-3 w-full xl:w-auto">

                <button
                    class="h-12 px-5 rounded-2xl
                           bg-indigo-500 hover:bg-indigo-400
                           text-white font-semibold
                           flex items-center justify-center gap-2
                           transition-all duration-200">

                    <x-lucide-plus class="w-5 h-5" />

                    Nouvelle annonce

                </button>

                <button
                    class="h-12 px-5 rounded-2xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           text-slate-200 font-medium
                           flex items-center justify-center gap-2">

                    <x-lucide-download class="w-5 h-5" />

                    Exporter

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1
               sm:grid-cols-2
               2xl:grid-cols-4 gap-4">

        @foreach ([['Écoles actives', '128', '+12%', 'building-2', 'emerald'], ['Demandes en attente', '14', '+4', 'clipboard-list', 'amber'], ['Abonnements actifs', '102', '+8%', 'badge-check', 'sky'], ['Écoles suspendues', '07', '-2', 'shield-alert', 'rose']] as $card)
            <div class="rounded-3xl border border-slate-800
                   bg-slate-900/80 p-5 overflow-hidden relative">

                {{-- BG ICON --}}
                <div class="absolute -right-4 -top-4
                       opacity-10">

                    <x-dynamic-component :component="'lucide-' . $card[3]" class="w-28 h-28" />

                </div>

                <div class="relative">

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-sm text-slate-400 truncate">

                                {{ $card[0] }}

                            </p>

                            <h2 class="mt-3 text-4xl
                                   font-black">

                                {{ $card[1] }}

                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl
                               bg-{{ $card[4] }}-500/10
                               flex items-center justify-center
                               shrink-0">

                            <x-dynamic-component :component="'lucide-' . $card[3]" class="w-7 h-7 text-{{ $card[4] }}-400" />

                        </div>

                    </div>

                    <div class="mt-5 inline-flex items-center gap-2
                           text-sm text-{{ $card[4] }}-400">

                        <x-lucide-trending-up class="w-4 h-4" />

                        {{ $card[2] }}

                        <span class="text-slate-500">
                            ce mois-ci
                        </span>

                    </div>

                </div>

            </div>
        @endforeach

    </section>

    {{-- ===================================================== --}}
    {{-- STATS + GRAPH --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1
               2xl:grid-cols-3 gap-6">

        {{-- GRAPH --}}
        <div class="2xl:col-span-2
                   rounded-3xl border border-slate-800
                   bg-slate-900/80 p-5 sm:p-6">

            <div class="flex flex-col lg:flex-row
                       lg:items-center lg:justify-between
                       gap-4">

                <div>

                    <h2 class="text-xl font-bold">
                        Évolution des inscriptions
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Suivi des écoles enregistrées sur la plateforme
                    </p>

                </div>

                <div class="flex flex-wrap gap-3">

                    <select class="h-11 px-4 rounded-xl
                               bg-slate-800 border border-slate-700
                               text-sm">

                        <option>12 derniers mois</option>
                        <option>6 derniers mois</option>

                    </select>

                    <button class="h-11 px-4 rounded-xl
                               border border-slate-700
                               bg-slate-800 hover:bg-slate-700
                               text-sm">

                        Actualiser

                    </button>

                </div>

            </div>

            {{-- GRAPH --}}
            <div class="mt-6 h-[350px]
                       rounded-2xl border border-dashed
                       border-slate-700
                       bg-slate-950/50
                       flex items-center justify-center">

                <div class="text-center">

                    <x-lucide-line-chart class="w-16 h-16 mx-auto text-slate-600" />

                    <p class="mt-4 text-slate-400">
                        Graphique des inscriptions
                    </p>

                </div>

            </div>

        </div>

        {{-- QUICK STATS --}}
        <div class="rounded-3xl border border-slate-800
                   bg-slate-900/80 p-5 sm:p-6">

            <h2 class="text-xl font-bold">
                Répartition des écoles
            </h2>

            <div class="mt-6 space-y-6">

                @foreach ([['Technique', '72%', 'emerald'], ['Général', '21%', 'sky'], ['Professionnel', '7%', 'amber']] as $item)
                    <div>

                        <div class="flex items-center
                               justify-between mb-2">

                            <span class="text-sm">
                                {{ $item[0] }}
                            </span>

                            <span class="text-sm
                                   text-{{ $item[2] }}-400">

                                {{ $item[1] }}

                            </span>

                        </div>

                        <div class="h-2 rounded-full
                               bg-slate-800 overflow-hidden">

                            <div class="h-full rounded-full
                                   bg-{{ $item[2] }}-500" style="width: {{ $item[1] }}">

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

            {{-- MINI STATS --}}
            <div class="mt-8 grid grid-cols-2 gap-4">

                @foreach ([['Tenants', '314'], ['Utilisateurs', '12k'], ['Connexions', '4.8k'], ['Incidents', '03']] as $mini)
                    <div class="rounded-2xl
                           border border-slate-800
                           bg-slate-950/40 p-4">

                        <p class="text-xs text-slate-500">
                            {{ $mini[0] }}
                        </p>

                        <p class="mt-2 text-2xl font-black">
                            {{ $mini[1] }}
                        </p>

                    </div>
                @endforeach

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- DEMANDES --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 overflow-hidden">

        {{-- HEADER --}}
        <div class="p-5 sm:p-6 border-b border-slate-800">

            <div class="flex flex-col xl:flex-row
                       xl:items-center xl:justify-between
                       gap-4">

                <div>

                    <h2 class="text-xl font-bold">
                        Demandes d'inscription
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Validation des établissements
                    </p>

                </div>

                <div class="flex flex-col sm:flex-row gap-3">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <x-lucide-search class="w-4 h-4
                                   absolute left-4 top-1/2
                                   -translate-y-1/2
                                   text-slate-500" />

                        <input type="text" placeholder="Rechercher..."
                            class="w-full sm:w-72 h-11
                                   rounded-xl
                                   bg-slate-800
                                   border border-slate-700
                                   pl-11 pr-4 text-sm">

                    </div>

                    <select class="h-11 px-4 rounded-xl
                               bg-slate-800
                               border border-slate-700
                               text-sm">

                        <option>Tous les statuts</option>
                        <option>En attente</option>
                        <option>Validées</option>

                    </select>

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-[1200px] w-full">

                <thead class="bg-slate-950">

                    <tr>

                        @foreach (['École', 'Directeur', 'Email', 'Pack', 'Date', 'Statut', 'Actions'] as $head)
                            <th class="px-6 py-4 text-left
                                   text-sm font-semibold
                                   text-slate-400
                                   border-b border-slate-800">

                                {{ $head }}

                            </th>
                        @endforeach

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-800">

                    @foreach (range(1, 8) as $item)
                        <tr class="hover:bg-slate-800/40
                               transition-colors duration-200">

                            {{-- SCHOOL --}}
                            <td class="px-6 py-5 whitespace-nowrap">

                                <div class="flex items-center gap-3">

                                    <div class="w-12 h-12 rounded-2xl
                                           bg-indigo-500/10
                                           flex items-center justify-center">

                                        <x-lucide-school class="w-6 h-6 text-indigo-400" />

                                    </div>

                                    <div>

                                        <p class="font-semibold">
                                            Lycée Technique {{ $item }}
                                        </p>

                                        <p class="text-xs text-slate-500">

                                            Cotonou

                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- DIRECTOR --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                M. Houngbedji
                            </td>

                            {{-- EMAIL --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                directeur{{ $item }}@mail.com
                            </td>

                            {{-- PACK --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                Premium
                            </td>

                            {{-- DATE --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                20 Mai 2026
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-5">

                                <span
                                    class="inline-flex items-center
                                       px-3 py-1 rounded-full
                                       bg-amber-500/10
                                       text-amber-400 text-xs font-medium">

                                    En attente

                                </span>

                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-2">

                                    <button
                                        class="h-10 px-4 rounded-xl
                                           bg-emerald-500/10
                                           text-emerald-400
                                           hover:bg-emerald-500/20
                                           transition-colors">

                                        Valider

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                           bg-rose-500/10
                                           text-rose-400
                                           hover:bg-rose-500/20
                                           transition-colors">

                                        Refuser

                                    </button>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- ABONNEMENTS + ECOLES --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1
                           2xl:grid-cols-2 gap-6">

        {{-- ABONNEMENTS --}}
        <div class="rounded-3xl border border-slate-800
                               bg-slate-900/80 overflow-hidden">

            <div class="p-6 border-b border-slate-800">

                <h2 class="text-xl font-bold">
                    Abonnements
                </h2>

            </div>

            <div class="divide-y divide-slate-800">

                @foreach (range(1, 5) as $item)
                    <div class="p-5 flex flex-col lg:flex-row
                                       lg:items-center lg:justify-between
                                       gap-4">

                        <div class="min-w-0">

                            <div class="flex items-center gap-3">

                                <div class="w-12 h-12 rounded-2xl
                                                   bg-sky-500/10
                                                   flex items-center justify-center">

                                    <x-lucide-credit-card class="w-6 h-6 text-sky-400" />

                                </div>

                                <div class="min-w-0">

                                    <p class="font-semibold truncate">
                                        Collège Horizon
                                    </p>

                                    <p class="text-sm text-slate-400">
                                        Pack Premium
                                    </p>

                                </div>

                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4
                                               text-sm">

                                <div>
                                    <p class="text-slate-500">
                                        Début
                                    </p>

                                    <p>
                                        10 Jan 2026
                                    </p>
                                </div>

                                <div>
                                    <p class="text-slate-500">
                                        Restants
                                    </p>

                                    <p class="text-emerald-400">
                                        98 jours
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="flex flex-wrap gap-2">

                            <button class="h-10 px-4 rounded-xl
                                               bg-amber-500/10
                                               text-amber-400">

                                Rappel

                            </button>

                            <button class="h-10 px-4 rounded-xl
                                               bg-indigo-500/10
                                               text-indigo-400">

                                Étendre

                            </button>

                            <button class="h-10 px-4 rounded-xl
                                               bg-rose-500/10
                                               text-rose-400">

                                Suspendre

                            </button>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- ECOLES --}}
        <div class="rounded-3xl border border-slate-800
                               bg-slate-900/80 overflow-hidden">

            <div class="p-6 border-b border-slate-800">

                <h2 class="text-xl font-bold">
                    Écoles enregistrées
                </h2>

            </div>

            <div class="divide-y divide-slate-800">

                @foreach (range(1, 5) as $item)
                    <div class="p-5">

                        <div class="flex flex-col sm:flex-row
                                           sm:items-start
                                           sm:justify-between gap-4">

                            <div>

                                <h3 class="font-semibold">
                                    CEG Akpakpa {{ $item }}
                                </h3>

                                <p class="text-sm text-slate-400 mt-1">
                                    Enseignement Technique
                                </p>

                            </div>

                            <span
                                class="inline-flex items-center
                                               px-3 py-1 rounded-full
                                               bg-emerald-500/10
                                               text-emerald-400 text-xs">

                                Active

                            </span>

                        </div>

                        <div class="mt-5 grid grid-cols-2
                                           sm:grid-cols-3 gap-4">

                            @foreach ([['Classes', '24'], ['Promotions', '6'], ['Enseignants', '48'], ['Parents', '742'], ['Séries', '4'], ['Filières', '3']] as $stat)
                                <div class="rounded-2xl bg-slate-800/60
                                               border border-slate-700 p-3">

                                    <p class="text-xs text-slate-500">
                                        {{ $stat[0] }}
                                    </p>

                                    <p class="mt-1 font-bold">
                                        {{ $stat[1] }}
                                    </p>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </section>

</div>
