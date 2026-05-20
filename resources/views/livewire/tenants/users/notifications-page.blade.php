<div class="w-full overflow-x-hidden p-2">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-5 sm:p-6 lg:p-8">

                <div class="flex flex-col
                            2xl:flex-row
                            2xl:items-center
                            2xl:justify-between
                            gap-6">

                    {{-- LEFT --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-2xl sm:text-3xl font-bold">

                                Centre de Notifications

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Communication Temps Réel

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Gérez les notifications,
                            alertes, annonces et communications
                            envoyées aux élèves, parents,
                            enseignants et personnels.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-emerald-500 hover:bg-emerald-600">

                            Nouvelle Notification

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600">

                            Historique

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-2
                        lg:grid-cols-4
                        2xl:grid-cols-6
                        gap-4">

                @foreach ([['Notifications', '1 284', 'text-indigo-400'], ['Emails', '843', 'text-sky-400'], ['SMS', '212', 'text-amber-400'], ['WhatsApp', '694', 'text-emerald-400'], ['Échecs', '18', 'text-rose-400'], ['Programmées', '46', 'text-violet-400']] as $kpi)
                    <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                        <p class="text-sm text-slate-400">

                            {{ $kpi[0] }}

                        </p>

                        <h2 class="mt-3 text-3xl font-bold {{ $kpi[2] }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        <section class="mb-3">
            <div class="space-y-6">

                {{-- ===================================================== --}}
                {{-- QUICK SEND --}}
                {{-- ===================================================== --}}
                <div class="rounded-3xl bg-gradient-to-br from-indigo-500/20 to-slate-900 border border-indigo-500/20 p-5">
                    <div class="flex  items-center">
                        <div class="w-1/3">
                            <h2 class="text-lg font-semibold">
                                Envoi Rapide
                            </h2>

                            <p class="mt-2 text-sm text-slate-300">
                                Envoyer rapidement une notification.
                            </p>
                        </div>

                        <div class="w-2/3">
                            <input type="email" placeholder="email destinaire" class="w-full rounded-2xl bg-slate-900/70 border border-slate-800 px-4 py-3.5">
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="w-full">
                            <div class="col-span-2">
                                <input type="text" placeholder="Objet..." class="w-full rounded-2xl bg-slate-900/70 border border-slate-800 px-4 py-3.5">
                            </div>
                        </div>

                        <textarea rows="5" placeholder="Votre message..." class="w-full rounded-2xl bg-slate-900/70 border border-slate-800 p-4"></textarea>

                        <button class="w-full p-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600">
                            Envoyer Notification
                        </button>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- FILTERS --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-3xl
                        bg-slate-900
                        border border-slate-800
                        p-5">

                <div class="grid
                            grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-6
                            gap-4">

                    {{-- SEARCH --}}
                    <input type="text" placeholder="Rechercher une notification..."
                        class="h-12 rounded-2xl
                                  bg-slate-950
                                  border border-slate-800
                                  px-4">

                    {{-- TYPE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Tous Types</option>
                        <option>Email</option>
                        <option>SMS</option>
                        <option>WhatsApp</option>
                        <option>Push</option>

                    </select>

                    {{-- STATUS --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Tous États</option>
                        <option>Envoyée</option>
                        <option>Échec</option>
                        <option>Programmée</option>
                        <option>Brouillon</option>

                    </select>

                    {{-- TARGET --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Toutes Cibles</option>
                        <option>Parents</option>
                        <option>Élèves</option>
                        <option>Enseignants</option>
                        <option>Personnel</option>

                    </select>

                    {{-- DATE --}}
                    <input type="date" class="h-12 rounded-2xl
                                  bg-slate-950
                                  border border-slate-800
                                  px-4">

                    {{-- BTN --}}
                    <button class="h-12 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600">

                        Filtrer

                    </button>

                </div>

            </div>

        </section>
        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="">

                <div class="space-y-6 min-w-0">

                    {{-- ===================================================== --}}
                    {{-- NOTIFICATIONS TABLE --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="p-5 border-b border-slate-800">

                            <div
                                class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-xl font-semibold">

                                        Notifications Récentes

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Liste complète des notifications
                                        envoyées ou programmées.

                                    </p>

                                </div>

                                {{-- BULK ACTIONS --}}
                                <div class="flex flex-wrap gap-2">

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-slate-800 hover:bg-slate-700">

                                        Marquer comme lues

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-rose-500/10
                                                   text-rose-400">

                                        Supprimer

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="w-full truncate">

                                <thead class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left">
                                            <input type="checkbox">
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Objet
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Type
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Destinataires
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            État
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Date
                                        </th>

                                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 12) as $notif)
                                        <tr class="hover:bg-slate-800/40">

                                            <td class="px-6 py-5">

                                                <input type="checkbox">

                                            </td>

                                            <td class="px-6 py-5">

                                                <div>

                                                    <h3 class="font-medium">

                                                        Réunion des Parents

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400">

                                                        Information générale
                                                        pour les parents d’élèves.

                                                    </p>

                                                </div>

                                            </td>

                                            <td class="px-4 py-5 text-center">

                                                <span
                                                    class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-xs">

                                                    WhatsApp

                                                </span>

                                            </td>

                                            <td class="px-4 py-5 text-center">

                                                684

                                            </td>

                                            <td class="px-4 py-5 text-center">

                                                <span class="px-3 py-1 rounded-full
                                                         bg-indigo-500/10
                                                         text-indigo-400 text-xs">

                                                    Envoyée

                                                </span>

                                            </td>

                                            <td class="px-4 py-5 text-center">

                                                19 Mai 2026

                                            </td>

                                            <td class="px-6 py-5">

                                                <div class="flex justify-end gap-2">

                                                    <button
                                                        class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400">

                                                        Voir

                                                    </button>

                                                    <button
                                                        class="h-10 px-4 rounded-xl
                                                               bg-emerald-500/10
                                                               text-emerald-400">

                                                        Relancer

                                                    </button>

                                                    <button
                                                        class="h-10 px-4 rounded-xl
                                                               bg-rose-500/10
                                                               text-rose-400">

                                                        Supprimer

                                                    </button>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- DELIVERY REPORT --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                p-5 sm:p-6">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-xl font-semibold">

                                    Rapport de Livraison

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Analyse des taux de diffusion.

                                </p>

                            </div>

                            <button class="h-10 px-4 rounded-xl
                                           bg-slate-800 hover:bg-slate-700">

                                Exporter

                            </button>

                        </div>

                        <div class="mt-6 grid
                                    grid-cols-1
                                    md:grid-cols-2
                                    xl:grid-cols-4
                                    gap-4">

                            @foreach ([['Emails Livrés', '92%', 'bg-indigo-500'], ['SMS Livrés', '87%', 'bg-amber-500'], ['WhatsApp', '96%', 'bg-emerald-500'], ['Push', '99%', 'bg-sky-500']] as $report)
                                <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                    <div class="flex justify-between">

                                        <span class="text-sm text-slate-400">

                                            {{ $report[0] }}

                                        </span>

                                        <span class="font-semibold">

                                            {{ $report[1] }}

                                        </span>

                                    </div>

                                    <div class="mt-3 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                        <div class="h-full rounded-full {{ $report[2] }}" style="width: {{ $report[1] }}">
                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>
            </div>

        </section>

    </div>

</div>

