<div class="w-full max-w-[1300px] mx-auto px-10 pt-8 pb-6 bg-white">

    {{-- ═══ BANDEAU OFFICIEL ═══ --}}
    <div class="w-full mx-auto mb-4 px-5 py-2.5 text-center">
        <div class="flex flex-col items-center gap-[3px]">
            <span class="font-bold uppercase text-orange-600 text-[0.85rem] tracking-wide">
                République du Bénin
            </span>
            <span class="text-[0.78rem] text-black">
                Ministère de l'Enseignement Technique et de la Formation Professionnelle
            </span>
            <span class="flex w-full mt-1">
                <span class="inline-block flex-1 h-1.5 bg-green-500"></span>
                <span class="inline-block flex-1 h-1.5 bg-yellow-500"></span>
                <span class="inline-block flex-1 h-1.5 bg-red-600"></span>
            </span>
        </div>
    </div>

    {{-- ═══ ENTÊTE INSTITUTIONNEL ═══ --}}
    <header>
        <div class="flex items-start gap-6 pb-5">
            <div class="relative shrink-0 w-[72px] h-[72px]">
                <span
                    class="flex items-center justify-center w-[72px] h-[72px] bg-navy rounded-full text-gold font-serif text-2xl font-bold tracking-wide border-[3px] border-gold">
                    {{ str()->initials(tenancy()->tenant->school_name) }}
                </span>
                <span
                    class="absolute bottom-0.5 right-0.5 w-3.5 h-3.5 bg-gold rounded-full border-2 border-white"></span>
            </div>

            <div class="flex-1">
                <h1 class="font-mono text-2xl font-bold text-navy tracking-wide leading-tight uppercase">
                    {{ tenancy()->tenant->school_name }}
                </h1>
                <p class="text-[0.8rem] text-gold uppercase tracking-widest mt-0.5 font-semibold">
                    Enseignement {{ tenancy()->tenant->enseignement_type }}
                </p>
                <p class="mt-2 text-[0.82rem] text-black leading-relaxed">
                    Établissement : <strong class="text-black">{{ tenant('school_type') ?? 'Lycée Example' }}</strong>
                    &nbsp;•&nbsp;
                    Adresse : {{ tenant('adresse') ?? '-' }}
                    &nbsp;•&nbsp;
                    Contact : {{ tenant('contacts') ?? '-' }}
                </p>
            </div>

            <div class="shrink-0 text-right flex flex-col gap-0.5">
                <span class="text-[0.65rem] uppercase tracking-widest text-gold font-bold">Document officiel</span>
                <span class="text-[0.78rem] text-black">{{ $printed_at }}</span>
                <span
                    class="font-mono text-[0.7rem] text-black bg-slate-100 px-2 py-0.5 rounded border border-slate-300 mt-1">
                    Réf : PERS-ENS-{{ now()->format('Ymd') }}-{{ str_pad($allStudents, 3, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>

        <div class="flex h-1.5 rounded overflow-hidden mb-5">
            <div class="flex-[4] bg-navy"></div>
            <div class="flex-1 bg-gold"></div>
            <div class="flex-1 bg-navy-light border border-slate-300"></div>
        </div>

        @if ($pdf_title)
            <div class="pb-5 border-b-2 border-navy text-center">
                <h4 class="text-xl font-bold text-navy uppercase tracking-wider">
                    {{ $pdf_title }}
                </h4>
            </div>
        @endif
    </header>

    {{-- ═══ TABLEAU ═══ --}}
    <main class="mt-5">
        @if ($students->isEmpty())
            <div class="p-8 text-center text-black border border-dashed border-slate-300 rounded-md mt-4">
                <p>Aucun apprenant à afficher pour les critères sélectionnés.</p>
            </div>
        @else
            @php
                $columns = $tableColumns ?: $defaultColumns;
                $widths = \App\Livewire\Tenants\Students\StudentsPrintableListComponent::normalizedWidths($columns);
            @endphp

            <table class="w-full border-collapse text-[0.8rem] table-fixed break-inside-avoid">
                <colgroup>
                    <col style="width:4%">
                    @foreach ($columns as $col)
                        <col style="width: {{ $widths[$col['key']] ?? 'auto' }}">
                    @endforeach
                </colgroup>

                <thead>
                    <tr class="bg-navy">
                        <th
                            class="px-1.5 py-2.5 text-white font-semibold text-[0.72rem] uppercase tracking-wide border border-navy-mid align-middle text-center">
                            #
                        </th>
                        @foreach ($columns as $col)
                            <th
                                class="px-1.5 py-2.5 text-white font-semibold text-[0.72rem] uppercase tracking-wide border border-navy-mid align-middle text-center">
                                {{ $col['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($students as $student)
                        <tr class="break-inside-avoid {{ $loop->even ? 'bg-slate-100' : 'bg-white' }}">
                            <td class="px-1.5 py-[7px] border border-slate-300 align-middle text-center text-black">
                                {{ $loop->iteration }}
                            </td>

                            @foreach ($columns as $col)
                                <td
                                    class="px-1.5 py-[7px] border border-slate-300 align-middle break-words whitespace-normal text-black
                                    {{ $col['key'] === 'full_name' ? 'text-left' : 'text-center' }}">
                                    @if ($col['key'] === 'full_name')
                                        <div class="flex flex-col items-start">
                                            <span class="font-semibold text-black text-[0.8rem]">
                                                {!! \App\Livewire\Tenants\Students\StudentsPrintableListComponent::getData($student, $col) !!}
                                            </span>
                                            <span class="font-mono text-[0.68rem] font-normal text-gray-600 mt-0.5">
                                                {{ $student->matricule }}
                                            </span>
                                        </div>
                                    @elseif ($col['key'] === 'classe.name')
                                        {!! \App\Livewire\Tenants\Students\StudentsPrintableListComponent::getData($student, $col) ?:
                                            '<span class="text-[0.72rem] text-gray-600 italic">Pas de classe</span>' !!}
                                    @elseif ($col['key'] === 'contacts')
                                        <div class="text-[0.76rem] leading-snug break-all text-black">
                                            {!! \App\Livewire\Tenants\Students\StudentsPrintableListComponent::getData($student, $col) !!}
                                            @if ($student->email)
                                                <p class="text-navy-mid text-[0.7rem]">{{ $student->email }}</p>
                                            @endif
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

{{-- Règles impossibles à exprimer en Tailwind utility classes --}}
<style>
    @page {
        size: A4 landscape;
        margin: 14mm 12mm 16mm 12mm;
    }

    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    @media print {
        thead {
            display: table-header-group;
        }
    }
</style>
