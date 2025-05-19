<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_estimado',
        'tiempo_estimado',
        'estado',
        'fotografia'
    ];

    public $timestamps = false;
}
