@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Listado de Todas las Cotizaciones</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total Estimado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->id_cotizacion }}</td>
                        <td>{{ $cotizacion->cliente->nombre }}</td>
                        <td>{{ $cotizacion->fecha }}</td>
                        <td>Q {{ number_format($cotizacion->costo_total, 2) }}</td>
                        <td>{{ $cotizacion->estado }}</td>
                        <td>
                            <a href="{{ route('cotizaciones.show', $cotizacion->id_cotizacion) }}" class="btn btn-info">Ver Detalle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
