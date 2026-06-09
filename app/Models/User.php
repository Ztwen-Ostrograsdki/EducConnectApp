<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Support\TenantStorage;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{

    protected $fillable = [
        'name',
        'prenames',
        'job_name',
        'country',
        'city',
        'department',
        'email',
        'password',
        'profil_photo',
        'school_name',
        'school_devise',
        'contacts',
        'is_super_admin',
        'tenant_id',
        'email_verified_at',
        'uuid',
        'adresse',
        'logged_count',
        'cannot_edit_classes',
        'blocked',
        'birth_date',
        'birth_place',
        'gender'
    ];


    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected string $guard_name = 'tenant';

    protected $appends = [
        'profil_photo_url'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date',
            'password' => 'hashed',
            'cannot_edit_classes' => 'boolean',
            'blocked' => 'boolean',
        ];
    }


    public function emailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function emailNotVerified()
    {
        return is_null($this->email_verified_at);
    }

    public function markAsVerified()
    {
        if($this->emailNotVerified()){
    
            $this->forceFill([
                'email_verify_key' => null,
                'email_verified_at' => now(),
            ])->setRememberToken(Str::random(60));
 
            $this->save();

        }

        return $this->emailVerified();
    }

    public function markAsNotVerified()
    {
        $email_verify_key = generateRandomNumber(6);

        if(!$this->emailNotVerified()){
    
            $this->forceFill([
                'email_verify_key' => $email_verify_key,
                'email_verified_at' => null,
                'remember_token' => null,
            ]);
 
            $this->save();

        }

        return $this->emailNotVerified();
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


    public function isSuperAdmin(): bool
    {
        return 0;
    }
}
