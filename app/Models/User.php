<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'is_active', // Tambahkan ini
    ];


// Helper methods
public function isAdmin()
{
    // Mengembalikan nilai true jika role adalah admin ATAU superadmin
    return in_array($this->role, ['admin', 'superadmin']);
}

public function isUser(): bool
{
    return $this->role === 'user';
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
    return $this->hasMany(Order::class);
    }

    public function wishlists() { return $this->hasMany(Wishlist::class); }
    public function reviews() { return $this->hasMany(Review::class); }
}
