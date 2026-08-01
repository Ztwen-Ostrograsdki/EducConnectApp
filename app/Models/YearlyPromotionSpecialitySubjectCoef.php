<?php

namespace App\Models;

use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Traits\InvalidatesClasseAveragesCacheForAllPeriods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class YearlyPromotionSpecialitySubjectCoef extends Model
{
    use InvalidatesClasseAveragesCacheForAllPeriods;

    
    protected $fillable = ['coef', 'subject_id', 'filiar_id', 'promotion', 'serial_id', 'school_year_id', 'uuid'];


    protected $table = 'yearly_promotion_speciality_subject_coefs';

    protected $connection = 'tenant';

    protected $casts = ['coef' => 'integer'];

    /**
     * Get the student this access is linked to.
     */
    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id');
    }

    /**
     * Get the student this access is linked to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the student this access is linked to.
     */
    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class, 'serial_id');
    }

    /**
     * Get the school year this access belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion');
    }
}
