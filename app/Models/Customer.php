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
    'assigned_staff',   // <-- ini penting
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
        return $this->hasMany(Vessel::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }


}
