@php

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

            @unless (($repeatHeader ?? false) && ($isPdf ?? false))
                @include('pdf.partials.header-banner')
            @endunless
            <div class="official-banner">
                <div class="official-banner-content">
                    <span class="official-flag">
                        <span class="flag-green"></span>
                        <span class="flag-yellow"></span>
                        <span class="flag-red"></span>
                    </span>
                </div>
            </div>

            {{-- ══════════ EN-TÊTE ÉTABLISSEMENT ══════════ --}}
            <header class="doc-header">

                {{-- Titre de l’EDT --}}
                <div class="edt-title-block">
                    <h2 class="edt-title">
                        @if ($planType === 'teacher')
                            EMPLOI DU TEMPS ENSEIGNANT : {{ $plan['title'] ?? 'Enseignant' }}
                        @else
                            EMPLOI DU TEMPS DE CLASSE : {{ $plan['title'] ?? '—' }}
                        @endif
                    </h2>

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
                                            RECREATION
                                        </td>
                                        <td colspan="6" class="cell-break">RECREATION</td>
                                    </tr>
                                @else
                                    <tr class="row-hour">
                                        <td class="cell-time">
                                            <span class="cell-time-container">
                                                <span
                                                    class="time-start">{{ \Illuminate\Support\Carbon::parse($row['start'])->format('H\hi') }}</span>
                                                <span class="time-sep">–</span>
                                                <span
                                                    class="time-end">{{ \Illuminate\Support\Carbon::parse($row['end'])->format('H\hi') }}</span>
                                            </span>
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
            @unless (($repeatHeader ?? false) && ($isPdf ?? false))
                @include('pdf.partials.header-banner')
            @endunless
            <div class="official-banner">
                <div class="official-banner-content">
                    <span class="official-flag">
                        <span class="flag-green"></span>
                        <span class="flag-yellow"></span>
                        <span class="flag-red"></span>
                    </span>
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

    .doc-banner {
        width: 100%;
        margin: 0 0 12px 0;
    }

    .doc-banner img {
        display: block;
        width: 100%;
        height: auto;
        /* garde les proportions */
        max-height: 120px;
        /* hauteur max du bandeau */
        object-fit: contain;
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
        min-height: 150mm;
        border: solid thin gray;
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
        /* max-width: 520px; */
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

    .edt-title-block {
        text-align: center;
        margin-bottom: 2mm;
    }

    .edt-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        letter-spacing: 0.03em;
        text-decoration: underline;
        text-decoration-style: double;
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
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: bold;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        color: rgb(37, 37, 37);
        font-weight: 600;
    }

    .meta-value {
        font-size: 15px;
        font-weight: 600;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        color: black;
    }

    .meta-hours .meta-value {
        font-weight: 600;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        color: black;
        font-size: 15px;
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
        background: rgb(206, 205, 205);
        color: black;
        font-weight: bolder;
        text-transform: uppercase;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        letter-spacing: 0.04em;
        padding: 2.5mm 1.5mm;
        text-align: center;
        font-size: 12px;
    }

    .col-time {
        width: 11%;
    }

    .col-day {
        width: 14.8%;
    }

    .cell-time {
        background: var(--slate-mid);
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        font-weight: 800;
        font-size: 13px;
        text-align: center;
        vertical-align: middle;
        padding: 1.5mm 1mm;
        color: rgb(29, 28, 28);
        white-space: nowrap;
    }

    .cell-time-container {
        display: inline-flex;
        align-items: center;
        vertical-align: middle;
        height: 100%;
    }

    .time-sep {
        color: black;
        margin: 0 1px;
    }

    .row-hour td {
        vertical-align: top;
    }

    .cell-empty {
        background: #FAFBFC;
    }

    .cell-slot {
        padding: 1mm;
        vertical-align: top;
    }

    .cell-slot.is-course {
        background: var(--course-bg);
        border: 1px solid var(--navy);
    }

    .cell-slot.is-ap {
        background: var(--ap-bg);
        border-color: #0d0d0e;
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
        font-weight: 900;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        font-size: 15px;
        color: black;
        line-height: 1.2;
    }

    .is-ap .slot-label {
        color: black;
    }

    .slot-detail {
        font-size: 9.5px;
        color: rgb(24, 21, 21);
        line-height: 1.2;
    }

    .slot-hours {
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        font-size: 11px;
        color: black;
        margin-top: auto;
    }

    .slot-badge-ap {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 18px;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        font-weight: 700;
        letter-spacing: 0.04em;
        color: rgb(0, 0, 0);
        background: #cdcdce;
        border: 1px solid #000000;
        border-radius: 2px;
        padding: 0 2px;
        line-height: 1.4;
    }

    td,
    th {
        border: 1px solid var(--navy);
    }

    /* Pauses */
    .row-break td {
        height: 5.5mm;
        border: 1px solid var(--navy);
        font-weight: 600;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .row-break-recreation td {
        background: rgb(184, 183, 183);
    }

    .row-break-lunch td {
        background: rgb(189, 189, 189);
    }

    .cell-break-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: black;
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
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: black;
        margin-bottom: 1mm;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans';
        text-decoration: underline;
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

