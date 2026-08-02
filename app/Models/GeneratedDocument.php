<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedDocument extends Model
{
    protected $connection = 'tenant';


    protected $table = 'generated_documents';

    protected $fillable = [
        'type',
        'filename',
        'downloaded',
        'downloaded_count',
        'downloadable_by_others',
        'for_teachers',
        'for_teacher_id',
        'for_parent_id',
        'for_parents',
        'path',
        'url',
        'user_id',
        'classe_id',
        'filiar_id',
        'serial_id',
        'promotion_id',
        'promotionsGrouped',
        'tenant_id',
    ];

    protected $casts = [
        'downloaded'              => 'boolean',
        'downloadable_by_others'  => 'boolean',
        'for_parents'  => 'boolean',
        'for_teachers'  => 'boolean',
        'downloaded_count'        => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePublic($query)
    {
        return $query->where('downloadable_by_others', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Incrémente le compteur et marque comme téléchargé.
     */
    public function recordDownload(): void
    {
        $this->increment('downloaded_count');

        if (! $this->downloaded) {
            $this->update(['downloaded' => true]);
        }

        
    }
}
