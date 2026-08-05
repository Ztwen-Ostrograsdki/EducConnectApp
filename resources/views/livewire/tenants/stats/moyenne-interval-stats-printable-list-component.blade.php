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
                <p class="school-meta">{{ $schoolYear?->periodLabel() }} {{ $period }}</p>
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
    </header>

    <main class="doc-body">
        @if (empty($rows))
            <div class="empty-state">
                <p>Aucune statistique à afficher pour les critères sélectionnés.</p>
            </div>
        @else
            <table class="students-table">
                <thead>
                    <tr class="tr-head">
                        <th class="col-label" rowspan="2">Classe / Groupe</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">G</th>
                        <th rowspan="2">F</th>
                        <th rowspan="2">Abandons</th>
                        @foreach ($intervalLabels as $label)
                            <th colspan="2">{{ $label }}</th>
                        @endforeach
                        <th colspan="2">Réussite</th>
                        <th rowspan="2">+ forte moy.</th>
                        <th rowspan="2">+ faible moy.</th>
                        <th class="col-name" rowspan="2">Meilleur élève</th>
                    </tr>
                    <tr class="tr-subhead">
                        @foreach ($intervalLabels as $label)
                            <th class="sub-col">Eff.</th>
                            <th class="sub-col">%</th>
                        @endforeach
                        <th class="sub-col sub-col-success">Eff.</th>
                        <th class="sub-col sub-col-success">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr class="row-{{ $row['type'] }} {{ $loop->even ? 'row-even' : 'row-odd' }}">
                            <td class="col-label {{ $row['type'] !== 'classe' ? 'cell-bold' : '' }}">
                                {{ $row['label'] }}
                            </td>
                            <td class="td-center cell-numeric">{{ $row['total'] }}</td>
                            <td class="td-center cell-numeric">{{ $row['garcons'] }}</td>
                            <td class="td-center cell-numeric">{{ $row['filles'] }}</td>
                            <td class="td-center cell-numeric">{{ $row['abandons'] }}</td>
                            @foreach ($intervalLabels as $i => $label)
                                <td class="td-center cell-numeric">
                                    {{ \App\Services\ClassesServices\MoyenneIntervalStatsFormatter::intervalCount($row, $i) }}
                                </td>
                                <td class="td-center cell-numeric cell-pct">
                                    {{ \App\Services\ClassesServices\MoyenneIntervalStatsFormatter::intervalPercentage($row, $i) }}
                                </td>
                            @endforeach
                            <td class="td-center cell-numeric cell-success">
                                {{ \App\Services\ClassesServices\MoyenneIntervalStatsFormatter::successCount($row) }}
                            </td>
                            <td class="td-center cell-numeric cell-pct cell-success">
                                {{ \App\Services\ClassesServices\MoyenneIntervalStatsFormatter::successPercentage($row) }}
                            </td>
                            <td class="td-center cell-numeric">
                                {{ $row['bestMoy'] !== null ? number_format($row['bestMoy'], 2) : '—' }}</td>
                            <td class="td-center cell-numeric">
                                {{ $row['worstMoy'] !== null ? number_format($row['worstMoy'], 2) : '—' }}</td>
                            <td class="col-name">{{ $row['bestStudentName'] ?? '—' }}</td>
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
        --purple-bg: #EEEDFE;
        --purple-text: #3C3489;
        --amber-bg: #FEF3C7;
        --amber-text: #92400E;
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
        font-size: 0.75rem;
        table-layout: auto;
    }

    .students-table thead tr {
        background: var(--navy);
    }

    .students-table thead th {
        padding: 7px 4px;
        color: var(--white);
        font-weight: 600;
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        border: 1px solid var(--navy-mid);
        text-align: center;
        white-space: normal;
    }

    .col-label {
        text-align: left !important;
    }

    .col-name {
        text-align: left !important;
    }

    .students-table tbody tr.row-even {
        background: var(--slate-mid);
    }

    .students-table tbody tr.row-odd {
        background: var(--white);
    }

    .students-table tbody td {
        padding: 5px 4px;
        border: 1px solid var(--border);
        color: var(--text);
        text-align: center;
    }

    /* Lignes sous-total / total mises en évidence */
    .row-subtotal td {
        background: var(--purple-bg) !important;
        color: var(--purple-text);
        font-weight: 600;
    }

    .row-total td {
        background: var(--amber-bg) !important;
        color: var(--amber-text);
        font-weight: 700;
    }

    .row-group td {
        background: var(--slate-mid);
        font-weight: 600;
    }

    .cell-bold {
        font-weight: 700;
    }

    .cell-numeric {
        font-family: var(--font-mono);
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--text-muted);
        border: 1px dashed var(--border);
        border-radius: 6px;
        margin-top: 1rem;
    }

    .students-table thead .tr-subhead th.sub-col {
        padding: 4px 3px;
        background: var(--navy-mid);
        color: var(--white);
        font-size: 0.58rem;
        font-weight: 500;
        text-transform: uppercase;
        border: 1px solid var(--navy);
    }

    .cell-pct {
        color: var(--text-muted);
        font-size: 0.7rem;
    }

    .sub-col-success {
        background: var(--green-bg, #DCFCE7) !important;
        color: var(--green-text, #166534) !important;
        border-color: var(--green-border, #86EFAC) !important;
    }

    .cell-success {
        background: rgba(220, 252, 231, 0.3);
        font-weight: 600;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 12mm 10mm;
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

