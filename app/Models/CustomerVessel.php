<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVessel extends Model
{
    use HasFactory;

    protected $table = 'customer_vessel';

    protected $fillable = [
        'customer_id',
        'vessel_name',
        'status',
        'currency',
        'potential_revenue',
        'next_followup_date',
    ];
}
