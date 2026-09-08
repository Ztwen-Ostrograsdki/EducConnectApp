<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use App\Models\TenantModuleAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Override;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'subscription_request_id',
        'started_at',
        'expire_at',
        'status',
        'key',
        'is_free',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expire_at' => 'datetime',
        'is_free' => 'boolean',
        'key' => 'string',
    ];

    protected $connection = 'central';

    protected $table = 'subscriptions';

    #[Override]
    protected static function booted()
    {
        static::creating(function($model){

            $model->key = (string) str()->upper(Str::random(10));

        });
    }

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

    public function moduleAccess(): HasOne
    {
        return $this->hasOne(TenantModuleAccess::class);
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

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isActiveStatus(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Abonnement utilisable maintenant (status active, période en cours).
     */
    public function isCurrentlyRunning(): bool
    {
        return $this->status === 'active'
            && $this->started_at
            && $this->started_at->lte(now())
            && $this->expire_at
            && $this->expire_at->isFuture();
    }

    /**
     * Abonnement en file d'attente (pas encore démarré).
     */
    public function isQueued(): bool
    {
        return in_array($this->status, ['active', 'suspended'], true)
            && $this->started_at
            && $this->started_at->isFuture()
            && ! $this->isExpired();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->expire_at, false));
    }
    
    public function progress(): float
    {
        $startedAt = $this->started_at;

        $daysCount = ceil($startedAt->diffInDays($this->expire_at, false));

        $remainingDays = $this->daysRemaining();

        $progress = ($remainingDays / $daysCount) * 100;

        return ceil($progress);
    }
}
