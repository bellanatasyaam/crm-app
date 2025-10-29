<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'client_name',
        'email',
        'phone',
        'staff',
        'last_contact',
        'next_follow_up',
        'status',
        'revenue',
        'vessel_name',
        'remark',
        'staff_id', // 🔥 tambahkan ini
    ];

    // 🔗 Relasi ke User (staff)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
