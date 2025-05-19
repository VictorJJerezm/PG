@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Crear Nuevo Producto</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="precio_estimado" class="form-label">Precio Estimado (Q):</label>
                <input type="number" name="precio_estimado" id="precio_estimado" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="tiempo_estimado" class="form-label">Tiempo Estimado (Días):</label>
                <input type="number" name="tiempo_estimado" id="tiempo_estimado" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fotografia" class="form-label">Fotografía:</label>
                <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Guardar Producto</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
