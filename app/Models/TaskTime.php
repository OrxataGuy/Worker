<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTime extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'task_id', 'technology_id', 'time'];

}
