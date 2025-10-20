<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'phone',
        'address',
        'assigned_staff',
        'status',
        'currency',
        'potential_revenue',
        'last_followup_date',
        'next_followup_date',
        'description',
        'remark',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function vessels()
    {
        return $this->hasMany(Vessel::class, 'customer_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }
    
    // App\Models\Customer.php
    public function logs()
    {
        return $this->hasMany(Log::class, 'customer_id');
    }

    public function customerVessels()
    {
        return $this->hasMany(CustomerVessel::class, 'customer_id');
    }


}
