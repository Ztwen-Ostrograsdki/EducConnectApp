<?php

namespace App\Models;

use App\Helpers\Support\TenantStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prenames',
        'birth_date',
        'contacts',
        'profil_photo',
        'title',
        'description',
        'school_year_id',
        'is_active',
        'hidden',
        'gender',
        'grade',
        'since',
        'ended_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'since'      => 'date',
        'ended_at'   => 'date',
        'is_active'  => 'boolean',
        'hidden'     => 'boolean',
    ];

    protected $connection = 'tenant';

    protected $table = 'personnels';

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * ------------------------------------------------------------
     *  SCOPES
     * ------------------------------------------------------------
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeGender(Builder $query, ?string $gender): Builder
    {
        return $query->when($gender, fn ($q) => $q->where('gender', $gender));
    }

    /**
     * ------------------------------------------------------------
     *  ACCESSORS
     * ------------------------------------------------------------
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    public function getFullName(bool $reverse = false)
    {
        if(!$reverse) return  $this->name . ' ' . $this->prenames;

        else  return $this->prenames . ' ' . $this->name;
    }


    public function getUserNamePrefix(bool $withFullName = false, bool $reverseName = false)
    {
        $prefix = 'Mr/Mme';

        if(in_array($this->gender, ['male', 'Male', 'M', 'm', 'masculin', 'Masculin'])) $prefix = 'Mr';

        if(in_array($this->gender, ['female', 'Female', 'F', 'f', 'feminin', 'Féminin', 'Feminin'])) $prefix = 'Mme';

        if($withFullName) return $prefix . ' ' . $this->getFullName($reverseName);

        return $prefix;
    }

    public function greating(bool $withFullName = true, bool $reverse = false)
    {
        $name = $this->getUserNamePrefix($withFullName, $reverse);

        $hour = date('G');
        
        if($hour >= 0 && $hour <= 12){

            $greating = "Bonjour ";
        }
        else{

            $greating = "Bonsoir ";
        }

        return $name  ? $greating . ' ' . $name : $greating;
    }

    public function getProfilPhotoUrlAttribute(): ?string
    {
       if($this->profil_photo)  return TenantStorage::url( $this->profil_photo);

       else return asset('images/default-avatar.jpg') ;
    }

    public function getIsCurrentlyEmployedAttribute(): bool
    {
        return $this->is_active && is_null($this->ended_at);
    }
}