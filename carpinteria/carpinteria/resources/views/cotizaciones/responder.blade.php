@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Responder Cotización - ID: {{ $cotizacion->id_cotizacion }}</h2>
        <h4>Cliente: {{ $cotizacion->cliente->nombre }}</h4>
        <p><strong>Correo:</strong> {{ $cotizacion->cliente->correo }}</p>
        <p><strong>Estado:</strong> {{ $cotizacion->estado }}</p>
        <p><strong>Total Estimado:</strong> Q {{ number_format($cotizacion->costo_total, 2) }}</p>

        <h4>Detalles de la Cotización:</h4>
        <ul>
            @foreach ($cotizacion->detalles as $detalle)
                <li>
                    {{ $detalle->producto->nombre }} - Material: {{ $detalle->material->nombre }} - Cantidad: {{ $detalle->cantidad }}
                </li>
            @endforeach
        </ul>

        <form action="{{ route('cotizaciones.update', $cotizacion->id_cotizacion) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje para el Cliente:</label>
                <textarea name="comentario" id="comentario" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Enviar Mensaje</button>
            <a href="{{ route('cotizaciones.pendientes') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
