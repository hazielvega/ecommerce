<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'name',
        'last_name',
        'document_number',
        'email',
        'phone',
        'default',	
    ];

    // Relación con usuarios
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con órdenes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
