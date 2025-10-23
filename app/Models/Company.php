<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'type', 'industry', 'phone', 'email', 'website',
        'address', 'city', 'country', 'tax_id', 'customer_tier', 'status',
    ];

    protected $attributes = [
        'country' => 'Indonesia',
        'customer_tier' => 'regular',
        'status' => 'active',
    ];

    protected static function booted()
    {
        static::creating(function ($company) {
            if (empty($company->code)) {
                $latest = Company::withTrashed()->latest('id')->first();
                $nextNumber = $latest ? ((int) substr($latest->code, 4)) + 1 : 1;
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

    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function vessels()
    {
        return $this->hasMany(Vessel::class, 'company_id');
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
