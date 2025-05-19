@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Producto</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $producto->nombre }}" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control">{{ $producto->descripcion }}</textarea>
            </div>

            <div class="mb-3">
                <label for="precio_estimado" class="form-label">Precio Estimado (Q):</label>
                <input type="number" name="precio_estimado" id="precio_estimado" class="form-control" step="0.01" value="{{ $producto->precio_estimado }}" required>
            </div>

            <div class="mb-3">
                <label for="tiempo_estimado" class="form-label">Tiempo Estimado (Días):</label>
                <input type="number" name="tiempo_estimado" id="tiempo_estimado" class="form-control" value="{{ $producto->tiempo_estimado }}" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="Activo" {{ $producto->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ $producto->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fotografia" class="form-label">Fotografía:</label>
                <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
                
                @if($producto->fotografia)
                    <div class="mt-2">
                        <p>Imagen actual:</p>
                        <img src="{{ asset('storage/' .$producto->fotografia) }}" class="card-img-top" alt="Producto">
                    </div>
                @else
                    <p>No hay imagen cargada para este producto.</p>
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Actualizar Producto</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
