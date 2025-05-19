<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
   public function index(Request $request)
    {
        $categorias = \App\Models\CategoriaMaterial::all();

        if ($request->has('categoria') && $request->categoria != '') {
            $materiales = \App\Models\Material::where('id_categoria', $request->categoria)->get();
        } else {
            $materiales = \App\Models\Material::all();
        }

        return view('materiales.index', compact('materiales', 'categorias'));
    }

    public function create()
    {
        return view('materiales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'costo_unitario' => 'required|numeric',
            'estado' => 'required|in:Activo,Inactivo',
        ]);


        Material::create([
            'nombre' => $request->nombre,
            'costo_unitario' => $request->costo_unitario,
            'estado' => $request->estado
        ]);

        return redirect()->route('materiales.index')->with('success', 'Material creado correctamente.');
    }

    public function edit($id_material)
    {
        $material = Material::findOrFail($id_material);
        return view('materiales.edit', compact('material'));
    }

    public function update(Request $request, $id_material)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'costo_unitario' => 'required|numeric',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $material = Material::findOrFail($id_material);
        $material->update($request->all());

        return redirect()->route('materiales.index')->with('success', 'Material actualizado correctamente.');
    }

    public function destroy($id_material)
    {
        $material = Material::findOrFail($id_material);
        $material->delete();

        return redirect()->route('materiales.index')->with('success', 'Material eliminado correctamente.');
    }
}
