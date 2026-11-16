{{-- resources/views/livewire/tenants/reports/partials/bulletins-new-system-section.blade.php
     Variables : $b (bulletin new_system), $schoolYear --}}

@php
    $labels = [
        'validated' => 'Validé',
        'not_validated' => 'Non validé',
        'not_graded' => 'Non évalué',
    ];
    $summary = $b['summary'];
    $decision = $b['decision'];
@endphp

{{-- ═══ TABLEAU DES MODULES ═══ --}}
<table class="bulletin-table ns-table">
    <thead>
        <tr>
            <th class="col-subject">Module</th>
            <th class="col-teacher">Professeur</th>
            <th class="col-mention">Résultat</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($b['modules'] as $row)
            <tr>
                <td class="col-subject">{{ $row['moduleName'] }}</td>
                <td class="col-teacher">{{ $row['teacherName'] }}</td>
                <td class="col-mention ns-{{ str_replace('_', '-', $row['status']) }}">
                    {{ $labels[$row['status']] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="td-center">Aucun module pour cette classe</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td class="tf-label">Modules validés</td>
            <td class="tf-value">{{ $summary['validated'] }} / {{ $summary['total'] }}</td>
            <td class="tf-value">{{ $summary['percentage'] }}%</td>
        </tr>
    </tfoot>
</table>

{{-- ═══ APPRÉCIATION + DÉCISION (dernière période uniquement) ═══ --}}
<div class="bottom-blocks {{ $b['isLastPeriod'] ? 'has-decision' : '' }}">
    <div class="block-appreciation">
        <p class="block-title">Appréciation</p>
        <div class="block-blank-lines">
            <div class="blank-line"></div>
            <div class="blank-line"></div>
        </div>
    </div>

    @if ($b['isLastPeriod'] && $decision)
        <div class="block-decision">
            <p class="block-title">Décision du jury</p>
            <div class="decision-line">
                <span>Décision :</span>
                <span class="decision-value {{ $decision['admitted'] ? 'ns-validated' : 'ns-not-validated' }}">
                    {{ $decision['admitted'] ? 'ADMIS(E)' : 'NON ADMIS(E)' }}
                </span>
            </div>
            <div class="decision-line">
                <span>Modules validés :</span>
                <span class="decision-value">{{ $summary['validated'] }} / {{ $summary['total'] }}
                    ({{ $summary['percentage'] }}%)</span>
            </div>
            <div class="decision-line">
                <span>Taux requis :</span>
                <span class="decision-value">{{ $decision['required'] }}%</span>
            </div>
        </div>
    @endif
</div>

{{-- À AJOUTER dans le <style> du template principal --}}
{{--
    .ns-table .col-subject { width: 45%; }
    .ns-table .col-teacher { width: 35%; }
    .ns-table .col-mention { width: 20%; font-weight: 700; }
    .ns-validated     { color: var(--green); }
    .ns-not-validated { color: var(--red); }
    .ns-not-graded    { color: var(--text-muted); font-style: italic; }
--}}
