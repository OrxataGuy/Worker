<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'client_id', 'project_id', 'task_id', 'payment_id', 'description'];


    public static function publish($content, $users) : Log {
        $log = Log::create($content);
        return $log;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}
