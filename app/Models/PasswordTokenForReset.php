<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class PasswordTokenForReset extends Model
{
    use TenantConnection;

    protected $connection = 'tenant';


    protected $fillable = [
        'token',
        'email',
        'used_at',
        'otp_code',
        'attempts',
        'expires_at',
    ];


    protected $casts = [
        'expires_at'  => 'datetime',
        'verified_at' => 'datetime',
        'used_at'     => 'datetime',
    ];


}
