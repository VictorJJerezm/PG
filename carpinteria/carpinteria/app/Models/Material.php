<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventario;

class Material extends Model
{
    protected $table = 'materiales';
    protected $primaryKey = 'id_material';

    protected $fillable = [
        'nombre',
        'costo_unitario',
        'estado',
        'id_categoria'
    ];

    public $timestamps = false;

    // Relación con Categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaMaterial::class, 'id_categoria', 'id_categoria');
    }

    // Listener para actualizar el inventario cuando se actualiza un material
    protected static function booted()
    {
        static::updated(function ($material) {
            // Buscar todos los inventarios que usen este material
            $inventarios = Inventario::where('id_material', $material->id_material)->get();

            foreach ($inventarios as $inventario) {
                $inventario->update([
                    'precio_unitario' => $material->costo_unitario
                ]);
            }
        });
    }
}
