<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'customer_id','assigned_staff_id','vessel_name','port_of_call',
        'estimate_revenue','currency','description','remark','status',
        'last_contact','next_follow_up'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function descriptionLogs()
    {
        return $this->hasMany(DescriptionLog::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }
}
