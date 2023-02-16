<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'project_id', 'title', 'description', 'details', 'price', 'time', 'finished', 'solution'];

    public function calculate() {
        if ($this->counting) {
            $this->last_run = $this->updated_at;
            $this->save();
            return ['-', '-'];
        }

        $start = new DateTime($this->last_run);
        $end = new DateTime($this->updated_at);
        $minutes = round(($end->getTimestamp() - $start->getTimestamp())/60);
        $this->time += $minutes;
        $this->price = $this->project->price_per_minute*$this->time;
        $this->save();
        $this->project->calculate();
        return [$this->time, $this->price];
    }

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function advanced_tasks () {
        return $this->hasMany(AdvancedTask::class);
    }
}
