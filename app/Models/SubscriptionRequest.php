<?php

namespace App\Models;


use App\Models\CentralUser;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'transaction_id',
        'status',
        'reject_reason',
        'payment_reminder_sent_at',
        'treated_by',
        'treated_at',
    ];

    protected $casts = [
        'payment_reminder_sent_at' => 'datetime',
        'treated_at' => 'datetime',
    ];

    protected $connection = 'central';

    protected $table = 'subscription_requests';


    // ─── Relations ────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function treatedBy(): BelongsTo
    {
        return $this->belongsTo(CentralUser::class, 'treated_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopePaymentClaimed(Builder $query): Builder
    {
        return $query->where('status', 'payment_claimed');
    }

    public function scopeAwaitingAction(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'payment_claimed']);
    }

    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ─── Helpers d'état ───────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaymentClaimed(): bool
    {
        return $this->status === 'payment_claimed';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function canClaimPayment(): bool
    {
        return in_array($this->status, ['pending', 'payment_claimed'], true);
    }

    public function canBeActedOn(): bool
    {
        return in_array($this->status, ['pending', 'payment_claimed'], true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente de paiement',
            'payment_claimed' => 'Paiement signalé',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'payment_claimed' => 'sky',
            'approved' => 'emerald',
            'rejected' => 'rose',
            default => 'slate',
        };
    }
}
