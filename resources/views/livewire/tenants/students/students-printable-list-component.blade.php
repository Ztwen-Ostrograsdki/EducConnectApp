{{--
    Vue Livewire : liste des enseignants — optimisée pour l'impression / PDF
    Composant : App\Livewire\Teachers\PrintTeacherList
    Layout : layouts.print (page blanche sans navigation)
--}}

<div class="print-wrapper">
    <div class="text-center mx-auto mt-2 px-3 border-2 border-gray-900 p-3 my-3">
        <h6 class="letter-spacing-2 flex flex-col items-center gap-y-1">
            <div class="text-sky-400 flex w-full">
                <span class="flex flex-col font-bold mx-auto">
                    <span class="uppercase text-orange-600">
                        République du Bénin
                    </span>
                    <span class="text-gray-800 text-sm">
                        Ministère de l'Enseignement Technique et de la Formation Professionnelle
                    </span>
                    <span class="mx-auto inline-block w-full mt-1">
                        <span class="w-full flex mx-auto ">
                            <span class="bg-green-500 inline-block p-0.5 w-1/3"></span>
                            <span class="bg-yellow-500 inline-block p-0.5 w-1/3"></span>
                            <span class="bg-red-600 inline-block p-0.5 w-1/3"></span>
                        </span>
                    </span>

                </span>
            </div>
        </h6>
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
                <h1 class="school-name uppercase font-semibold font-mono">{{ tenancy()->tenant->school_name }}</h1>
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
                <span class="stamp-ref">Réf : PERS-ENS-{{ now()->format('Ymd') }}-{{ str_pad($allStudents, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Ligne décorative tricolore --}}
        <div class="header-rule">
            <div class="rule-navy"></div>
            <div class="rule-gold"></div>
            <div class="rule-light"></div>
        </div>

        {{-- Titre du document --}}
        <div class="doc-title-block text-center">

            @if ($pdf_title)
                <h4 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" class="text-gray-900 my-0 uppercase letter-spacing-1 fas fa-2x">
                    {{ $pdf_title }}
                </h4>
            @endif

            {{-- Statistiques sommaires --}}

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
            <table class="students-table text-center truncate">
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-matricule">Matricule</th>
                        <th class="col-nom">Nom & Prénom</th>
                        <th class="col-nom">Père</th>
                        <th class="col-nom">Mère</th>
                        <th class="col-dept">Département / Matière</th>
                        <th class="col-grade">EducMaster</th>
                        <th class="col-contact">Contact</th>
                        <th class="col-recrutement">Date de recrutement</th>
                        <th class="col-statut">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                        <tr class="{{ $loop->even ? 'row-even' : 'row-odd' }}">

                            <td class="">{{ $loop->iteration }}</td>

                            <td class="font-semibold text-gray-800 text-sm">
                                <span class="">{{ $student->matricule ?? '—' }}</span>
                            </td>

                            <td class="">
                                <div class="">
                                    <div>
                                        <p class="name-full">{{ $student->getFullName() }}</p>
                                        @if ($student->roles)
                                            <p class="name-titre">{{ $student->myRoles() }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="col-grade">{{ $student->father_full_name }}</td>
                            <td class="col-grade">{{ $student->mother_full_name }}</td>

                            <td class="col-dept">
                                <p class="dept-main">{{ $student->city ?? '—' }}</p>
                                <p class="dept-matiere">{{ '-' }}</p>
                            </td>

                            <td class="col-grade">{{ $student->educMaster }}</td>

                            <td class="col-contact">
                                @if ($student->contacts)
                                    <p class="contact-line">{{ $student->contacts }}</p>
                                @endif
                                @if ($student->email)
                                    <p class="contact-line contact-email">{{ $student->email ?? 'non renseigné' }}</p>
                                @endif
                            </td>

                            <td class="col-recrutement td-center">
                                {{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->isoFormat('DD/MM/YYYY') : '—' }}
                            </td>

                            <td class="italic font-semibold">
                                <span class="statut-badge--{{ $student->status ?? 'inactif' }}">
                                    {{ ucfirst($student->status ? 'Actif' : 'Inactif') }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </main>

    {{-- ═══════════════════════════════════════════
         PIED DE PAGE
    ════════════════════════════════════════════ --}}

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

    /* ══ ENTÊTE ═════════════════════════════════ */
    .header-band {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        padding-bottom: 1.25rem;
    }

    /* Logo circulaire */
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

    /* Identité école */
    .school-identity {
        flex: 1;
    }

    .school-name {
        font-family: var(--font-serif);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--navy);
        letter-spacing: 0.02em;
        line-height: 1.2;
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

    /* Cachet date */
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

    /* Règle tricolore */
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

    /* Titre document */
    .doc-title-block {
        padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--navy);
    }

    .doc-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .doc-subtitle {
        display: block;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 3px;
    }

    /* Stat pills */
    .doc-stats {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .stat-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--navy-light);
        border: 1px solid #BFDBFE;
        border-radius: 20px;
        padding: 4px 14px 4px 10px;
    }

    .stat-pill--gold {
        background: var(--gold-light);
        border-color: #FDE68A;
    }

    .stat-pill--muted {
        background: var(--slate-mid);
        border-color: var(--border);
    }

    .stat-num {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        font-family: var(--font-mono);
    }

    .stat-pill--gold .stat-num {
        color: #92400E;
    }

    .stat-label {
        font-size: 0.73rem;
        color: var(--text-muted);
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
    }

    /* Largeurs colonnes */
    .col-num {
        width: 3%;
    }

    .col-matricule {
        width: 10%;
    }

    .col-nom {
        width: 21%;
    }

    .col-dept {
        width: 15%;
    }

    .col-grade {
        width: 10%;
    }

    .col-contact {
        width: 16%;
    }

    .col-recrutement {
        width: 12%;
    }

    .col-statut {
        width: 9%;
    }

    /* En-tête table */
    .students-table thead tr {
        background: var(--navy);
    }

    .students-table thead th {
        padding: 9px 8px;
        color: var(--white);
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 1px solid var(--navy-mid);
        vertical-align: middle;
    }

    .students-table thead th.td-center {
        text-align: center;
    }

    /* Lignes alternées */
    .students-table tbody tr.row-odd {
        background: var(--white);
    }

    .students-table tbody tr.row-even {
        background: var(--slate-mid);
    }

    .students-table tbody tr:hover {
        background: var(--navy-light);
    }

    /* Cellules */
    .students-table tbody td {
        padding: 8px 8px;
        border: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
        line-height: 1.45;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .td-center {
        text-align: center;
    }

    /* Matricule badge */
    .matricule-badge {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        background: var(--navy);
        color: var(--gold);
        padding: 2px 7px;
        border-radius: 3px;
        white-space: nowrap;
        letter-spacing: 0.05em;
    }

    /* Nom enseignant */
    .teacher-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .name-initials {
        flex-shrink: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--navy-light);
        border: 1.5px solid var(--navy-mid);
        color: var(--navy);
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0.03em;
    }

    .row-even .name-initials {
        background: var(--white);
    }

    .name-full {
        font-weight: 600;
        color: var(--navy);
        font-size: 0.82rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .name-titre {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Département */
    .dept-main {
        font-weight: 600;
        color: var(--text);
    }

    .dept-matiere {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    /* Contact */
    .contact-line {
        font-size: 0.75rem;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-email {
        color: var(--navy-mid);
    }

    /* Statut badge */
    .statut-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
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

    /* ══ PIED DE PAGE ═══════════════════════════ */
    .doc-footer {
        margin-top: 1.75rem;
    }

    .footer-rule {
        height: 2px;
        background: linear-gradient(to right, var(--navy) 60%, var(--gold) 80%, var(--navy-light) 100%);
        margin-bottom: 0.75rem;
        border-radius: 1px;
    }

    .footer-body {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        font-size: 0.73rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .footer-left {
        flex: 1;
    }

    .footer-confidential {
        margin-top: 3px;
        font-size: 0.68rem;
        color: #B45309;
        font-weight: 500;
    }

    .footer-right {
        text-align: right;
        flex-shrink: 0;
    }

    /* Cachet vide */
    .footer-seal {
        width: 80px;
        height: 80px;
        border: 2px dashed var(--border-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        flex-shrink: 0;
        margin: 0 auto;
    }

    .seal-text {
        font-size: 0.58rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--border-dark);
        font-weight: 600;
        line-height: 1.3;
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

        /* Numérotation auto des pages */
        .page-num::after {
            content: counter(page);
        }

        .print-wrapper {
            max-width: 100%;
            padding: 0;
        }

        /* Évite les coupures à l'intérieur d'une ligne */
        .students-table tbody tr {
            page-break-inside: avoid;
        }

        /* Répète l'entête de table sur chaque page */
        .students-table thead {
            display: table-header-group;
        }

        .students-table tfoot {
            display: table-footer-group;
        }

        /* Forcer les couleurs à l'impression */
        .students-table thead tr {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .row-even {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .statut-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .matricule-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .header-rule {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .rule-navy,
        .rule-gold,
        .rule-light {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Cacher les éléments non-imprimables si présents */
        .no-print {
            display: none !important;
        }

        td,
        th {
            text-align: center !important;
        }
    }
</style>

