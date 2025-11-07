<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'email',
        'phone',
        'staff_id',
        'last_contact',
        'next_follow_up',
        'status',
        'revenue',
        'vessel_name',
        'description',
        'remark',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
