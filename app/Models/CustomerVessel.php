<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVessel extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'vessel_id',
        'status',
        'potential_revenue',
        'currency',
        'last_followup_date',
        'next_followup_date',
        'description',
        'remark',
    ];

    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'vessel_id');
    }

    public function customer()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
