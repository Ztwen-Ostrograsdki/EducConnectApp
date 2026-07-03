<?php

use App\Models\Classe;
use App\Models\Student;
use App\Models\Teacher;

return [
    'students_total' => [
        'model' => Student::class,
        'ttl' => 3600, // 1h, filet de sécurité derrière l'invalidation
    ],
    'teachers_total' => [
        'model' => Teacher::class,
        'ttl' => 3600,
    ],
    'classes_active' => [
        'model' => Classe::class,
        'conditions' => ['is_active' => true],
        'ttl' => 1800,
    ],
    // ...
];