<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'email', 'phone', 'user_id'];

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function payments () {
        return $this->hasMany(Payment::class);
    }

    public function logs () {
        return $this->hasMany(Log::class);
    }
}
