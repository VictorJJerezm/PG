<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    // Definimos los campos que se pueden rellenar masivamente
    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'direccion',
        'estado'
    ];

    public $timestamps = false;
}
