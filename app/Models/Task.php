<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'project_id', 'title', 'description', 'details', 'price', 'time', 'finished', 'solution'];
}
