<?php

namespace App\Contracts;

use App\Securities\AuthorizationResponse;

interface AuthorizationRule
{
    public function handle(mixed $model) : AuthorizationResponse;
}