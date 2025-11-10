<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vessel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'name', 'imo_number', 'call_sign', 'port_of_call', 'flag', 
        'vessel_type', 'gross_tonnage', 'net_tonnage', 'year_built', 'status',
        'created_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function descriptionLogs()
    {
        return $this->hasMany(DescriptionLog::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
