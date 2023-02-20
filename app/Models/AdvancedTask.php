<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedTask extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'task_id', 'document_id', 'description'];

    public function task () {
        return $this->belongsTo(Task::class);
    }

    public function document () {
        return $this->belongsTo(Document::class);
    }
}
