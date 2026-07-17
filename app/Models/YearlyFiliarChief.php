<?php

namespace App\Models;

use App\Traits\InvalidatesFiliarDetailsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YearlyFiliarChief extends Model
{
    use InvalidatesFiliarDetailsCache;
    
    protected $table = 'yearly_filiar_chiefs';

    protected $connection = 'tenant';

    protected $fillable = [
        'filiar_id', 'teacher_id', 'school_year_id', 'is_active', 'is_master', 'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_master' => 'boolean',
    ];


    /**
     * Get the teacher this access belongs to.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the student this access is linked to.
     */
    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id');
    }

    /**
     * Get the school year this access belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

}
