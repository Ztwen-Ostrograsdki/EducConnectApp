<div class="notes-share-wrapper">

    <div class="ns-header">
        <p class="ns-school">{{ tenancy()->tenant->school_name }}</p>
        <p class="ns-meta">{{ $schoolYear?->periodLabel() }} {{ $period }} — {{ $printed_at }}</p>
    </div>

    <div class="ns-rule"></div>

    <div class="ns-student-block">
        <p class="ns-student-name">{{ $student->getFullName() }}</p>
        <p class="ns-student-meta">
            Classe : {{ $classe?->code ?: $classe?->name ?: '—' }}
            &nbsp;•&nbsp;
            Matricule : {{ $student->matricule }}
        </p>
    </div>

    <p class="ns-title">{{ $pdf_title }}</p>

    @if (empty($marksData))
        <div class="ns-empty">
            <p>Aucune note disponible pour cette sélection.</p>
        </div>
    @else
        @foreach ($marksData as $subjectBlock)
            <div class="ns-subject-block">
                <p class="ns-subject-name">{{ $subjectBlock['subjectName'] }}</p>
                <table class="ns-table">
                    <tbody>
                        @foreach ($subjectBlock['rows'] as $row)
                            <tr>
                                <td class="ns-type">{{ $row['label'] }}</td>
                                <td class="ns-value">{{ number_format($row['value'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif

    <div class="ns-footer">
        <p>Document informatif — ne remplace pas le bulletin officiel.</p>
        <p>Ce fichier vous permet de suivre les performances de votre enfant.</p>
    </div>

</div>

<style>
    :root {
        --navy: #1E3A5F;
        --navy-mid: #2C5282;
        --gold: #C9A84C;
        --slate-mid: #F1F5F9;
        --text: #000000;
        --text-muted: #4B5563;
        --border: #CBD5E1;
        --white: #FFFFFF;
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
        font-size: 11px;
    }

    .notes-share-wrapper {
        width: 100%;
        max-width: 380px;
        margin: 0 auto;
        padding: 14px 16px;
    }

    .ns-header {
        text-align: center;
        margin-bottom: 6px;
    }

    .ns-school {
        font-size: 13px;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
    }

    .ns-meta {
        font-size: 9px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .ns-rule {
        height: 2px;
        background: linear-gradient(to right, var(--navy) 0%, var(--navy) 70%, var(--gold) 70%, var(--gold) 100%);
        margin-bottom: 8px;
        border-radius: 2px;
    }

    .ns-student-block {
        background: var(--slate-mid);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 6px 8px;
        margin-bottom: 8px;
        text-align: center;
    }

    .ns-student-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
    }

    .ns-student-meta {
        font-size: 9px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .ns-title {
        font-size: 10px;
        font-weight: 600;
        color: var(--navy-mid);
        text-align: center;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .ns-subject-block {
        margin-bottom: 10px;
    }

    .ns-subject-name {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--white);
        background: var(--navy);
        padding: 4px 8px;
        border-radius: 4px 4px 0 0;
    }

    .ns-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .ns-table tr {
        border-bottom: 1px solid var(--border);
    }

    .ns-table tr:last-child {
        border-bottom: 1px solid var(--border);
    }

    .ns-type {
        padding: 4px 8px;
        color: var(--text-muted);
    }

    .ns-value {
        padding: 4px 8px;
        text-align: right;
        font-family: var(--font-mono);
        font-weight: 700;
        color: var(--navy);
    }

    .ns-empty {
        text-align: center;
        padding: 20px 10px;
        color: var(--text-muted);
        font-style: italic;
        font-size: 10px;
    }

    .ns-footer {
        margin-top: 12px;
        text-align: center;
        font-size: 8px;
        color: var(--text-muted);
        font-style: italic;
    }

    @media print {
        @page {
            size: A5 portrait;
            margin: 8mm;
        }

        body {
            background: white;
        }
    }
</style>

