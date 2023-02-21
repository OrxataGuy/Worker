<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'project_id', 'client_id', 'amount', 'concept'];

    public function client() {
        $this->belongsTo(Client::class);
    }

    public function project() {
        $this->belongsTo(Project::class);
    }
}
