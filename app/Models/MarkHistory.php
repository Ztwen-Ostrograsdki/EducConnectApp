<?php

namespace App\Models;

use App\Models\Mark;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MarkHistory extends Model
{
    protected $table = 'mark_histories';

    protected $fillable = [
        'mark_id', 'editor_id', 'old_value', 'new_value',
        'reasons', 'authorized_by_director', 'authorized_by', 'authorized_at',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
        'authorized_by_director' => 'boolean',
        'authorized_at' => 'datetime',
    ];

    /**
     * Get the mark this history entry belongs to.
     */
    public function mark(): BelongsTo
    {
        return $this->belongsTo(Mark::class, 'mark_id');
    }

    /**
     * Get the user who edited the mark.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Get the director who authorized the edit.
     */
    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }
}
