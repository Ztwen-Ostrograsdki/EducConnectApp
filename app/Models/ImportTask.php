<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportTask extends Model
{
    protected $connection = 'tenant';

    protected $table = 'import_tasks';


    protected $fillable = [
        'batch_id',
        'payload',
        'status',
        'task_name',
        'error',
        'attempts',
        'crud',
    ];



    protected $casts = [
        'payload' => 'array',
    ];
}
