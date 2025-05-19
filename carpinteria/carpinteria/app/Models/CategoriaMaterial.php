<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaMaterial extends Model
{
    protected $table = 'categorias_materiales';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public $timestamps = false;

    public function materiales()
    {
        return $this->hasMany(Material::class, 'id_categoria', 'id_categoria');
    }
}
