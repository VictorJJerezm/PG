@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Detalle de Cotización</h2>

        <div class="card mb-3">
            <div class="card-header">
                Cotización #{{ $cotizacion->id_cotizacion }} - Cliente: {{ $cotizacion->cliente->nombre }}
            </div>
            <div class="card-body">
                <p><strong>Fecha:</strong> {{ $cotizacion->fecha }}</p>
                <p><strong>Comentario:</strong> {{ $cotizacion->comentario }}</p>
                <p><strong>Estado:</strong> {{ $cotizacion->estado }}</p>
                <p><strong>Total Estimado:</strong> Q {{ number_format($cotizacion->costo_total, 2) }}</p>
            </div>
        </div>

        <h4>Productos Cotizados</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Ancho (m)</th>
                    <th>Alto (m)</th>
                    <th>Profundidad (m)</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->material->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->ancho }}</td>
                        <td>{{ $detalle->alto }}</td>
                        <td>{{ $detalle->profundidad }}</td>
                        <td>Q {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>Q {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('cotizaciones.index', $cotizacion->id_cliente) }}" class="btn btn-secondary">Volver a Cotizaciones del Cliente</a>
    </div>
@endsection
