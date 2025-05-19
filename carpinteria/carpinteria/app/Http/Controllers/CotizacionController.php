<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Material;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Mail\RespuestaCotizacionMail;


class CotizacionController extends Controller
{

    public function indexAll()
    {
        $cotizaciones = Cotizacion::all();
        return view('cotizaciones.indexAll', compact('cotizaciones'));
    }

    public function pendientes()
    {
        $cotizaciones = Cotizacion::where('estado', 'Pendiente')->get();
        return view('cotizaciones.pendientes', compact('cotizaciones'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cotizaciones = Cotizacion::where('id_cliente', $id_cliente)->get();
        return view('cotizaciones.index', compact('cliente', 'cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $productos = Producto::where('estado', 'Activo')->get();
        $materiales = Material::all();
        return view('cotizaciones.create', compact('cliente', 'productos', 'materiales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Ingresó al método store de CotizacionController');
        
        DB::beginTransaction();

        try {
            // Crear la cotización
            $cotizacion = Cotizacion::create([
                'id_cliente' => $request->id_cliente,
                'id_usuario' => $request->id_usuario,
                'comentario' => $request->comentario,
                'costo_total' => 0,  // Se actualizará más adelante
                'estado' => 'Pendiente'
            ]);

            \Log::info('Cotización creada: ' . $cotizacion->id_cotizacion);

            $total = 0;

            // Recorrer los productos
            foreach ($request->productos as $producto) {
                $precioUnitario = Producto::find($producto['id_producto'])->precio_estimado;
                $subtotal = $precioUnitario * $producto['cantidad'];
                $total += $subtotal;

                // Inserción en la tabla de detalles
                DB::table('detalle_cotizacion')->insert([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'id_material' => $producto['id_material'],
                    'ancho' => $producto['ancho'],
                    'alto' => $producto['alto'],
                    'profundidad' => $producto['profundidad'],
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal
                ]);
                \Log::info('Detalle creado para el producto: ' . $producto['id_producto']);
            }

            // Actualizamos el costo total en la cotización
            $cotizacion->costo_total = $total;
            $cotizacion->save();
            
            DB::commit();
            \Log::info('Cotización guardada correctamente.');

            return redirect()->route('cotizaciones.index', $cotizacion->id_cliente)
                             ->with('success', 'Cotización guardada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar cotización: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al guardar la cotización.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $detalles = DetalleCotizacion::where('id_cotizacion', $id)->get();
        return view('cotizaciones.show', compact('cotizacion', 'detalles'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cotizacion = Cotizacion::with('cliente', 'detalles.producto', 'detalles.material')->findOrFail($id);
        return view('cotizaciones.responder', compact('cotizacion'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cotizacion = Cotizacion::findOrFail($id);

            if (!$cotizacion->id_cliente) {
                throw new \Exception("No se encontró un cliente asociado a la cotización.");
            }

            $cliente = $cotizacion->cliente;
            $id_cliente = $cotizacion->id_cliente;

            // Actualización del estado y comentario
            $cotizacion->estado = 'Respondida';
            $cotizacion->comentario = $request->comentario;
            $cotizacion->save();

            DB::commit();

            // **Envio del SMS usando Twilio**
            $this->enviarSMS($cliente->telefono, $cotizacion->comentario);


            \Mail::to($cliente->correo)->send(new \App\Mail\RespuestaCotizacionMail($cotizacion, $request->comentario));

            return redirect()->route('cotizaciones.index', ['id_cliente' => $id_cliente])
                ->with('success', 'Respuesta enviada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar respuesta de cotización: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al guardar la respuesta.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $id_cliente = $cotizacion->id_cliente;
        $cotizacion->detalles()->delete();
        $cotizacion->delete();

        return redirect()->route('cotizaciones.index', $id_cliente)
                         ->with('success', 'Cotización eliminada correctamente.');
    }


    public function responder($id)
    {
        $cotizacion = Cotizacion::with('detalles.producto')->findOrFail($id);
        $insumos = Inventario::all();
        return view('cotizaciones.responder', compact('cotizacion', 'insumos'));
    }

    public function guardarRespuesta(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cotizacion = Cotizacion::findOrFail($id);

            // Actualización de estado y mensaje
            $cotizacion->estado = 'Respondida';
            $cotizacion->comentario = $request->mensaje;
            $cotizacion->save();

            // Calculo de costos de insumos
            $costoTotal = 0;
            foreach ($request->productos as $productoId => $insumoId) {
                $insumo = Inventario::find($insumoId);
                $costoTotal += $insumo->precio * $request->cantidades[$productoId];
            }

            $cotizacion->costo_total += $costoTotal;
            $cotizacion->save();

            DB::commit();
            return redirect()->route('cotizaciones.index')->with('success', 'Cotización respondida correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al responder la cotización: ' . $e->getMessage());
        }
    }

    private function enviarSMS($telefono, $mensaje)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $twilio->setHttpClient(new \Twilio\Http\CurlClient([
            CURLOPT_CAINFO => storage_path('certs/cacert.pem'),
        ]));

        try {
            $twilio->messages->create(
                $telefono, // Número del cliente
                [
                    'from' => env('TWILIO_PHONE'),
                    'body' => "¡Tu cotización te la carpintería ha sido respondida!: $comentario" 
                ]
            );
            \Log::info("SMS enviado correctamente a $telefono");
        } catch (\Exception $e) {
            \Log::error("Error al enviar SMS: " . $e->getMessage());
        }
    }





}
