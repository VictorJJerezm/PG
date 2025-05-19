@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Inventario de Insumos y Materiales</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Notificación de Stock Bajo -->
        @php
            $stockBajo = $inventarios->filter(function ($item) {
                return $item->cantidad <= 5;
            });
        @endphp

        @if($stockBajo->count() > 0)
            <div class="alert alert-warning">
                <strong>¡Atención!</strong> Hay productos con stock bajo:
                <ul>
                    @foreach($stockBajo as $item)
                        <li>
                            {{ $item->tipo === 'Material' ? $item->material->nombre : $item->descripcion }} 
                            → Cantidad: {{ $item->cantidad }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('inventarios.create') }}" class="btn btn-primary">+ Nuevo Inventario</a>
        </div>

        <!-- Filtro por Tipo -->
        <form method="GET" action="{{ route('inventarios.index') }}" class="mb-3">
            <label for="tipo">Filtrar por Tipo:</label>
            <select name="tipo" id="tipo" class="form-select" onchange="this.form.submit()">
                <option value="">-- Seleccionar Tipo --</option>
                <option value="Material" {{ request()->tipo == 'Material' ? 'selected' : '' }}>Material</option>
                <option value="Insumo" {{ request()->tipo == 'Insumo' ? 'selected' : '' }}>Insumo</option>
            </select>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Material</th>
                    <th>Precio Unitario</th>
                    <th>Largo (m)</th>
                    <th>Alto (m)</th>
                    <th>Ancho (m)</th>
                    <th>Cantidad</th>
                    <th>Fecha Actualización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventarios as $inventario)
                    @if(!request()->tipo || $inventario->tipo === request()->tipo)
                        <tr class="{{ $inventario->cantidad <= 5 ? 'table-danger' : '' }}">
                            <td>{{ $inventario->id_inv }}</td>
                            <td>
                                @if($inventario->tipo === 'Material')
                                    {{ $inventario->material ? $inventario->material->nombre : 'Sin Nombre' }}
                                @else
                                    {{ $inventario->descripcion }}
                                @endif
                            </td>

                            <td>{{ $inventario->tipo }}</td>

                            <td>
                                @if($inventario->tipo === 'Material')
                                    -
                                @else
                                    {{ $inventario->tipo_material ?? 'No especificado' }}
                                @endif
                            </td>

                            <td>Q {{ number_format($inventario->precio_unitario, 2) }}</td>
                            <td>{{ $inventario->largo ?? '-' }}</td>
                            <td>{{ $inventario->alto ?? '-' }}</td>
                            <td>{{ $inventario->ancho ?? '-' }}</td>
                            <td>{{ $inventario->cantidad }}</td>
                            <td>{{ $inventario->fecha_actualizacion }}</td>
                            <td>
                                <button onclick="confirmarEdicion({{ $inventario->id_inv }})" class="btn btn-warning">
                                    Editar
                                </button>
                                <button onclick="confirmarEliminacion({{ $inventario->id_inv }})" class="btn btn-danger">
                                    Eliminar
                                </button>

                                <form id="form-delete-{{ $inventario->id_inv }}" 
                                      action="{{ route('inventarios.destroy', $inventario->id_inv) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <form id="form-edit-{{ $inventario->id_inv }}" 
                                      action="{{ route('inventarios.edit', $inventario->id_inv) }}" 
                                      method="GET" 
                                      style="display: none;">
                                </form>
                            </td>
                        </tr>
                    @endif
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

        function confirmarEdicion(id) {
            Swal.fire({
                title: '¿Editar Inventario?',
                text: "Vas a ingresar al formulario de edición.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, editar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-edit-${id}`).submit();
                }
            })
        }
    </script>
@endsection
