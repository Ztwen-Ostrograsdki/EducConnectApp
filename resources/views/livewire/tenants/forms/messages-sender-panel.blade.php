<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
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

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-2xl sm:text-3xl font-bold">

                                Publication d’Information

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Communication Administrative

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Publier des notes de service,
                            annonces ou informations importantes
                            aux élèves, parents, enseignants
                            et personnels administratifs.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-slate-800 hover:bg-slate-700
                                       transition-all">

                            Brouillons

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600
                                       transition-all">

                            Historique

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_420px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- ===================================================== --}}
                    {{-- FORM --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="p-5 sm:p-6
                                    border-b border-slate-800">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h2 class="text-xl font-semibold">

                                        Nouvelle Publication

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Rédigez et envoyez
                                        une note de service.

                                    </p>

                                </div>

                                <div class="hidden lg:flex items-center gap-2">

                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                                    <span class="text-sm text-slate-400">

                                        Sauvegarde automatique

                                    </span>

                                </div>

                            </div>

                        </div>

                        {{-- BODY --}}
                        <div class="p-4 sm:p-6 lg:p-8 space-y-6">

                            {{-- ===================================================== --}}
                            {{-- OBJECT --}}
                            {{-- ===================================================== --}}
                            <div>

                                <label class="block
                                             text-sm font-medium
                                             text-slate-300 mb-2">

                                    Objet de la publication

                                </label>

                                <input type="text"
                                       placeholder="Ex : Réunion des parents d’élèves"
                                       class="w-full
                                              h-12
                                              rounded-2xl
                                              bg-slate-950
                                              border border-slate-800
                                              px-4
                                              text-slate-100
                                              placeholder:text-slate-500
                                              focus:outline-none
                                              focus:ring-2
                                              focus:ring-indigo-500">
                            </div>

                            {{-- ===================================================== --}}
                            {{-- TARGETS --}}
                            {{-- ===================================================== --}}
                            <div>

                                <div class="flex items-center justify-between mb-3">

                                    <label class="text-sm font-medium text-slate-300">

                                        Destinataires

                                    </label>

                                    <button class="text-xs text-indigo-400">

                                        Tout sélectionner

                                    </button>

                                </div>

                                <div class="grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-3">

                                    @foreach([
                                        ['Élèves','386'],
                                        ['Parents','244'],
                                        ['Enseignants','42'],
                                        ['Personnel','18']
                                    ] as $target)

                                    <label class="rounded-2xl
                                                  bg-slate-950
                                                  border border-slate-800
                                                  p-4
                                                  flex items-center
                                                  gap-4
                                                  cursor-pointer
                                                  hover:border-indigo-500/40
                                                  transition-all">

                                        <input type="checkbox"
                                               class="w-5 h-5 rounded
                                                      bg-slate-800
                                                      border-slate-700
                                                      text-indigo-500">

                                        <div>

                                            <p class="font-medium">

                                                {{ $target[0] }}

                                            </p>

                                            <p class="text-xs text-slate-400">

                                                {{ $target[1] }} utilisateurs

                                            </p>

                                        </div>

                                    </label>

                                    @endforeach

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- FILTERS --}}
                            {{-- ===================================================== --}}
                            <div>

                                <h3 class="text-sm font-medium
                                           text-slate-300 mb-3">

                                    Filtres Avancés

                                </h3>

                                <div class="grid
                                            grid-cols-1
                                            md:grid-cols-2
                                            xl:grid-cols-4
                                            gap-3">

                                    <select class="h-11 rounded-2xl
                                                   bg-slate-950
                                                   border border-slate-800
                                                   px-4">

                                        <option>Toutes les classes</option>
                                        <option>Terminale F2-1</option>

                                    </select>

                                    <select class="h-11 rounded-2xl
                                                   bg-slate-950
                                                   border border-slate-800
                                                   px-4">

                                        <option>Toutes les filières</option>

                                    </select>

                                    <select class="h-11 rounded-2xl
                                                   bg-slate-950
                                                   border border-slate-800
                                                   px-4">

                                        <option>Toutes les séries</option>

                                    </select>

                                    <select class="h-11 rounded-2xl
                                                   bg-slate-950
                                                   border border-slate-800
                                                   px-4">

                                        <option>Priorité normale</option>
                                        <option>Urgent</option>
                                        <option>Critique</option>

                                    </select>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- ADD SPECIFIC USERS --}}
                            {{-- ===================================================== --}}
                            <div>

                                <div class="flex items-center justify-between mb-3">

                                    <h3 class="text-sm font-medium text-slate-300">

                                        Personnes Spécifiques

                                    </h3>

                                    <button class="text-sm text-indigo-400">

                                        + Ajouter

                                    </button>

                                </div>

                                <div class="rounded-3xl
                                            bg-slate-950
                                            border border-slate-800
                                            p-4">

                                    <div class="flex flex-wrap gap-3">

                                        @foreach([
                                            'M. AGBODJI',
                                            'Mme HOUNKPATI',
                                            'KOUASSI Marc',
                                            'Parent : AHOUEFA'
                                        ] as $person)

                                        <div class="h-11 px-4 rounded-2xl
                                                    bg-slate-800
                                                    flex items-center gap-3">

                                            <span class="text-sm">

                                                {{ $person }}

                                            </span>

                                            <button class="text-rose-400">

                                                ×

                                            </button>

                                        </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- WYSIWYG --}}
                            {{-- ===================================================== --}}
                            <div>

                                <div class="flex items-center justify-between mb-3">

                                    <label class="text-sm font-medium text-slate-300">

                                        Contenu de la publication

                                    </label>

                                    <button class="text-xs text-slate-400">

                                        Plein écran

                                    </button>

                                </div>

                                {{-- TOOLBAR --}}
                                <div class="rounded-t-3xl
                                            bg-slate-950
                                            border border-slate-800
                                            border-b-0
                                            p-3">

                                    <div class="flex flex-wrap gap-2">

                                        @foreach([
                                            'B',
                                            'I',
                                            'U',
                                            'Titre',
                                            'Liste',
                                            'Lien',
                                            'Image',
                                            'PDF',
                                            'Tableau',
                                            'Emoji'
                                        ] as $tool)

                                        <button class="h-10 px-4 rounded-xl
                                                       bg-slate-800
                                                       hover:bg-slate-700
                                                       text-sm">

                                            {{ $tool }}

                                        </button>

                                        @endforeach

                                    </div>

                                </div>

                                {{-- EDITOR --}}
                                <div class="rounded-b-3xl
                                            bg-slate-950
                                            border border-slate-800
                                            min-h-[420px]
                                            p-5">

                                    <div contenteditable="true"
                                         class="outline-none
                                                min-h-[360px]
                                                text-slate-200
                                                leading-relaxed">

                                        <h2 class="text-2xl font-bold">

                                            Réunion des Parents d’Élèves

                                        </h2>

                                        <p class="mt-4">

                                            Chers parents,
                                            nous vous informons
                                            qu’une réunion générale
                                            aura lieu ce vendredi
                                            à 15h00 dans la salle polyvalente.

                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- ATTACHMENTS --}}
                            {{-- ===================================================== --}}
                            <div>

                                <label class="block
                                             text-sm font-medium
                                             text-slate-300 mb-3">

                                    Pièces Jointes

                                </label>

                                <div class="rounded-3xl
                                            border-2 border-dashed
                                            border-slate-700
                                            bg-slate-950
                                            p-8 text-center">

                                    <div class="max-w-md mx-auto">

                                        <h3 class="font-semibold">

                                            Glissez vos fichiers ici

                                        </h3>

                                        <p class="mt-2 text-sm text-slate-400">

                                            PDF, Images, Documents Word,
                                            Bulletins, Rapports…

                                        </p>

                                        <button class="mt-5 h-11 px-5
                                                       rounded-2xl
                                                       bg-indigo-500
                                                       hover:bg-indigo-600">

                                            Sélectionner Fichiers

                                        </button>

                                    </div>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- DELIVERY --}}
                            {{-- ===================================================== --}}
                            <div>

                                <h3 class="text-sm font-medium
                                           text-slate-300 mb-3">

                                    Modes d’Envoi

                                </h3>

                                <div class="grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-3">

                                    @foreach([
                                        ['Email','bg-indigo-500/10 text-indigo-400'],
                                        ['WhatsApp','bg-emerald-500/10 text-emerald-400'],
                                        ['SMS','bg-amber-500/10 text-amber-400'],
                                        ['Notification','bg-sky-500/10 text-sky-400']
                                    ] as $delivery)

                                    <label class="rounded-2xl
                                                  border border-slate-800
                                                  bg-slate-950
                                                  p-4 flex items-center gap-4
                                                  cursor-pointer">

                                        <input type="checkbox">

                                        <div>

                                            <div class="px-3 py-1 rounded-full
                                                        text-xs inline-flex {{ $delivery[1] }}">

                                                {{ $delivery[0] }}

                                            </div>

                                        </div>

                                    </label>

                                    @endforeach

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- SCHEDULE --}}
                            {{-- ===================================================== --}}
                            <div>

                                <div class="grid
                                            grid-cols-1
                                            md:grid-cols-2
                                            gap-4">

                                    <div>

                                        <label class="block
                                                     text-sm text-slate-300 mb-2">

                                            Date d’envoi

                                        </label>

                                        <input type="datetime-local"
                                               class="w-full
                                                      h-12
                                                      rounded-2xl
                                                      bg-slate-950
                                                      border border-slate-800
                                                      px-4">

                                    </div>

                                    <div>

                                        <label class="block
                                                     text-sm text-slate-300 mb-2">

                                            Expiration

                                        </label>

                                        <input type="datetime-local"
                                               class="w-full
                                                      h-12
                                                      rounded-2xl
                                                      bg-slate-950
                                                      border border-slate-800
                                                      px-4">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT SIDEBAR --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- ===================================================== --}}
                    {{-- PREVIEW --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <div class="flex items-center justify-between">

                            <h2 class="text-lg font-semibold">

                                Aperçu

                            </h2>

                            <span class="text-xs text-slate-400">

                                Temps réel

                            </span>

                        </div>

                        <div class="mt-5 rounded-3xl
                                    bg-slate-950
                                    border border-slate-800
                                    p-5">

                            <h3 class="font-bold text-lg">

                                Réunion des Parents

                            </h3>

                            <p class="mt-4 text-sm text-slate-300 leading-relaxed">

                                Chers parents,
                                nous vous informons
                                qu’une réunion aura lieu…

                            </p>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- DELIVERY STATS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Estimation de Diffusion

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach([
                                ['Emails','1240','text-indigo-400'],
                                ['WhatsApp','986','text-emerald-400'],
                                ['SMS','640','text-amber-400'],
                                ['Notifications','1450','text-sky-400']
                            ] as $stat)

                            <div class="flex items-center justify-between">

                                <span class="text-slate-400">

                                    {{ $stat[0] }}

                                </span>

                                <span class="font-semibold {{ $stat[2] }}">

                                    {{ $stat[1] }}

                                </span>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- QUICK ACTIONS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Actions Rapides

                        </h2>

                        <div class="mt-5 space-y-3">

                            @foreach([
                                'Enregistrer Brouillon',
                                'Programmer Envoi',
                                'Envoyer Test',
                                'Prévisualiser PDF',
                                'Dupliquer Publication'
                            ] as $action)

                            <button class="w-full
                                           h-11
                                           rounded-2xl
                                           bg-slate-950
                                           hover:bg-slate-800
                                           border border-slate-800">

                                {{ $action }}

                            </button>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- SEND --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-gradient-to-br
                                from-indigo-500/20
                                to-slate-900
                                border border-indigo-500/20
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Publication Finale

                        </h2>

                        <p class="mt-2 text-sm text-slate-300">

                            Vérifiez les informations
                            avant l’envoi.

                        </p>

                        <div class="mt-5 space-y-3">

                            <button class="w-full
                                           h-12
                                           rounded-2xl
                                           bg-indigo-500
                                           hover:bg-indigo-600
                                           font-medium">

                                Publier Maintenant

                            </button>

                            <button class="w-full
                                           h-12
                                           rounded-2xl
                                           bg-slate-800
                                           hover:bg-slate-700">

                                Sauvegarder Brouillon

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>