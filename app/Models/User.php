<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Cek apakah user ini admin.
     * Admin ditandai berdasarkan email saja, tanpa database tambahan.
     */
    public function isAdmin()
    {
        $adminEmails = [
            'bellanatasyaam@gmail.com', // email admin
            // bisa ditambah email lain di sini
        ];

        return in_array($this->email, $adminEmails);
    }


        public function marketings()
    {
        return $this->hasMany(Marketing::class, 'staff_id');
    }

}
