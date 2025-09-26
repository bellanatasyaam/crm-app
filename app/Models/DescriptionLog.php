<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionLog extends Model
{
    protected $fillable = ['vessel_id','changed_by','old_description','new_description'];

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
