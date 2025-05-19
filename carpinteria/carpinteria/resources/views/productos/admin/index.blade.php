@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Gestión de Productos</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">+ Agregar Producto</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio Estimado (Q)</th>
                    <th>Tiempo Estimado (Días)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id_producto }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>Q {{ number_format($producto->precio_estimado, 2) }}</td>
                        <td>{{ $producto->tiempo_estimado }}</td>
                        <td>{{ $producto->estado }}</td>
                        <td>
                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
