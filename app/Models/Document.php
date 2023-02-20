<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'type', 'url'];

    public function advanced_tasks() {
        return $this->hasMany(AdvancedTask::class);
    }

}
