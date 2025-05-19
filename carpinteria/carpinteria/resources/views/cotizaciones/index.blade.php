@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Cotizaciones de {{ $cliente->nombre }}</h2>

        <a href="{{ route('cotizaciones.create', $cliente->id_cliente) }}" class="btn btn-primary mb-3">
            Nueva Cotización
        </a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Comentario</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->id_cotizacion }}</td>
                        <td>{{ $cotizacion->fecha }}</td>
                        <td>{{ $cotizacion->comentario }}</td>
                        <td>Q {{ number_format($cotizacion->costo_total, 2) }}</td>
                        <td>{{ $cotizacion->estado }}</td>
                        <td>
                            <a href="{{ route('cotizaciones.show', $cotizacion->id_cotizacion) }}" class="btn btn-info">
                                Ver Detalle
                            </a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion->id_cotizacion) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta cotización?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
