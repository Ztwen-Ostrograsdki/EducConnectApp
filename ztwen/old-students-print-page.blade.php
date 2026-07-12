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
                <colgroup>
                    <col style="width:4%"> {{-- # --}}
                    @foreach ($tableColumns ?: $defaultColumns as $col)
                        <col>
                    @endforeach
                </colgroup>

                <thead>
                    <tr class="hover:bg-indigo-950 tr-head">
                        <th class="col-num">#</th>
                        @foreach ($tableColumns ?: $defaultColumns as $col)
                            <th class="col-grade">{{ $col['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($students as $student)
                        <tr class="{{ $loop->even ? 'row-even' : 'row-odd' }}">
                            <td class="td-center">{{ $loop->iteration }}</td>

                            @foreach ($tableColumns ?: $defaultColumns as $col)
                                <td class="cell-wrap @if ($col['key'] === 'full_name') cell-name-col @endif">
                                    @if ($col['key'] === 'full_name')
                                        <div class="cell-flex-col cell-name-block">
                                            <span class="name-main">{!! \App\Livewire\Tenants\Students\StudentsPrintableListComponent::getData($student, $col) !!}</span>
                                            <span class="name-matricule">Matricule : {{ $student->matricule }}</span>
                                        </div>
                                    @else
                                        {!! \App\Livewire\Tenants\Students\StudentsPrintableListComponent::getData($student, $col) !!}
                                    @endif
                                </td>
                            @endforeach
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

    .students-table thead tr:hover,
    .tr-head:hover {
        background: rgb(4, 1, 24) !important;
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

    /* Cellule nom : alignée à gauche, matricule discret en dessous */
    .cell-name-block {
        align-items: flex-start !important;
        text-align: left !important;
    }

    .cell-name-block .name-main {
        font-weight: 600;
        color: var(--text);
        font-size: 0.8rem;
    }

    .cell-name-block .name-matricule {
        font-size: 0.68rem;
        font-weight: 400;
        color: var(--text-muted);
        margin-top: 7px;
    }

    td.cell-name-col {
        text-align: left !important;
    }

    /* Cellule âge : date en haut, âge en bas, plus petit et moins dense */
    .cell-age-col .age-date {
        font-size: 0.76rem;
        color: var(--text);
    }

    .cell-age-col .age-years {
        font-size: 0.66rem;
        font-weight: 400;
        color: var(--text-muted);
        margin-top: 1px;
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
