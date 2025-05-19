<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';
    protected $primaryKey = 'id_inv';

    protected $fillable = [
        'id_material',
        'tipo',
        'descripcion',
        'cantidad',
        'largo',
        'alto',
        'ancho',
        'tipo_material',
        'precio_unitario',
        'fecha_actualizacion'
    ];

    public $timestamps = false;

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
}
