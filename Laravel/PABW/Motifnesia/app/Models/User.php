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
        'full_name',
        'email',
        'password',
        'role',
        'profile_pic',
        'phone_number',
        'birth_date',
        'gender',
        'address_line',
        'city',
        'province',
        'postal_code',
        'secret_question',
        'secret_answer',
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

    // Relationship dengan orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship dengan addresses
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    // Get primary address
    public function primaryAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_primary', true);
    }
}
