<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">
        <section class="mb-6">

            <div class="relative overflow-hidden  rounded-[32px]  border border-slate-800 bg-slate-900">
                <div class="absolute inset-0  bg-gradient-to-br from-indigo-500/10 via-slate-900 to-slate-900">
                </div>
                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-bold">
                                    Dashboard Promotions
                                </h1>

                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">
                                    Gestion Académique
                                </span>

                            </div>

                            <p class="mt-3 text-slate-400 max-w-3xl">

                                Vue globale des promotions,
                                performances académiques,
                                statistiques des apprenants
                                et gestion des classes.

                            </p>

                            {{-- BADGES --}}
                            <div class="mt-6 flex flex-wrap gap-3">

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    {{ __zero(tenancy()->tenant?->promotionsCount()) }} Promotions

                                </div>

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    124 Classes

                                </div>

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    4 812 Apprenants

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                                class="px-5 py-3 flex justify-center items-center rounded-2xl bg-blue-500 hover:bg-blue-800 transition">
                                Nouvelle Promotion
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid grid-cols-2 lg:grid-cols-4 2xl:grid-cols-6 gap-4">

                @foreach ([['Promotions', __zero(tenancy()->tenant?->promotionsCount()), 'text-indigo-400'], ['Classes', '124', 'text-sky-400'], ['Apprenants', '4 812', 'text-emerald-400'], ['Moyenne Générale', '12.84', 'text-amber-400'], ['Taux Réussite', '78%', 'text-violet-400'], ['Filières', '14', 'text-rose-400']] as $kpi)
                    <div class="rounded-3xl bg-slate-900 border border-slate-800 p-5">
                        <p class="text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>
                        <h2 class="mt-3 text-2xl font-bold {{ $kpi[1] ? $kpi[2] : '' }}">
                            {{ $kpi[1] }}
                        </h2>

                    </div>
                @endforeach

            </div>

        </section>
        <section>

            <div
                class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <div class="p-5 sm:p-6 border-b border-slate-800">

                    <div
                        class="flex flex-col
                                2xl:flex-row
                                2xl:items-center
                                2xl:justify-between
                                gap-5">

                        {{-- TITLE --}}
                        <div>

                            <h2 class="text-xl font-semibold">

                                Liste des Promotions

                            </h2>

                            <p class="mt-1 text-sm text-slate-400">

                                Analyse détaillée des promotions,
                                performances et statistiques.

                            </p>

                        </div>

                        {{-- FILTERS --}}
                        <div
                            class="flex flex-col
                                    sm:flex-row
                                    flex-wrap
                                    gap-3
                                    w-full
                                    2xl:w-auto">

                            {{-- SEARCH --}}
                            <div class="relative w-full sm:w-auto">

                                <input type="text" placeholder="Rechercher une promotion..."
                                    class="h-12
                                              w-full sm:w-[260px]
                                              rounded-2xl
                                              bg-slate-950
                                              border border-slate-800
                                              pl-4 pr-4
                                              text-sm">

                            </div>

                            {{-- FILIERE --}}
                            <select
                                class="h-12
                                           rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4
                                           text-sm">

                                <option>
                                    Toutes les Filières
                                </option>

                                <option>
                                    F1
                                </option>

                                <option>
                                    F2
                                </option>

                                <option>
                                    F3
                                </option>

                                <option>
                                    F4
                                </option>

                            </select>

                            {{-- SERIE --}}
                            <select
                                class="h-12
                                           rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4
                                           text-sm">

                                <option>
                                    Toutes les Séries
                                </option>

                                <option>
                                    Série A
                                </option>

                                <option>
                                    Série C
                                </option>

                                <option>
                                    Série D
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- TABLE --}}
                {{-- ===================================================== --}}
                <div class="overflow-x-auto">

                    <table class="min-w-[2100px] w-full">

                        {{-- HEAD --}}
                        <thead class="bg-slate-950
                                     border-b border-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    N°
                                </th>
                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Promotion
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Nb Classes
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Effectif
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Meilleur Élève
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Faible
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Jeune
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Âgé
                                </th>

                                <th class="px-6 py-4 text-right text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        {{-- BODY --}}
                        <tbody class="divide-y divide-slate-800 text-slate-400">

                            @foreach ($promotions as $promo)
                                <tr class="hover:bg-slate-800/40 transition-colors duration-200">

                                    <td class="px-6 py-5 truncate">{{ $loop->iteration }}</td>

                                    <td class="px-6 py-5">

                                        <a wire:navigate
                                            href="{{ route('tenant.promotion.profil', ['promotion_slug' => $promo->slug]) }}"
                                            class="hover:underline underline-offset-2">

                                            <h3 class="font-semibold text-base">

                                                {{ $promo->name }}

                                            </h3>

                                            <p class="mt-1 text-sm text-slate-400">
                                                {{ $promo->code }}
                                            </p>

                                        </a>

                                    </td>

                                    {{-- CLASSES --}}
                                    <td class="px-4 py-5 text-center">

                                        <span class="font-semibold">

                                            18

                                        </span>

                                    </td>

                                    {{-- EFFECTIF --}}
                                    <td class="px-4 py-5 text-center">

                                        <span
                                            class="font-semibold
                                                 text-indigo-400">

                                            684

                                        </span>

                                    </td>

                                    {{-- BEST --}}
                                    <td class="px-6 py-5">

                                        <div>

                                            <h3 class="font-medium">

                                                KOUASSI Sarah

                                            </h3>

                                            <p class="text-sm text-emerald-400">

                                                (18.92)
                                            </p>

                                        </div>

                                    </td>

                                    {{-- WORST --}}
                                    <td class="px-6 py-5">

                                        <div>

                                            <h3 class="font-medium">

                                                HOUNKPE David

                                            </h3>

                                            <p class="text-sm text-rose-400">

                                                (03.42)

                                            </p>

                                        </div>

                                    </td>

                                    {{-- YOUNGEST --}}
                                    <td class="px-6 py-5">

                                        <div>

                                            <h3 class="font-medium">

                                                ADJOVI Esther

                                            </h3>

                                            <p class="text-sm text-slate-400">

                                                10 ans

                                            </p>

                                        </div>

                                    </td>

                                    {{-- OLDEST --}}
                                    <td class="px-6 py-5">

                                        <div>

                                            <h3 class="font-medium">

                                                AKAKPO Jonas

                                            </h3>

                                            <p class="text-sm text-slate-400">

                                                19 ans

                                            </p>

                                        </div>

                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-5">

                                        <div class="flex justify-end gap-2">

                                            {{-- PROFIL --}}
                                            <a wire:navigate
                                                href="{{ route('tenant.promotion.profil', ['promotion_slug' => $promo->slug]) }}"
                                                class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/30 transition-all text-sm inline-block text-center">
                                                Profil
                                            </a>

                                            {{-- CLOSE --}}
                                            <button
                                                class="h-10 px-4
                                                       rounded-xl
                                                       bg-amber-500/10
                                                       text-amber-400
                                                       hover:bg-amber-500/20
                                                       transition">

                                                Fermer

                                            </button>

                                            {{-- DELETE --}}
                                            <button
                                                class="h-10 px-4
                                                       rounded-xl
                                                       bg-rose-500/10
                                                       text-rose-400
                                                       hover:bg-rose-500/20
                                                       transition">

                                                Supprimer

                                            </button>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- ===================================================== --}}
                {{-- FOOTER --}}
                {{-- ===================================================== --}}
                <div class="p-5 border-t border-slate-800">

                    <div
                        class="flex flex-col
                                lg:flex-row
                                lg:items-center
                                lg:justify-between
                                gap-4">

                        {{-- INFO --}}
                        <div class="text-sm text-slate-400">

                            Affichage de
                            <span class="text-slate-200 font-medium">
                                7
                            </span>
                            promotions enregistrées.

                        </div>

                        {{-- EXPORTS --}}
                        <div class="flex flex-wrap gap-3">

                            <button
                                class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600
                                           transition">

                                Export Excel

                            </button>

                            <button
                                class="h-11 px-5 rounded-2xl
                                           bg-rose-500 hover:bg-rose-600
                                           transition">

                                Export PDF

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

