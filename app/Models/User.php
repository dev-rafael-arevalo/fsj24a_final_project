<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes; // Habilita el soft delete

    protected $dates = ['deleted_at']; // Agrega el campo deleted_at

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Relación con las reviews del usuario.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calcular el promedio de valoraciones.
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
