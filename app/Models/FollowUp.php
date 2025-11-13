<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'assigned_staff_id',
        'vessel_name',
        'status',
        'revenue_usd',
        'description',
        'remark',
        'last_contact',
        'next_follow_up',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
