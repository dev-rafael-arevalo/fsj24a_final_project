<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // Asegúrate de que coincida con la tabla en la base de datos.

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Los atributos que deben ocultarse para los arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
