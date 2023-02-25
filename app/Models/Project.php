<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'client_id', 'name', 'description', 'price', 'time', 'price_per_minute'];

    public function getPercentStatus() {
        $taskCount = $this->tasks->count();
        $taskComplete = $this->tasks->where('finished', 1)->count();
        return round($taskComplete*100/$taskCount,2);
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

    public function attachPlatforms(Request $request) {
        if($request->has('platform'))
            $this->technologies()->attach($request->get('platform'));
        else {
            if($request->has('backend')) $this->technologies()->attach($request->get('backend'));
            else $this->technologies()->attach(Technology::where('icon', 'like', '%none.png')->where('context', '=', 'BACKEND')->first()->id);
            if($request->has('database')) $this->technologies()->attach($request->get('database'));
            else $this->technologies()->attach(Technology::where('icon', 'like', '%none.png')->where('context', '=', 'DATABASE')->first()->id);
            if($request->has('frontend')) $this->technologies()->attach($request->get('frontend'));
            else $this->technologies()->attach(Technology::where('name', '=', 'HTML')->first()->id);
        }

        if($request->has('devops'))
            foreach($request->get('devops') as $d)
                $this->technologies()->attach($d);
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
