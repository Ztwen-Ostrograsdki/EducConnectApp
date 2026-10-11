{{--
    Template d’impression PDF — Emplois du temps (paysage, 1 page / EDT)

    Données attendues :
    - $schoolYear          : SchoolYear|null
    - $printed_at          : string
    - $pdf_title           : string (titre global du document)
    - $context_label       : string|null  (ex. "Filière Série A" / "Matière Mathématiques")
    - $plans               : array de créneaux d’affichage, chacun :
        [
            'type'        => 'teacher' | 'classe',
            'title'       => string,          // Nom complet du prof OU nom de la classe
            'subtitle'    => string|null,     // Ex. code classe, matières, grade…
            'contacts'    => string|null,     // Téléphone / email
            'school_year' => string,          // slug / libellé année
            'total_hours' => int|float,       // heures cumulées
            'meta_line'   => string|null,     // ligne libre (ex. "2 matières · 4 classes")
            'days'        => [1 => 'Lundi', … 6 => 'Samedi'],
            'rows'        => scheduleRows(),  // identique à TimePlanActions::scheduleRows()
            'cells'       => [
                // [day][rowStart] => null | [
                //   'label'   => 'MATH',
                //   'detail'  => '2nde A' | 'AP',
                //   'start'   => '08h00',
                //   'end'     => '10h00',
                //   'span'    => 2,          // rowspan en lignes horaires contiguës
                //   'is_ap'   => false,
                //   'covered' => false,      // true = cellule absorbée par un rowspan précédent
                // ]
            ],
        ]

    Options Browsershot recommandées :
    - format: 'A4'
    - landscape: true
    - margins: 10–12 mm
--}}

@php
    $days = $days ?? [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
    $plans = $plans ?? [];
@endphp

<div class="print-root">

    @forelse ($plans as $planIndex => $plan)
        @php
            $planType = $plan['type'] ?? 'classe';
            $planDays = $plan['days'] ?? $days;
            $planRows = $plan['rows'] ?? [];
            $planCells = $plan['cells'] ?? [];
            $totalHours = $plan['total_hours'] ?? 0;
        @endphp

        <section class="edt-page {{ $loop->last ? 'is-last' : '' }}">

            {{-- ══════════ BANDEAU RÉPUBLIQUE ══════════ --}}
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

            {{-- ══════════ EN-TÊTE ÉTABLISSEMENT ══════════ --}}
            <header class="doc-header">
                <div class="header-band">
                    <div class="school-logo">
                        <span class="logo-initials">{{ str()->initials(tenancy()->tenant->school_name ?? 'E') }}</span>
                        <div class="logo-dot"></div>
                    </div>

                    <div class="school-identity">
                        <h1 class="school-name">{{ tenancy()->tenant->school_name ?? 'Établissement' }}</h1>
                        <p class="school-subtitle">
                            Enseignement {{ tenancy()->tenant->enseignement_type ?? '' }}
                        </p>
                        <p class="school-meta">
                            Année scolaire :
                            <strong>{{ $plan['school_year'] ?? ($schoolYear->slug ?? ($schoolYear->name ?? '—')) }}</strong>
                            @if ($context_label ?? null)
                                &nbsp;•&nbsp; {{ $context_label }}
                            @endif
                        </p>
                    </div>

                    <div class="doc-stamp">
                        <span class="stamp-label">Emploi du temps</span>
                        <span class="stamp-type">
                            {{ $planType === 'teacher' ? 'Enseignant' : 'Classe' }}
                        </span>
                        <span class="stamp-date">{{ $printed_at ?? now()->isoFormat('D/MM/YYYY') }}</span>
                    </div>
                </div>

                <div class="header-rule">
                    <div class="rule-navy"></div>
                    <div class="rule-gold"></div>
                    <div class="rule-light"></div>
                </div>

                {{-- Titre de l’EDT --}}
                <div class="edt-title-block">
                    <h2 class="edt-title">
                        @if ($planType === 'teacher')
                            {{ $plan['title'] ?? 'Enseignant' }}
                        @else
                            Classe : {{ $plan['title'] ?? '—' }}
                        @endif
                    </h2>

                    @if (!empty($plan['subtitle']))
                        <p class="edt-subtitle">{{ $plan['subtitle'] }}</p>
                    @endif
                </div>

                {{-- Métadonnées --}}
                <div class="edt-meta-bar">
                    @if ($planType === 'teacher')
                        <div class="meta-item">
                            <span class="meta-label">Nom & Prénom(s)</span>
                            <span class="meta-value">{{ $plan['title'] ?? '—' }}</span>
                        </div>
                        @if (!empty($plan['contacts']))
                            <div class="meta-item">
                                <span class="meta-label">Contacts</span>
                                <span class="meta-value">{{ $plan['contacts'] }}</span>
                            </div>
                        @endif
                    @else
                        <div class="meta-item">
                            <span class="meta-label">Classe</span>
                            <span class="meta-value">{{ $plan['title'] ?? '—' }}</span>
                        </div>
                    @endif

                    <div class="meta-item">
                        <span class="meta-label">Année scolaire</span>
                        <span class="meta-value">{{ $plan['school_year'] ?? ($schoolYear->slug ?? '—') }}</span>
                    </div>

                    <div class="meta-item meta-hours">
                        <span class="meta-label">Volume horaire</span>
                        <span class="meta-value">{{ $totalHours }} h</span>
                    </div>

                    @if (!empty($plan['meta_line']))
                        <div class="meta-item meta-wide">
                            <span class="meta-label">Détail</span>
                            <span class="meta-value">{{ $plan['meta_line'] }}</span>
                        </div>
                    @endif
                </div>
            </header>

            {{-- ══════════ GRILLE HORAIRE ══════════ --}}
            <main class="doc-body">
                @if (empty($planRows))
                    <div class="empty-state">
                        <p>Aucune plage horaire configurée.</p>
                    </div>
                @else
                    <table class="edt-table">
                        <thead>
                            <tr>
                                <th class="col-time">Horaire</th>
                                @foreach ($planDays as $dayNum => $dayName)
                                    <th class="col-day">{{ $dayName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planRows as $row)
                                @if (($row['type'] ?? '') === 'break')
                                    <tr class="row-break row-break-{{ $row['variant'] ?? 'recreation' }}">
                                        <td class="cell-time cell-break-label">
                                            {{ $row['label'] ?? 'Pause' }}
                                        </td>
                                        @foreach ($planDays as $dayNum => $dayName)
                                            <td class="cell-break"></td>
                                        @endforeach
                                    </tr>
                                @else
                                    <tr class="row-hour">
                                        <td class="cell-time">
                                            <span
                                                class="time-start">{{ \Illuminate\Support\Carbon::parse($row['start'])->format('H\hi') }}</span>
                                            <span class="time-sep">–</span>
                                            <span
                                                class="time-end">{{ \Illuminate\Support\Carbon::parse($row['end'])->format('H\hi') }}</span>
                                        </td>

                                        @foreach ($planDays as $dayNum => $dayName)
                                            @php
                                                $cell = $planCells[$dayNum][$row['start']] ?? null;
                                                $covered = is_array($cell) ? (bool) ($cell['covered'] ?? false) : false;
                                            @endphp

                                            @if ($covered)
                                                {{-- absorbé par rowspan --}}
                                            @elseif (is_array($cell) && !empty($cell['label']))
                                                @php
                                                    $span = max(1, (int) ($cell['span'] ?? 1));
                                                    $isAp = (bool) ($cell['is_ap'] ?? false);
                                                @endphp
                                                <td class="cell-slot {{ $isAp ? 'is-ap' : 'is-course' }}"
                                                    rowspan="{{ $span }}">
                                                    <div class="slot-inner">
                                                        <span class="slot-label">{{ $cell['label'] }}</span>
                                                        @if (!empty($cell['detail']))
                                                            <span class="slot-detail">{{ $cell['detail'] }}</span>
                                                        @endif
                                                        @if (!empty($cell['start']) && !empty($cell['end']))
                                                            <span class="slot-hours">{{ $cell['start'] }} –
                                                                {{ $cell['end'] }}</span>
                                                        @endif
                                                        @if ($isAp)
                                                            <span class="slot-badge-ap">AP</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            @else
                                                <td class="cell-empty"></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </main>

            {{-- ══════════ SIGNATURES ══════════ --}}
            <footer class="edt-signatures">
                <div class="sig-row">
                    <div class="sig-block">
                        <p class="sig-role">Censeur</p>
                        <p class="sig-space">&nbsp;</p>
                        <p class="sig-line">_________________________</p>
                    </div>
                    <div class="sig-block">
                        <p class="sig-role">Directeur</p>
                        <p class="sig-space">&nbsp;</p>
                        <p class="sig-line">_________________________</p>
                    </div>
                    <div class="sig-block">
                        <p class="sig-role">Enseignant</p>
                        <p class="sig-space">&nbsp;</p>
                        <p class="sig-line">_________________________</p>
                    </div>
                </div>

                <div class="sig-date">
                    <span>Date : ……… / ……… / …………</span>
                </div>
            </footer>

        </section>
    @empty
        <section class="edt-page">
            <div class="official-banner">
                <div class="official-banner-content">
                    <span class="official-country">République du Bénin</span>
                    <span class="official-ministry">Ministère de l'Enseignement Technique et de la Formation
                        Professionnelle</span>
                </div>
            </div>
            <div class="empty-state">
                <p>Aucun emploi du temps à imprimer.</p>
            </div>
        </section>
    @endforelse

</div>

<style>
    :root {
        --navy: #1E3A5F;
        --navy-mid: #2C5282;
        --navy-light: #EBF4FF;
        --gold: #C9A84C;
        --violet: #6D28D9;
        --violet-bg: #F5F3FF;
        --slate: #475569;
        --slate-mid: #F1F5F9;
        --text: #0F172A;
        --text-muted: #64748B;
        --border: #CBD5E1;
        --border-soft: #E2E8F0;
        --white: #FFFFFF;
        --course-bg: #EEF2FF;
        --course-border: #6366F1;
        --ap-bg: #F5F3FF;
        --ap-border: #8B5CF6;
        --break-bg: #FFFBEB;
        --lunch-bg: #FFF7ED;
        --font-serif: 'Georgia', 'Times New Roman', serif;
        --font-sans: 'Inter', 'Segoe UI', system-ui, sans-serif;
        --font-mono: 'DM Mono', 'Consolas', monospace;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html,
    body {
        font-family: var(--font-sans);
        color: var(--text);
        background: var(--white);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 11px;
        line-height: 1.35;
    }

    .print-root {
        width: 100%;
    }

    /* ─── PAGE = 1 EDT ─── */
    .edt-page {
        width: 100%;
        padding: 8mm 10mm 6mm;
        page-break-after: always;
        break-after: page;
        display: flex;
        flex-direction: column;
        min-height: 190mm;
        /* A4 paysage utile */
    }

    .edt-page.is-last {
        page-break-after: auto;
        break-after: auto;
    }

    /* ─── BANDEAU OFFICIEL ─── */
    .official-banner {
        text-align: center;
        margin-bottom: 4mm;
    }

    .official-banner-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
    }

    .official-country {
        font-weight: 700;
        text-transform: uppercase;
        color: #EA580C;
        font-size: 9.5px;
        letter-spacing: 0.06em;
    }

    .official-ministry {
        font-size: 8.5px;
        color: var(--text);
    }

    .official-flag {
        display: flex;
        width: 100%;
        max-width: 220px;
        margin-top: 2px;
    }

    .flag-green,
    .flag-yellow,
    .flag-red {
        flex: 1;
        height: 4px;
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

    /* ─── HEADER ─── */
    .doc-header {
        margin-bottom: 3mm;
    }

    .header-band {
        display: flex;
        align-items: flex-start;
        gap: 4mm;
        padding-bottom: 2.5mm;
    }

    .school-logo {
        position: relative;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
    }

    .logo-initials {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: var(--navy);
        border-radius: 50%;
        color: var(--gold);
        font-family: var(--font-serif);
        font-size: 15px;
        font-weight: 700;
        border: 2px solid var(--gold);
    }

    .logo-dot {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 10px;
        height: 10px;
        background: var(--gold);
        border-radius: 50%;
        border: 2px solid var(--white);
    }

    .school-identity {
        flex: 1;
        min-width: 0;
    }

    .school-name {
        font-family: var(--font-serif);
        font-size: 15px;
        font-weight: 700;
        color: var(--navy);
        line-height: 1.2;
    }

    .school-subtitle {
        font-size: 9px;
        color: var(--slate);
        margin-top: 1px;
    }

    .school-meta {
        font-size: 8.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .doc-stamp {
        text-align: right;
        flex-shrink: 0;
    }

    .stamp-label {
        display: block;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gold);
        font-weight: 700;
    }

    .stamp-type {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--navy);
        margin-top: 1px;
    }

    .stamp-date {
        display: block;
        font-size: 8px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .header-rule {
        display: flex;
        flex-direction: column;
        gap: 1px;
        margin: 2mm 0 2.5mm;
    }

    .rule-navy {
        height: 2.5px;
        background: var(--navy);
    }

    .rule-gold {
        height: 1.5px;
        background: var(--gold);
    }

    .rule-light {
        height: 1px;
        background: var(--navy-light);
    }

    .edt-title-block {
        text-align: center;
        margin-bottom: 2mm;
    }

    .edt-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .edt-subtitle {
        font-size: 9.5px;
        color: var(--slate);
        margin-top: 1px;
    }

    .edt-meta-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 2mm 5mm;
        padding: 2mm 3mm;
        background: var(--navy-light);
        border: 1px solid var(--border-soft);
        border-radius: 3px;
        margin-bottom: 2.5mm;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 1px;
        min-width: 80px;
    }

    .meta-wide {
        flex: 1;
        min-width: 140px;
    }

    .meta-label {
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        font-weight: 600;
    }

    .meta-value {
        font-size: 10px;
        font-weight: 600;
        color: var(--text);
    }

    .meta-hours .meta-value {
        color: var(--navy);
        font-size: 11px;
    }

    /* ─── TABLEAU ─── */
    .doc-body {
        flex: 1;
    }

    .edt-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 8.5px;
    }

    .edt-table thead th {
        background: var(--navy);
        color: var(--white);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 2.5mm 1.5mm;
        border: 1px solid var(--navy);
        text-align: center;
        font-size: 8.5px;
    }

    .col-time {
        width: 11%;
    }

    .col-day {
        width: 14.8%;
    }

    .cell-time {
        background: var(--slate-mid);
        font-family: var(--font-mono);
        font-weight: 600;
        font-size: 8px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid var(--border);
        padding: 1.5mm 1mm;
        color: var(--slate);
        white-space: nowrap;
    }

    .time-sep {
        color: var(--border);
        margin: 0 1px;
    }

    .row-hour td {
        height: 11mm;
        vertical-align: top;
    }

    .cell-empty {
        border: 1px solid var(--border-soft);
        background: #FAFBFC;
    }

    .cell-slot {
        border: 1px solid var(--border);
        padding: 1mm;
        vertical-align: top;
    }

    .cell-slot.is-course {
        background: var(--course-bg);
        border-color: #C7D2FE;
    }

    .cell-slot.is-ap {
        background: var(--ap-bg);
        border-color: #DDD6FE;
    }

    .slot-inner {
        display: flex;
        flex-direction: column;
        gap: 0.5mm;
        height: 100%;
        position: relative;
        padding: 0.5mm;
    }

    .slot-label {
        font-weight: 700;
        font-size: 9px;
        color: var(--navy);
        line-height: 1.2;
    }

    .is-ap .slot-label {
        color: var(--violet);
    }

    .slot-detail {
        font-size: 7.5px;
        color: var(--text-muted);
        line-height: 1.2;
    }

    .slot-hours {
        font-family: var(--font-mono);
        font-size: 7px;
        color: var(--slate);
        margin-top: auto;
    }

    .slot-badge-ap {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 6.5px;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: var(--violet);
        background: #EDE9FE;
        border: 1px solid #DDD6FE;
        border-radius: 2px;
        padding: 0 2px;
        line-height: 1.4;
    }

    /* Pauses */
    .row-break td {
        height: 5.5mm;
        border: 1px solid var(--border-soft);
    }

    .row-break-recreation td {
        background: var(--break-bg);
    }

    .row-break-lunch td {
        background: var(--lunch-bg);
    }

    .cell-break-label {
        font-size: 7px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #B45309;
        text-align: center;
    }

    .empty-state {
        padding: 15mm;
        text-align: center;
        color: var(--text-muted);
        font-size: 11px;
        border: 1px dashed var(--border);
        border-radius: 4px;
    }

    /* ─── SIGNATURES ─── */
    .edt-signatures {
        margin-top: 5mm;
        padding-top: 3mm;
        border-top: 1px solid var(--border-soft);
    }

    .sig-row {
        display: flex;
        justify-content: space-between;
        gap: 8mm;
        margin-bottom: 4mm;
    }

    .sig-block {
        flex: 1;
        text-align: center;
    }

    .sig-role {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--navy);
        margin-bottom: 1mm;
    }

    .sig-space {
        height: 12mm;
        /* espace signature manuscrite */
        line-height: 12mm;
    }

    .sig-line {
        font-size: 9px;
        color: var(--border);
        letter-spacing: 1px;
    }

    .sig-date {
        text-align: right;
        font-size: 9.5px;
        color: var(--text);
        font-weight: 500;
        padding-right: 2mm;
    }

    /* ─── PRINT ─── */
    @media print {

        html,
        body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .edt-page {
            page-break-after: always;
            break-after: page;
        }

        .edt-page.is-last {
            page-break-after: auto;
            break-after: auto;
        }

        thead {
            display: table-header-group;
        }
    }

    @page {
        size: A4 landscape;
        margin: 8mm 10mm;
    }
</style>

