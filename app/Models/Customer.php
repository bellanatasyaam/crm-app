<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'assigned_staff',
        'contact',
        'last_followup_date',
        'next_followup_date',
        'status',
        'potential_revenue',
        'currency',
        'notes',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }
}
