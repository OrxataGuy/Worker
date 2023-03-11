<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'project_id', 'parent_task_id', 'title', 'description', 'details', 'price', 'time', 'finished', 'bug',  'solution'];

    public function reg() {
        $this->mins = $this->time;
        $this->secs = 0;
        $this->price = round($this->project->price_per_minute*$this->time, 2);
        $this->save();
        $this->project->calculate();
    }

    public function calculate() {
        if ($this->counting) {
            $this->last_run = $this->updated_at;
            $this->save();
            return [$this->getTime(), $this->price];
        }



        $start = (new DateTime($this->last_run))->getTimestamp();
        $end = (new DateTime($this->updated_at))->getTimestamp();

        $time = ($end - $start)/60;
        $this->time += $time;
        TaskTime::create([
            'task_id' => $this->id,
            'technology_id' => $this->workingOn,
            'time' => $time
        ]);
        $this->workingOn = null;
        if($this->time > 0) {
            $this->mins = (int)($this->time);
            $this->secs = ($this->time*60)%60;
        }
        $this->price = round($this->project->price_per_minute*$this->time, 2);
        $this->save();
        $this->project->calculate();
        return [$this->getTime(), $this->price];
    }

    public function patches() {
        return Task::where('parent_task_id', '=', $this->id)->where('bug', '=', 0)->count();
    }

    public function bugs() {
        return Task::where('parent_task_id', '=', $this->id)->where('bug', '=', 1)->count();
    }

    public function patch($description) {
        $original_task = $this;
        while($original_task->parent_task_id)
            $original_task = Task::find($original_task->parent_task_id);
        $patch_count = Task::where('parent_task_id', '=', $original_task->id)->where('bug', '=', 0)->count();
        $task = Task::create([
            'project_id' => $this->project_id,
            'title' => '[PARCHE #'.(++$patch_count).'] '.$original_task->title,
            'description' => $description,
            'details' => $original_task->details,
            'parent_task_id' => $original_task->id
        ]);
        return $task;
    }

    public function getTime() {
        $mins = strlen($this->mins) == 1 ? "0$this->mins" : $this->mins;
        $secs = strlen($this->secs) == 1 ? "0$this->secs" : $this->secs;

        return "$mins:$secs";
    }

    public function getCurrentTime() {
        $start = (new DateTime($this->last_run))->getTimestamp();
        $end = (new DateTime(now()))->getTimestamp();

        $time = $this->time + ($end - $start)/60;
        $this->mins = (int)($time);
        $this->secs = ($time*60)%60;
        $this->save();

        $mins = strlen($this->mins) == 1 ? "0$this->mins" : $this->mins;
        $secs = strlen($this->secs) == 1 ? "0$this->secs" : $this->secs;

        return "$mins:$secs";
    }

    public function bug($description) {
        $original_task = $this;
        while($original_task->parent_task_id)
            $original_task = Task::find($original_task->parent_task_id);
        $bug_count = Task::where('parent_task_id', '=', $original_task->id)->where('bug', '=', 1)->count();
        $task = Task::create([
            'project_id' => $this->project_id,
            'title' => '[BUG #'.(++$bug_count).'] '.$original_task->title,
            'description' => $description,
            'details' => $original_task->details,
            'parent_task_id' => $original_task->id,
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
