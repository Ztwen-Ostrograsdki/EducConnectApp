<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'subscription_request_id',
        'started_at',
        'expire_at',
        'status',
        'is_free',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expire_at' => 'datetime',
        'is_free' => 'boolean',
    ];

    protected $connection = 'central';

    protected $table = 'subscriptions';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('expire_at', '>', now());
    }

    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function isExpired(): bool
    {
        return $this->expire_at->isPast();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->expire_at, false));
    }
}
