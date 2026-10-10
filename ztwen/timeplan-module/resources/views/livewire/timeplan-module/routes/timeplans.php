<?php

use App\Livewire\TimePlans\ManageTimePlan;
use Illuminate\Support\Facades\Route;

// À inclure dans le groupe de routes tenant déjà protégé par le guard/middleware directeur.
Route::get('/director/time-plans', ManageTimePlan::class)
    ->name('director.time-plans.index');
