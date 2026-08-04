<div class="print-wrapper">

    @foreach ($bulletins as $i => $b)
        @php
            $student = $b['student'];
            $classe = $b['classe'];
            $effectifs = $b['effectifs'];
            $term = $b['termAverage'];
        @endphp

        <div class="bulletin-page {{ $i > 0 ? 'page-break' : '' }}">

            {{-- ═══ EN-TÊTE OFFICIEL COMPACT ═══ --}}
            <div class="bulletin-header">
                <div class="bh-left">
                    <p class="bh-country">République du Bénin</p>
                    <p class="bh-ministry">Min. Enseign. Technique et de la Formation Professionnelle</p>
                </div>
                <div class="bh-center">
                    <h1 class="bh-school">{{ tenancy()->tenant->school_name }}</h1>
                    <p class="bh-year">Année scolaire {{ $schoolYear?->slug }}</p>
                </div>
                <div class="bh-right">
                    <p class="bh-badge">Bulletin — {{ $schoolYear?->periodLabel() }} {{ $period }}</p>
                    @if ($b['isLastPeriod'])
                        <p class="bh-badge-sub">Résultats annuels inclus</p>
                    @endif
                </div>
            </div>

            <div class="bulletin-rule"></div>

            {{-- ═══ IDENTITÉ ÉLÈVE ═══ --}}
            <div class="student-strip">
                <div class="ss-item ss-name">
                    <span class="ss-label">Élève</span>
                    <span class="ss-value">{{ $student->getFullName() }}</span>
                </div>
                <div class="ss-item">
                    <span class="ss-label">Matricule</span>
                    <span class="ss-value">{{ $student->matricule }}</span>
                </div>
                <div class="ss-item">
                    <span class="ss-label">Classe</span>
                    <span class="ss-value">{{ $classe->code ?: $classe->name }}</span>
                </div>
                <div class="ss-item">
                    <span class="ss-label">Sexe</span>
                    <span class="ss-value">{{ strtoupper(substr($student->gender ?? '', 0, 1)) }}</span>
                </div>
                <div class="ss-item">
                    <span class="ss-label">Né(e) le</span>
                    <span class="ss-value">{{ $student->birth_date ? __formatDate($student->birth_date) : '—' }}</span>
                </div>
                <div class="ss-item">
                    <span class="ss-label">Effectif classe</span>
                    <span class="ss-value">{{ $effectifs['apprenants'] ?? '—' }}</span>
                </div>
            </div>

            {{-- ═══ TABLEAU DES MATIÈRES ═══ --}}
            <table class="bulletin-table">
                <thead>
                    <tr>
                        <th class="col-subject">Matière</th>
                        <th>Coef</th>
                        <th>Moy Int</th>
                        @foreach ($b['devoirColumns'] as $type => $label)
                            <th>{{ $label }}</th>
                        @endforeach
                        <th>Moy</th>
                        <th>Moy×Coef</th>
                        <th>Rang</th>
                        <th class="col-teacher">Professeur</th>
                        <th class="col-mention">Mention</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($b['subjectsDetail'] as $row)
                        <tr>
                            <td class="col-subject">{{ $row['subjectName'] }}</td>
                            <td class="td-center">{{ $row['coefficient'] ?? '—' }}</td>
                            <td class="td-center">
                                {{ $row['moy_interro'] !== null ? number_format($row['moy_interro'], 2) : '—' }}</td>
                            @foreach ($b['devoirColumns'] as $type => $label)
                                <td class="td-center">
                                    {{ $row['marks'][$type] !== null ? number_format($row['marks'][$type], 2) : '—' }}
                                </td>
                            @endforeach
                            <td class="td-center cell-bold">
                                {{ $row['moy'] !== null ? number_format($row['moy'], 2) : '—' }}</td>
                            <td class="td-center">
                                {{ $row['moy_coef'] !== null ? number_format($row['moy_coef'], 2) : '—' }}</td>
                            <td class="td-center">{{ $row['rank'] ?? '—' }}</td>
                            <td class="col-teacher">{{ $row['teacherName'] }}</td>
                            <td class="col-mention">{{ $row['mention'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="tf-label">Moyenne générale</td>
                        <td colspan="2" class="tf-value">{{ $term['moyenne'] ?? '—' }}</td>
                        <td class="tf-label">Rang</td>
                        <td class="tf-value">{{ $term['rank'] ?? '—' }} / {{ $term['total'] ?? '—' }}</td>
                        <td class="tf-label">Mention</td>
                        <td colspan="3" class="tf-value">{{ $term['mention'] ?? '—' }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- ═══ SYNTHÈSE PÉRIODE (classe) ═══ --}}
            <div class="synth-strip">
                <div class="synth-item">
                    <span class="synth-label">Plus forte moyenne</span>
                    <span class="synth-value synth-good">{{ $term['premier']['moyenne'] ?? '—' }}</span>
                </div>
                <div class="synth-item">
                    <span class="synth-label">Plus faible moyenne</span>
                    <span class="synth-value synth-bad">{{ $term['dernier']['moyenne'] ?? '—' }}</span>
                </div>
                <div class="synth-item">
                    <span class="synth-label">Réussite classe</span>
                    <span
                        class="synth-value">{{ ($term['class_success_rate'] ?? null) !== null ? $term['class_success_rate'] . '%' : '—' }}</span>
                </div>
                <div class="synth-item">
                    <span class="synth-label">Réussite élève</span>
                    <span
                        class="synth-value">{{ ($term['success_percentage'] ?? null) !== null ? $term['success_percentage'] . '%' : '—' }}</span>
                </div>
            </div>

            {{-- ═══ RÉCAP PÉRIODES PRÉCÉDENTES (dernière période uniquement) ═══ --}}
            @if ($b['isLastPeriod'] && !empty($b['previousPeriodsRecap']))
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th class="col-subject">{{ $schoolYear?->periodLabel() }} précédent(s)</th>
                            <th>Moyenne</th>
                            <th>Rang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($b['previousPeriodsRecap'] as $p => $entry)
                            <tr>
                                <td class="col-subject">{{ $schoolYear?->periodLabel() }} {{ $p }}</td>
                                <td class="td-center">{{ $entry['moyenne'] ?? '—' }}</td>
                                <td class="td-center">{{ $entry['rank'] ?? '—' }} / {{ $entry['total'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- ═══ APPRÉCIATION + DÉCISION (dernière période) ═══ --}}
            <div class="bottom-blocks {{ $b['isLastPeriod'] ? 'has-decision' : '' }}">
                <div class="block-appreciation">
                    <p class="block-title">Appréciation</p>
                    <div class="block-blank-lines">
                        <div class="blank-line"></div>
                        <div class="blank-line"></div>
                    </div>
                </div>

                @if ($b['isLastPeriod'])
                    <div class="block-decision">
                        <p class="block-title">Décision du jury</p>
                        <div class="decision-line">
                            <span>Décision :</span>
                            <div class="blank-line-inline"></div>
                        </div>
                        <div class="decision-line">
                            <span>Moy. annuelle :</span>
                            <span class="decision-value">{{ $b['yearlyAverage']['moy_general'] ?? '—' }}</span>
                        </div>
                        <div class="decision-line">
                            <span>Rang annuel :</span>
                            <span class="decision-value">{{ $b['yearlyAverage']['rang_general'] ?? '—' }} /
                                {{ $b['yearlyClasseData']['total'] ?? '—' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══ SIGNATURE ═══ --}}
            <div class="signature-strip">
                <div class="sig-block">
                    <p class="sig-label">Le Professeur Principal</p>
                    <div class="sig-space"></div>
                </div>
                <div class="sig-block">
                    <p class="sig-label">Le Directeur</p>
                    <div class="sig-space"></div>
                </div>
            </div>

        </div>
    @endforeach

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
        --green: #166534;
        --red: #991B1B;
        --font-sans: 'Inter', 'Segoe UI', sans-serif;
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
        font-size: 10px;
    }

    .print-wrapper {
        width: 100%;
    }

    .bulletin-page {
        width: 100%;
        max-width: 780px;
        margin: 0 auto;
        padding: 14px 20px;
    }

    .page-break {
        page-break-before: always;
        break-before: page;
    }

    /* ── En-tête ── */
    .bulletin-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        padding-bottom: 6px;
    }

    .bh-left {
        flex: 1;
    }

    .bh-country {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        color: #EA580C;
    }

    .bh-ministry {
        font-size: 7.5px;
        color: var(--text-muted);
        margin-top: 2px;
        line-height: 1.3;
    }

    .bh-center {
        flex: 1.4;
        text-align: center;
    }

    .bh-school {
        font-size: 15px;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
    }

    .bh-year {
        font-size: 8.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .bh-right {
        flex: 1;
        text-align: right;
    }

    .bh-badge {
        font-size: 9px;
        font-weight: 700;
        color: var(--navy-mid);
    }

    .bh-badge-sub {
        font-size: 7.5px;
        color: var(--green);
        margin-top: 2px;
    }

    .bulletin-rule {
        height: 3px;
        background: linear-gradient(to right, var(--navy) 0%, var(--navy) 70%, var(--gold) 70%, var(--gold) 100%);
        margin-bottom: 8px;
        border-radius: 2px;
    }

    /* ── Bande identité élève ── */
    .student-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        background: var(--slate-mid);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 6px 8px;
        margin-bottom: 8px;
    }

    .ss-item {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 80px;
    }

    .ss-name {
        flex: 2;
        min-width: 160px;
    }

    .ss-label {
        font-size: 6.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
    }

    .ss-value {
        font-size: 9px;
        font-weight: 600;
        color: var(--text);
        margin-top: 1px;
    }

    /* ── Tableau matières ── */
    .bulletin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
        margin-bottom: 6px;
    }

    .bulletin-table thead tr {
        background: var(--navy);
    }

    .bulletin-table thead th {
        padding: 4px 3px;
        color: var(--white);
        font-weight: 600;
        font-size: 7px;
        text-transform: uppercase;
        text-align: center;
        border: 0.5px solid var(--navy-mid);
    }

    .bulletin-table .col-subject {
        text-align: left;
        width: 18%;
    }

    .bulletin-table .col-teacher {
        text-align: left;
        width: 14%;
    }

    .bulletin-table .col-mention {
        text-align: left;
        width: 8%;
    }

    .bulletin-table tbody td {
        padding: 3px;
        border: 0.5px solid var(--border);
        text-align: center;
        color: var(--text);
    }

    .bulletin-table tbody tr:nth-child(even) {
        background: #FAFBFC;
    }

    .cell-bold {
        font-weight: 700;
    }

    .bulletin-table tfoot td {
        padding: 4px 5px;
        border: 0.5px solid var(--border);
        background: var(--navy-light);
        font-size: 8px;
    }

    .tf-label {
        font-weight: 600;
        color: var(--navy);
        text-align: right;
    }

    .tf-value {
        font-weight: 700;
        color: var(--navy);
        text-align: center;
    }

    /* ── Bande synthèse classe ── */
    .synth-strip {
        display: flex;
        gap: 6px;
        margin-bottom: 6px;
    }

    .synth-item {
        flex: 1;
        text-align: center;
        border: 1px solid var(--border);
        border-radius: 5px;
        padding: 4px 2px;
    }

    .synth-label {
        display: block;
        font-size: 6.5px;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .synth-value {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: var(--navy);
        margin-top: 1px;
    }

    .synth-good {
        color: var(--green);
    }

    .synth-bad {
        color: var(--red);
    }

    /* ── Récap périodes précédentes ── */
    .recap-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
        margin-bottom: 6px;
    }

    .recap-table thead tr {
        background: var(--slate);
    }

    .recap-table thead th {
        padding: 3px;
        color: var(--white);
        font-size: 7px;
        text-transform: uppercase;
        border: 0.5px solid var(--slate);
    }

    .recap-table .col-subject {
        text-align: left;
    }

    .recap-table tbody td {
        padding: 3px;
        border: 0.5px solid var(--border);
        text-align: center;
    }

    /* ── Appréciation + décision ── */
    .bottom-blocks {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }

    .block-appreciation,
    .block-decision {
        flex: 1;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 6px 8px;
    }

    .bottom-blocks:not(.has-decision) .block-appreciation {
        flex: 1;
    }

    .block-title {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--navy);
        margin-bottom: 4px;
    }

    .block-blank-lines {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .blank-line {
        height: 0;
        border-bottom: 0.5px solid var(--border);
    }

    .decision-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 8px;
        margin-bottom: 4px;
    }

    .decision-value {
        font-weight: 700;
        color: var(--navy);
    }

    .blank-line-inline {
        flex: 1;
        height: 0;
        border-bottom: 0.5px solid var(--border);
        margin-left: 6px;
    }

    /* ── Signature ── */
    .signature-strip {
        display: flex;
        justify-content: space-around;
        margin-top: 10px;
    }

    .sig-block {
        text-align: center;
        width: 40%;
    }

    .sig-label {
        font-size: 8px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .sig-space {
        border-top: 0.5px solid var(--border);
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }

        body {
            background: white;
        }

        .print-wrapper {
            max-width: 100%;
        }

        .bulletin-page {
            max-width: 100%;
            padding: 0;
            page-break-inside: avoid;
        }
    }
</style>

