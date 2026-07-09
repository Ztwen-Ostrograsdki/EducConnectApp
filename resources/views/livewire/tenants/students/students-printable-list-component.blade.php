<div class="print-wrapper">

    {{-- ═══════════════════════════════════════════
         BANDEAU OFFICIEL RÉPUBLIQUE DU BÉNIN
         (converti en CSS custom — ne dépend plus de Tailwind)
    ════════════════════════════════════════════ --}}
    <div class="official-banner">
        <div class="official-banner-content">
            <span class="official-country">République du Bénin</span>
            <span class="official-ministry">Ministère de l'Enseignement Technique et de la Formation
                Professionnelle</span>
            <span class="official-flag">
                <span class="flag-green"></span>
                <span class="flag-yellow"></span>
                <span class="flag-red"></span>
            </span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ENTÊTE INSTITUTIONNEL
    ════════════════════════════════════════════ --}}
    <header class="doc-header">

        {{-- Bandeau top : identité de l'établissement --}}
        <div class="header-band">
            {{-- Logo / initiales --}}
            <div class="school-logo">
                <span class="logo-initials">
                    {{ str()->initials(tenancy()->tenant->school_name) }}
                </span>
                <div class="logo-dot"></div>
            </div>

            {{-- Nom & coordonnées --}}
            <div class="school-identity">
                <h1 class="school-name">{{ tenancy()->tenant->school_name }}</h1>
                <p class="school-subtitle">
                    Enseignement {{ tenancy()->tenant->enseignement_type }}
                </p>
                <p class="school-meta">
                    Établissement : <strong>{{ tenant('school_type') ?? 'Lycée Example' }}</strong>
                    &nbsp;•&nbsp;
                    Adresse : {{ tenant('adresse') ?? '-' }}
                    &nbsp;•&nbsp;
                    Contact : {{ tenant('contacts') ?? '-' }}
                </p>
            </div>

            {{-- Cachet / date --}}
            <div class="doc-stamp">
                <span class="stamp-label">Document officiel</span>
                <span class="stamp-date">{{ $printed_at }}</span>
                <span class="stamp-ref">Réf :
                    PERS-ENS-{{ now()->format('Ymd') }}-{{ str_pad($allStudents, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Ligne décorative tricolore --}}
        <div class="header-rule">
            <div class="rule-navy"></div>
            <div class="rule-gold"></div>
            <div class="rule-light"></div>
        </div>

        {{-- Titre du document --}}
        <div class="doc-title-block">
            @if ($pdf_title)
                <h4 class="doc-title">
                    {{ $pdf_title }}
                </h4>
            @endif
        </div>

    </header>

    {{-- ═══════════════════════════════════════════
         TABLEAU PRINCIPAL
    ════════════════════════════════════════════ --}}
    <main class="doc-body">

        @if ($students->isEmpty())
            <div class="empty-state">
                <p>Aucun apprenant à afficher pour les critères sélectionnés.</p>
            </div>
        @else
            <table class="students-table">
                {{-- Largeurs fixes par colonne : nécessaire avec table-layout:fixed,
                     sinon le navigateur répartit l'espace à parts égales entre
                     les 10 colonnes, sans tenir compte du contenu réel --}}
                <colgroup>
                    <col style="width:4%"> {{-- # --}}
                    <col style="width:10%"> {{-- EducMaster --}}
                    <col style="width:18%"> {{-- Nom & Prénom --}}
                    <col style="width:4%"> {{-- sexe --}}
                    <col style="width:11%"> {{-- Père --}}
                    <col style="width:11%"> {{-- Mère --}}
                    <col style="width:8%"> {{-- Classe --}}
                    <col style="width:15%"> {{-- Contact --}}
                    <col style="width:11%"> {{-- Date naissance / Age --}}
                    <col style="width:9%"> {{-- Statut --}}
                    <col style="width:8%"> {{-- Statut --}}
                </colgroup>
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-grade">EducMaster</th>
                        <th class="col-nom">Nom & Prénom</th>
                        <th class="text-center">Sexe</th>
                        <th class="col-nom">Père</th>
                        <th class="col-nom">Mère</th>
                        <th class="col-dept">Classe</th>
                        <th class="col-contact">Contact</th>
                        <th class="col-recrutement">
                            <span class="th-stacked">
                                <span>Date de naissance</span>
                                <span>Age</span>
                            </span>
                        </th>
                        <th class="col-statut">Statut</th>
                        <th class="col-statut">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                        <tr class="{{ $loop->even ? 'row-even' : 'row-odd' }}">

                            <td class="td-center">{{ $loop->iteration }}</td>

                            <td class="cell-matricule">
                                <span>{{ $student->educMaster }} </span>
                            </td>

                            <td class="cell-wrap cell-name ">
                                <div class="cell-flex-col">
                                    <span>{{ $student->getFullName() }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $student->matricule }}</span>
                                </div>
                            </td>
                            <td class="td-center">
                                {{ str()->upper(str()->substr($student->gender, 0, 1)) }}
                            </td>

                            <td class="cell-wrap">{{ $student->father_full_name }}</td>
                            <td class="cell-wrap">{{ $student->mother_full_name }}</td>

                            <td class="cell-wrap">
                                @if ($student->currentClasse() && $student->currentClasse()->classe)
                                    @php
                                        $rel = $student->currentClasse()->classe;
                                    @endphp
                                    <span class="dept-main">{{ $rel->code ? $rel->code : $rel->name }}</span>
                                @else
                                    <span class="dept-empty">Pas de classe</span>
                                @endif
                            </td>

                            <td class="cell-wrap cell-contact">
                                @if ($student->contacts)
                                    <p>{{ $student->contacts }}</p>
                                @endif
                                @if ($student->email)
                                    <p class="contact-email">{{ $student->email ?? 'non renseigné' }}</p>
                                @endif
                            </td>

                            <td class="td-center cell-age ">
                                <div class="cell-flex-col">
                                    <span>{{ __formatDate($student->birth_date) }}</span>
                                    <span>{{ __getAge($student->birth_date) }} ans</span>
                                </div>
                            </td>

                            <td class="td-center">
                                <span class="statut-badge statut-badge--{{ $student->status ?? 'inactif' }}">
                                    {{ ucfirst($student->status ? 'Actif' : 'Inactif') }}
                                </span>
                            </td>
                            <td class="td-center">

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </main>

</div>

{{-- ═══════════════════════════════════════════
     STYLES DÉDIÉS À L'IMPRESSION
════════════════════════════════════════════ --}}
<style>
    /* ── Variables ──────────────────────────────── */
    :root {
        --navy: #1E3A5F;
        --navy-mid: #2C5282;
        --navy-light: #EBF4FF;
        --gold: #C9A84C;
        --gold-light: #FEF9EE;
        --slate: #475569;
        --slate-light: #F8FAFC;
        --slate-mid: #F1F5F9;
        --text: #0F172A;
        --text-muted: #64748B;
        --border: #CBD5E1;
        --border-dark: #94A3B8;
        --white: #FFFFFF;
        --font-serif: 'Georgia', 'Times New Roman', serif;
        --font-sans: 'Inter', 'Segoe UI', system-ui, sans-serif;
        --font-mono: 'DM Mono', 'Courier New', monospace;
    }

    /* ── Reset impression ───────────────────────── */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: var(--font-sans);
        color: var(--text);
        background: var(--white);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* ── Wrapper principal ──────────────────────── */
    .print-wrapper {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        padding: 2rem 2.5rem 1.5rem;
        background: var(--white);
    }

    /* ══ BANDEAU OFFICIEL (ex-Tailwind, en CSS custom) ══ */
    .official-banner {
        width: 100%;
        margin: 0 auto 1rem;
        padding: 0.6rem 1.25rem;
        text-align: center;
    }

    .official-banner-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
    }

    .official-country {
        font-weight: 700;
        text-transform: uppercase;
        color: #EA580C;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .official-ministry {
        font-size: 0.78rem;
        color: var(--text);
    }

    .official-flag {
        display: flex;
        width: 100%;
        margin-top: 4px;
    }

    .flag-green,
    .flag-yellow,
    .flag-red {
        display: inline-block;
        flex: 1;
        height: 6px;
    }

    .flag-green {
        background: #22C55E;
    }

    .flag-yellow {
        background: #EAB308;
    }

    .flag-red {
        background: #DC2626;
    }

    /* ══ ENTÊTE ═════════════════════════════════ */
    .header-band {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        padding-bottom: 1.25rem;
    }

    .school-logo {
        flex-shrink: 0;
        position: relative;
        width: 72px;
        height: 72px;
    }

    .logo-initials {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        background: var(--navy);
        border-radius: 50%;
        color: var(--gold);
        font-family: var(--font-serif);
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        border: 3px solid var(--gold);
    }

    .logo-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 14px;
        height: 14px;
        background: var(--gold);
        border-radius: 50%;
        border: 2px solid var(--white);
    }

    .school-identity {
        flex: 1;
    }

    .school-name {
        font-family: var(--font-mono);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--navy);
        letter-spacing: 0.02em;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .school-subtitle {
        font-size: 0.8rem;
        color: var(--gold);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-top: 2px;
        font-weight: 600;
    }

    .school-meta {
        margin-top: 8px;
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .doc-stamp {
        flex-shrink: 0;
        text-align: right;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .stamp-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--gold);
        font-weight: 700;
    }

    .stamp-date {
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .stamp-ref {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: var(--slate);
        background: var(--slate-mid);
        padding: 2px 8px;
        border-radius: 3px;
        border: 1px solid var(--border);
        margin-top: 4px;
    }

    .header-rule {
        display: flex;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .rule-navy {
        flex: 4;
        background: var(--navy);
    }

    .rule-gold {
        flex: 1;
        background: var(--gold);
    }

    .rule-light {
        flex: 1;
        background: var(--navy-light);
        border: 1px solid var(--border);
    }

    .doc-title-block {
        padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--navy);
        text-align: center;
    }

    .doc-title {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* ══ TABLEAU ════════════════════════════════ */
    .doc-body {
        margin-top: 1.25rem;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
        table-layout: fixed;
        /* nécessite le <colgroup> ci-dessus pour bien répartir l'espace */
    }

    .students-table thead tr {
        background: var(--navy);
    }

    .students-table thead th {
        padding: 9px 6px;
        color: var(--white);
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border: 1px solid var(--navy-mid);
        vertical-align: middle;
        text-align: center;
    }

    .th-stacked {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .students-table tbody tr.row-odd {
        background: var(--white);
    }

    .students-table tbody tr.row-even {
        background: var(--slate-mid);
    }

    .students-table tbody tr:hover {
        background: var(--navy-light);
    }

    /* Cellules : par défaut on autorise le retour à la ligne
       (plus de troncature globale qui masquait noms/emails) */
    .students-table tbody td {
        padding: 7px 6px;
        border: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
        line-height: 1.4;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        text-align: left;
    }

    .td-center {
        text-align: center;
    }

    /* Matricule : reste sur une ligne, c'est court par nature */
    .cell-matricule {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--navy);
        white-space: nowrap;
        text-align: center;
    }

    .cell-name {
        font-weight: 600;
        color: var(--text);
    }

    .dept-main {
        font-weight: 600;
        color: var(--text);
    }

    .dept-empty {
        font-size: 0.72rem;
        color: var(--slate);
        font-style: italic;
    }

    .cell-contact p {
        font-size: 0.76rem;
        line-height: 1.35;
        word-break: break-all;
        /* les emails longs ne débordent plus de la colonne */
    }

    .contact-email {
        color: var(--navy-mid);
        font-size: 0.7rem !important;
    }

    .cell-flex-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        border-collapse: collapse !important;
    }

    .cell-age {
        font-size: 0.75rem;
    }

    /* Statut badge */
    .statut-badge {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
    }

    .statut-badge--actif {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #86EFAC;
    }

    .statut-badge--inactif {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FCA5A5;
    }

    .statut-badge--conge {
        background: #FEF9C3;
        color: #854D0E;
        border: 1px solid #FDE047;
    }

    .statut-badge--suspend {
        background: #F3E8FF;
        color: #6B21A8;
        border: 1px solid #D8B4FE;
    }

    /* État vide */
    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--text-muted);
        border: 1px dashed var(--border);
        border-radius: 6px;
        margin-top: 1rem;
    }

    td,
    th {
        text-align: center !important;
    }

    /* ══ RÈGLES D'IMPRESSION @media print ══════ */
    @media print {
        @page {
            size: A4 landscape;
            margin: 14mm 12mm 16mm 12mm;
        }

        body {
            background: white;
        }

        .print-wrapper {
            max-width: 100%;
            padding: 0;
        }

        .students-table tbody tr {
            page-break-inside: avoid;
        }

        .students-table thead {
            display: table-header-group;
        }

        .students-table tfoot {
            display: table-footer-group;
        }

        .students-table thead tr,
        .row-even,
        .statut-badge,
        .cell-matricule,
        .header-rule,
        .rule-navy,
        .rule-gold,
        .rule-light,
        .official-flag,
        .flag-green,
        .flag-yellow,
        .flag-red {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

