<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'client_id', 'name', 'description', 'price', 'time', 'price_per_minute'];

    public function getPercentStatus() {
        $taskCount = $this->tasks->count();
        $taskComplete = $this->tasks->where('finished', 1)->count();
        return $taskComplete*100/$taskCount;
    }

    public function tasks () {
        return $this->hasMany(Task::class);
    }

    public function client () {
        return $this->belongsTo(Client::class);
    }
}
