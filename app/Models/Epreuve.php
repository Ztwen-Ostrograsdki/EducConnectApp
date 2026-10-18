<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Epreuve extends Model
{
    use SoftDeletes;

    protected $table = 'epreuves';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'filename',
        'path',
        'classe_name',
        'promotion_id',
        'filiar_id',
        'classe_id',
        'serial_id',
        'subject_id',
        'school_year_id',
        'teacher_name',
        'author_id',
        'period',
        'period_as_label',
        'type',
        'downloaded',
        'downloaded_count',
        'lot',
        'downloadable_by_others',
        'school_year_slug',
        'published',
        'locked',
    ];

    protected $casts = [
        'published'              => 'boolean',
        'downloaded'             => 'boolean',
        'downloadable_by_others' => 'boolean',
        'locked'                 => 'boolean',
        'downloaded_count'       => 'integer',
        'period'                 => 'integer',
        'lot'                    => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class, 'serial_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeByYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeSubject(Builder $query, int $subjectId): Builder
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByLot(Builder $query, int $lot): Builder
    {
        return $query->where('lot', $lot);
    }

    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('author_id', $userId);
    }

    public function scopeLocked(Builder $query): Builder
    {
        return $query->where('locked', true);
    }

    public function scopeUnlocked(Builder $query): Builder
    {
        return $query->where('locked', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function recordDownload(): void
    {
        $this->increment('downloaded_count');

        if (! $this->downloaded) {
            $this->update(['downloaded' => true]);
        }
    }

    public function isLocked(): bool
    {
        return (bool) $this->locked;
    }

    public function isDeletableByOwner(): bool
    {
        return ! $this->isLocked();
    }
}
