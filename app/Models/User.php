<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $fillable = ['username', 'password', 'role', 'expires_at'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isExpired()
    {
        return $this->role !== 'admin' && $this->expires_at && now()->greaterThan($this->expires_at);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
