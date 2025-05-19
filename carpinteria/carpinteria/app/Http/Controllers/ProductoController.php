<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cotizacion;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.admin.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'precio_estimado' => 'required|numeric',
            'tiempo_estimado' => 'required|integer',
            'estado' => 'required|in:Activo,Inactivo',
            'fotografia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('fotografia')) {
            $ruta = $request->file('fotografia')->store('productos', 'public');
            $data['fotografia'] = $ruta;
        }

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.admin.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cotizacion = Cotizacion::findOrFail($id);

            // Recuperar el ID del cliente correctamente
            $id_cliente = $cotizacion->id_cliente;

            // Actualización del estado y comentario
            $cotizacion->estado = 'Respondida';
            $cotizacion->comentario = $request->comentario;
            $cotizacion->save();

            // Recorrer los insumos seleccionados y registrar en la base de datos
            if (!empty($request->insumos)) {
                foreach ($request->insumos as $idDetalle => $insumo) {
                    // Actualizar el detalle correspondiente
                    $detalle = DetalleCotizacion::findOrFail($idDetalle);
                    $detalle->id_insumo = $insumo['id_insumo'];
                    $detalle->cantidad = $insumo['cantidad'];
                    $detalle->save();
                }
            }

            DB::commit();

            // ✅ Redirigir correctamente al listado de cotizaciones del cliente
            return redirect()->route('cotizaciones.index', ['id_cliente' => $id_cliente])
                            ->with('success', 'Respuesta guardada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar respuesta de cotización: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al guardar la respuesta.');
        }
    }



    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function catalogo()
    {
        $productos = Producto::where('estado', 'Activo')->get();
        return view('productos.cliente.index', compact('productos'));
    }
    
}
