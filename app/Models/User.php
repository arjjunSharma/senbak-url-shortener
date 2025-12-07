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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const ROLE_SUPER_ADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SALES = 'sales';
    public const ROLE_MEMBER = 'member';
    public const ROLE_MANAGER = 'manager';

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isSuperAdmin(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_ADMIN;
    }

    public function isSales(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_SALES;
    }

    public function isMember(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_MEMBER;
    }

    public function isManager(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_MANAGER;
    }
}
