<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'website', 'tax_id', 'type', 'industry',
        'customer_tier', 'status', 'address', 'city', 'country',
        'assigned_staff_id', 'assigned_staff', 'assigned_staff_email',
        'last_followup_date', 'next_followup_date', 'remark',
    ];

    protected $attributes = [
        'country' => 'Indonesia',
        'customer_tier' => 'regular',
        'status' => 'active',
    ];  

    protected $casts = [
    'last_followup_date' => 'date',
    'next_followup_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($company) {
            if (empty($company->code)) {
                $maxCode = Company::withTrashed()
                    ->selectRaw("MAX(CAST(SUBSTRING(code, 6) AS UNSIGNED)) as max_number")
                    ->first()
                    ->max_number;

                $nextNumber = $maxCode ? $maxCode + 1 : 1;
                $company->code = 'CUST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }

            if (auth()->check()) {
                $company->created_by = auth()->id();
                $company->updated_by = auth()->id();
            }
        });

        static::updating(function ($company) {
            if (auth()->check()) {
                $company->updated_by = auth()->id();
            }
        });
    }

    public function vessels()
    {
        return $this->hasMany(Vessel::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }
    
    // App\Models\Customer.php
    public function logs()
    {
        return $this->hasMany(Log::class, 'company_id');
    }

    public function customerVessels()
    {
        return $this->hasMany(CustomerVessel::class, 'company_id');
    }


}
