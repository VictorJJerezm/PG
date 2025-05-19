@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Editar Material</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formulario-editar" action="{{ route('materiales.update', $material->id_material) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Material:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $material->nombre }}" required>
            </div>

            <div class="mb-3">
                <label for="costo_unitario" class="form-label">Costo Unitario (Q):</label>
                <input type="number" step="0.01" name="costo_unitario" id="costo_unitario" class="form-control" value="{{ $material->costo_unitario }}" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="Activo" {{ $material->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ $material->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría:</label>
                <select name="id_categoria" id="id_categoria" class="form-select" required>
                    @foreach(App\Models\CategoriaMaterial::all() as $categoria)
                        <option value="{{ $categoria->id_categoria }}" 
                            {{ $material->id_categoria == $categoria->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" onclick="confirmarEdicion()" class="btn btn-success">Guardar cambios</button>
                <a href="{{ route('materiales.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmarEdicion() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Los cambios serán guardados de manera permanente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar cambios!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formulario-editar').submit();
                }
            })
        }
    </script>
@endsection
