<?php

namespace App\Livewire\Central;

use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
use App\Models\Plan;
use App\Models\RequestToCreateNewTenant;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use App\Models\TenantStatistic;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Tableau de bord central")]
class CentralDashboard extends Component
{
    use CentralReloaderTrait, WireUiActions, WithPagination;

    public string $searchRequests = '';
    public string $statusFilter = 'all';

    #[Computed]
    public function kpis(): array
    {
        $activeSchools = Tenant::query()->count();

        $pendingRequests = RequestToCreateNewTenant::query()
            ->pending()
            ->count()
            + SubscriptionRequest::query()
                ->awaitingAction()
                ->count();

        $activeSubscriptions = Subscription::query()
            ->active()
            ->count();

        $suspendedSchools = RequestToCreateNewTenant::query()
            ->where('status', 'suspended')
            ->count();

        // Deltas simples (mois en cours vs mois précédent) – peut être affiné plus tard
        $thisMonthActive = Tenant::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthActive = Tenant::query()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $deltaSchools = $thisMonthActive - $lastMonthActive;
        $deltaSchoolsLabel = ($deltaSchools >= 0 ? '+' : '') . $deltaSchools;

        return [
            [
                'Écoles actives',
                (string) $activeSchools,
                $deltaSchoolsLabel,
                'building-2',
                'emerald',
            ],
            [
                'Demandes en attente',
                (string) $pendingRequests,
                '+' . max(0, $pendingRequests),
                'clipboard-list',
                'amber',
            ],
            [
                'Abonnements actifs',
                (string) $activeSubscriptions,
                '+' . $activeSubscriptions,
                'badge-check',
                'sky',
            ],
            [
                'Écoles suspendues',
                str_pad((string) $suspendedSchools, 2, '0', STR_PAD_LEFT),
                $suspendedSchools > 0 ? '-' . $suspendedSchools : '0',
                'shield-alert',
                'rose',
            ],
        ];
    }

    #[Computed]
    public function pendingsSubscriptionRequests()
    {
        $query = SubscriptionRequest::query()
            ->awaitingAction()
            ->with(['tenant', 'plan', 'treatedBy'])
            ->latest();

        if ($this->searchRequests !== '') {
            $search = '%' . $this->searchRequests . '%';
            $query->whereHas('tenant', function ($q) use ($search) {
                $q->where('id', 'like', $search)
                    ->orWhere('data->school_name', 'like', $search)
                    ->orWhere('data->name', 'like', $search);
            })->orWhereHas('plan', fn ($q) => $q->where('name', 'like', $search));
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->limit(15)->get();
    }

    #[Computed]
    public function activesSubscriptions()
    {
        return Subscription::query()
            ->active()
            ->with(['tenant', 'plan'])
            ->orderBy('expire_at')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function expiredsSubscriptions()
    {
        return Subscription::query()
            ->where(function ($q) {
                $q->where('status', 'expired')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'active')
                            ->where('expire_at', '<=', now());
                    });
            })
            ->with(['tenant', 'plan'])
            ->orderByDesc('expire_at')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function validatedsSubscriptionRequests()
    {
        return SubscriptionRequest::query()
            ->where('status', 'approved')
            ->with(['tenant', 'plan', 'treatedBy'])
            ->latest('treated_at')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function schools()
    {
        return Tenant::query()
            ->with(['statistics' => fn ($q) => $q->latest('last_synced_at')])
            ->latest()
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function pendingNewTenantRequests()
    {
        return RequestToCreateNewTenant::query()
            ->pending()
            ->latest()
            ->limit(15)
            ->get();
    }

    #[Computed]
    public function schoolsDistribution(): array
    {
        $labels = config('app.enseignement_types', []);
        $colors = ['emerald', 'sky', 'amber', 'violet', 'rose', 'cyan', 'indigo'];

        // Un seul appel groupé vers la base
        $counts = RequestToCreateNewTenant::query()
            ->whereIn('status', ['active', 'suspended'])
            ->selectRaw('enseignement_type, COUNT(*) as total')
            ->groupBy('enseignement_type')
            ->pluck('total', 'enseignement_type');

        $grandTotal = (int) $counts->sum();

        $result = [];
        $i = 0;

        // Parcourt tous les types définis dans la config
        foreach ($labels as $key => $label) {
            $count = (int) ($counts[$key] ?? 0);
            $percent = $grandTotal > 0
                ? (int) round(($count / $grandTotal) * 100)
                : 0;

            $result[] = [
                $label,
                $percent . '%',
                $colors[$i % count($colors)],
            ];
            $i++;
        }

        // Types présents en base mais absents de la config
        foreach ($counts as $key => $count) {
            if (array_key_exists($key, $labels)) {
                continue;
            }

            $count = (int) $count;
            $percent = $grandTotal > 0
                ? (int) round(($count / $grandTotal) * 100)
                : 0;

            $result[] = [
                ucfirst((string) $key),
                $percent . '%',
                $colors[$i % count($colors)],
            ];
            $i++;
        }

        return $result;
    }

    #[Computed]
    public function miniStats(): array
    {
        $tenantsCount = Tenant::query()->count();
        
        $usersApprox = TenantStatistic::query()->sum('students_count')
            + TenantStatistic::query()->sum('teachers_count')
            + TenantStatistic::query()->sum('parents_count');

        return [
            ['Tenants', (string) $tenantsCount],
            ['Utilisateurs', $this->formatCompact($usersApprox)],
            ['Connexions', '—'],
            ['Incidents', '0'],
        ];
    }

    protected function formatCompact(int|float $number): string
    {
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'k';
        }

        return (string) $number;
    }

    public function updatedSearchRequests(): void
    {
        // Recompute only the related computed property
        unset($this->pendingsSubscriptionRequests);
    }

    public function updatedStatusFilter(): void
    {
        unset($this->pendingsSubscriptionRequests);
    }

    public function render()
    {
        return view('livewire.central.central-dashboard');
    }
}