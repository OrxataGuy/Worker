<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'project_id', 'parent_task_id', 'title', 'description', 'details', 'price', 'time', 'finished', 'bug',  'solution'];

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

    public function patch($description) {
        $patch_count = Task::where('parent_task_id', '=', $this->id)->where('bug', '=', 0)->count();
        $task = Task::create([
            'project_id' => $this->project_id,
            'title' => '[PARCHE #'.(++$patch_count).'] '.$this->title,
            'description' => $description,
            'details' => $this->details,
            'parent_task_id' => $this->id
        ]);
        return $task;
    }

    public function bug($description) {
        $bug_count = Task::where('parent_task_id', '=', $this->id)->where('bug', '=', 1)->count();
        $task = Task::create([
            'project_id' => $this->project_id,
            'title' => '[BUG #'.(++$bug_count).'] '.$this->title,
            'description' => $description,
            'details' => $this->details,
            'parent_task_id' => $this->id,
            'bug' => 1,
            'time' => ($this->time*(-0.5))
        ]);
        return $task;
    }

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function children() {
        return $this->hasMany(Task::class);
    }

    public function root() {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function advanced_tasks () {
        return $this->hasMany(AdvancedTask::class);
    }
}
