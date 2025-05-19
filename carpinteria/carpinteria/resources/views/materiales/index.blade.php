@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Lista de Materiales</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('materiales.create') }}" class="btn btn-primary">+ Agregar Material</a>
        </div>

        <form method="GET" action="{{ route('materiales.index') }}" class="mb-3">
            <label for="categoria">Filtrar por Categoría:</label>
            <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
                <option value="">-- Seleccione una categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}" {{ request()->categoria == $categoria->id_categoria ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Costo Unitario (Q)</th>
                    <th>Estado</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materiales as $material)
                    <tr>
                        <td>{{ $material->nombre }}</td>
                        <td>Q {{ number_format($material->costo_unitario, 2) }}</td>
                        <td>{{ $material->estado }}</td>
                        <td>{{ $material->categoria ? $material->categoria->nombre : 'Sin categoría' }}</td>
                        <td>
                            <a href="{{ route('materiales.edit', $material->id_material) }}" class="btn btn-warning">Editar</a>

                            <button onclick="confirmarEliminacion({{ $material->id_material }})" class="btn btn-danger">
                                Eliminar
                            </button>

                            <form id="form-delete-{{ $material->id_material }}" 
                                  action="{{ route('materiales.destroy', $material->id_material) }}" 
                                  method="POST" 
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-delete-${id}`).submit();
                }
            })
        }
    </script>
@endsection
