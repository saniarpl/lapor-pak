<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SISWA = 'siswa';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'class_name',
    ];

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

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSiswa(): bool
    {
        return $this->role === self::ROLE_SISWA;
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class, 'admin_id');
    }
}