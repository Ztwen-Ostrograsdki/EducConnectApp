<?php

namespace App\Models;


use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'days_count',
        'pack',
        'is_active',
        'order',
    ];

    protected $casts = [
        'price' => 'integer',
        'days_count' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $connection = 'central';

    protected $table = 'plans';


    public function subscriptionRequests(): HasMany
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('price');
    }

    /**
     * Labels lisibles des packs, réutilisés pour l'affichage.
     */
    public static function packLabels(): array
    {
        return [
            'starter' => 'Starter',
            'pro' => 'Pro',
            'premium' => 'Premium',
            'custom' => 'Sur mesure',
        ];
    }

    public function packLabel(): string
    {
        return self::packLabels()[$this->pack] ?? ucfirst($this->pack);
    }
}
