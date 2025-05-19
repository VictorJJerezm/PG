<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente); 
        return view('clientes.edit', compact('cliente'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_cliente)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
