<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las reviews del producto.
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
