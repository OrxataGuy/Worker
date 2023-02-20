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

    public function registerPayment() {
        $amount = 0;
        foreach($this->payments as $payment)
            $amount += $payment->amount;
        $this->paid = $amount;
        $this->save();
    }

    public function calculate() {
        $time = 0;
        foreach ($this->tasks as $task)
            $time += $task->time;
        $this->time = $time;
        $this->price = $this->price_per_minute*$time;
        $this->save();
    }

    public function tasks () {
        return $this->hasMany(Task::class);
    }

    public function payments () {
        return $this->hasMany(Payment::class);
    }

    public function client () {
        return $this->belongsTo(Client::class);
    }

    public function technologies() {
        return $this->belongsToMany(Technology::class, 'technology_project');
    }
}
