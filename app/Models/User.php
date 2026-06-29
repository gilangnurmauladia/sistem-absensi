<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;
 
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function isAdmin(): bool
    {
        // Fallback agar login tidak error saat tabel roles belum ada/baru migrasi.
        if (!Schema::hasTable('roles')) {
            return $this->role === 'admin';
        }

        return $this->hasAnyRole(['Super Admin', 'Admin']) || $this->role === 'admin';
    }

    public function isKaryawan(): bool
    {
        if (!Schema::hasTable('roles')) {
            return $this->role === 'karyawan';
        }

        return $this->hasRole('Karyawan') || $this->role === 'karyawan';
    }
}
