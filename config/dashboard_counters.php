<?php

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\Serial;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\User;
use App\Models\YearlyClasseStudent;

return [
    'students' => [
        'model' => Student::class,
        'ttl' => 3600,
    ],
    'students_in_classe' => [
        'model' => YearlyClasseStudent::class,
        'conditions' => ['is_active' => true, 'ended_at' => null],
        'current_school_year' => true, // flag au lieu d'une valeur résolue
        'ttl' => 3600,
    ],
    'teachers' => [
        'model' => Teacher::class,
        'ttl' => 3600,
    ],
    'teachers_in_classes' => [
        'model' => ClasseSubjectOfSchoolYear::class,
        'conditions' => ['is_active' => true, 'ended_at' => null],
        'current_school_year' => true,
        'ttl' => 3600,
    ],
    'classes_actives' => [
        'model' => Classe::class,
        'conditions' => ['is_active' => true],
        'current_school_year' => true,
        'ttl' => 1800,
    ],
    'classes_closeds' => [
        'model' => Classe::class,
        'conditions' => ['is_locked' => true],
        'current_school_year' => true,
        'ttl' => 1800,
    ], 
    'classes_unactives' => [
        'model' => Classe::class,
        'conditions' => ['is_active' => false],
        'current_school_year' => true,
        'ttl' => 1800,
    ],
    'promotions_actives' => [
        'model' => Promotion::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],
    'filiars_actives' => [
        'model' => Filiar::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],
    'serials_actives' => [
        'model' => Serial::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],
    'parents' => [
        'model' => Tutor::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],
    'tutors' => [
        'model' => Tutor::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],

    'users' => [
        'model' => User::class,
        'ttl' => 1800,
    ],
    
    'users_blockeds' => [
        'model' => User::class,
        'conditions' => ['blocked' => true],
        'ttl' => 1800,
    ],
    'users_without_roles' => [
        'model' => User::class,
        'roles' => ['has' => false, 'roles' => []],
        'ttl' => 1800,
    ],
    'users_tutors' => [
        'model' => User::class,
        'roles' => ['has' => true, 'roles' => ['tuteur']],
        'ttl' => 1800,
    ],
    'users_teachers' => [
        'model' => User::class,
        'roles' => ['has' => true, 'roles' => ['enseignant']],
        'ttl' => 1800,
    ],
    
];