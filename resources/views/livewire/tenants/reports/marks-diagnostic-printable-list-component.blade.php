<div class="print-wrapper">

    <div class="official-banner">
        <div class="official-banner-content">
            <span class="official-country">République du Bénin</span>
            <span class="official-ministry">Ministère de l'Enseignement Technique et de la Formation
                Professionnelle</span>
            <span class="official-flag">
                <span class="flag-green"></span><span class="flag-yellow"></span><span class="flag-red"></span>
            </span>
        </div>
    </div>

    <header class="doc-header">
        <div class="header-band">
            <div class="school-logo">
                <span class="logo-initials">{{ str()->initials(tenancy()->tenant->school_name) }}</span>
                <div class="logo-dot"></div>
            </div>
            <div class="school-identity">
                <h1 class="school-name">{{ tenancy()->tenant->school_name }}</h1>
                <p class="school-subtitle">Enseignement {{ tenancy()->tenant->enseignement_type }}</p>
                <p class="school-meta">
                    {{ $schoolYear?->periodLabel() }} {{ $period }}
                </p>
            </div>
            <div class="doc-stamp">
                <span class="stamp-label">Document officiel</span>
                <span class="stamp-date">{{ $printed_at }}</span>
            </div>
        </div>

        <div class="header-rule">
            <div class="rule-navy"></div>
            <div class="rule-gold"></div>
            <div class="rule-light"></div>
        </div>

        @if ($pdf_title)
            <div class="doc-title-block">
                <h4 class="doc-title">{{ $pdf_title }}</h4>
            </div>
        @endif
        <div class="doc-counter">
            <h4>Total: {{ __zero($allRows) }} Affectation(s)</h4>
        </div>
    </header>

    <main class="doc-body">
        @if (empty($rows))
            <div class="empty-state">
                <p>Aucune affectation à afficher pour les critères sélectionnés.</p>
            </div>
        @else
            @php
                $columns = $tableColumns;
                $widths = \App\Services\MarksServices\MarksDiagnosticFormatter::normalizedWidths($columns);
            @endphp

            <table class="students-table">
                <colgroup>
                    <col style="width:4%">
                    @foreach ($columns as $col)
                        <col style="width: {{ $widths[$col['key']] ?? 'auto' }}">
                    @endforeach
                </colgroup>
                <thead>
                    <tr class="tr-head">
                        <th class="col-num">#</th>
                        @foreach ($columns as $col)
                            <th>{{ $col['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr class="{{ $loop->even ? 'row-even' : 'row-odd' }}">
                            <td class="td-center">{{ $row['index'] }}</td>
                            @foreach ($columns as $col)
                                <td
                                    class="{{ $col['key'] === 'teacher' ? 'cell-name' : 'td-center' }}
                                    {{ $col['key'] === 'students_without_list' ? 'cell-list' : '' }}">
                                    {!! $row['cells'][$col['key']] ?? '—' !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </main>
</div>

<style>
    :root {
        --navy: #1E3A5F;
        --navy-mid: #2C5282;
        --navy-light: #EBF4FF;
        --gold: #C9A84C;
        --slate: #475569;
        --slate-mid: #F1F5F9;
        --text: #000000;
        --text-muted: #4B5563;
        --border: #CBD5E1;
        --white: #FFFFFF;
        --green-bg: #DCFCE7;
        --green-text: #166534;
        --green-border: #86EFAC;
        --red-bg: #FEE2E2;
        --red-text: #991B1B;
        --red-border: #FCA5A5;
        --font-serif: 'Georgia', serif;
        --font-sans: 'Inter', sans-serif;
        --font-mono: 'DM Mono', monospace;
    }

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

    .print-wrapper {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        padding: 2rem 2.5rem 1.5rem;
        background: var(--white);
    }

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
        text-align: left;
    }

    .doc-counter h4 {
        text-align: right;
        padding: 7px 10px;
        font-size: 1.5em;
        font-family: monospace;
        border: thin solid gray;
        display: inline-flex;
        float: right;
        margin: 5px 0;
        border-radius: 10px;
    }

    .doc-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .doc-body {
        margin-top: 1.25rem;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
        table-layout: fixed;
    }

    .students-table thead tr {
        background: var(--navy);
    }

    .students-table thead th {
        padding: 9px 4px;
        color: var(--white);
        font-weight: 600;
        font-size: 0.66rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border: 1px solid var(--navy-mid);
        text-align: center;
        white-space: normal;
        word-break: break-word;
        line-height: 1.25;
    }

    .students-table thead tr:hover {
        background: #09001bfd !important;
    }

    .students-table tbody tr.row-odd {
        background: var(--white);
    }

    .students-table tbody tr.row-even {
        background: var(--slate-mid);
    }

    .students-table tbody td {
        padding: 7px 6px;
        border: 1px solid var(--border);
        color: var(--text);
        line-height: 1.4;
        word-break: break-word;
        text-align: center;
    }

    .td-center {
        text-align: center;
    }

    .cell-name {
        text-align: left;
        font-weight: 600;
    }

    /* Colonne liste d'élèves : plus petite police, alignée à gauche, lisible même si longue */
    .cell-list {
        text-align: left;
        font-size: 0.7rem;
        line-height: 1.4;
        color: var(--text-muted);
    }

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
        background: var(--green-bg);
        color: var(--green-text);
        border: 1px solid var(--green-border);
    }

    .statut-badge--inactif {
        background: var(--red-bg);
        color: var(--red-text);
        border: 1px solid var(--red-border);
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--text-muted);
        border: 1px dashed var(--border);
        border-radius: 6px;
        margin-top: 1rem;
    }

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
    }
</style>
