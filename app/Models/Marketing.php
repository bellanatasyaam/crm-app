<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;

    protected $table = 'marketings';

    protected $fillable = [
        'client_name',
        'project_name',
        'staff',
        'status',
        'value',
        'last_contact',
        'next_follow_up',
        'remark',
    ];
}
