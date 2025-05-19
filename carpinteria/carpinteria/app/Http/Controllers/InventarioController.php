<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Material;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventarios = Inventario::all();

        $stockBajo = Inventario::where('cantidad', '<=', 5)->get();

        return view('inventarios.index', compact('inventarios', 'stockBajo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materiales = Material::all();
        return view('inventarios.create', compact('materiales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required',
            'cantidad' => 'required|integer',
            'descripcion' => 'nullable',
            'id_material' => 'nullable|exists:materiales,id_material',
            'largo' => 'nullable|numeric',
            'alto' => 'nullable|numeric',
            'ancho' => 'nullable|numeric',
            'tipo_material' => 'nullable|string',
            'precio_unitario' => 'nullable|numeric'
        ]);

        // Sincronización de precios
        if ($request->tipo === 'Material' && $request->id_material) {
            $material = \App\Models\Material::find($request->id_material);
            $request->merge(['precio_unitario' => $material->costo_unitario]);
        }

        Inventario::create($request->all());

        return redirect()->route('inventarios.index')->with('success', 'Inventario creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_inv)
    {
        $inventario = Inventario::find($id_inv);

        if (!$inventario) {
            return redirect()->route('inventarios.index')->with('error', 'Inventario no encontrado.');
        }

        $materiales = Material::all();
        return view('inventarios.edit', compact('inventario', 'materiales'));
    }


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id_inv)
    {
        $request->validate([
            'tipo' => 'required',
            'cantidad' => 'required|integer',
            'descripcion' => 'nullable',
            'id_material' => 'nullable|exists:materiales,id_material',
            'largo' => 'nullable|numeric',
            'alto' => 'nullable|numeric',
            'ancho' => 'nullable|numeric',
            'tipo_material' => 'nullable|string',
            'precio_unitario' => 'nullable|numeric'
        ]);

        $inventario = Inventario::find($id_inv);

        // Sincronización de precios
        if ($request->tipo === 'Material' && $request->id_material) {
            $material = \App\Models\Material::find($request->id_material);
            $request->merge(['precio_unitario' => $material->costo_unitario]);
        }

        $inventario->update($request->all());

        return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_inv)
    {
        $inventario = Inventario::find($id_inv);

        if (!$inventario) {
            return redirect()->route('inventarios.index')->with('error', 'Inventario no encontrado.');
        }

        $inventario->delete();
        return redirect()->route('inventarios.index')->with('success', 'Inventario eliminado correctamente.');
    }

}
