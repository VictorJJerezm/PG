@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Lista de Clientes</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Agregar Cliente</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id_cliente }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->correo }}</td>
                        <td>{{ $cliente->direccion }}</td>
                        <td>{{ $cliente->estado }}</td>
                        <td>
                            <button onclick="confirmarEdicion({{ $cliente->id_cliente }})" class="btn btn-warning">
                                Editar
                            </button>

                            <button onclick="confirmarEliminacion({{ $cliente->id_cliente }})" class="btn btn-danger">
                                Eliminar
                            </button>

                            <a href="{{ route('cotizaciones.create', $cliente->id_cliente) }}" class="btn btn-primary">
                                Nueva Cotización
                            </a>

                            <form id="form-delete-{{ $cliente->id_cliente }}" 
                                  action="{{ route('clientes.destroy', $cliente->id_cliente) }}" 
                                  method="POST" 
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <form id="form-edit-{{ $cliente->id_cliente }}" 
                                  action="{{ route('clientes.edit', $cliente->id_cliente) }}" 
                                  method="GET" 
                                  style="display: none;">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Incluimos SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-delete-${id}`).submit();
                }
            })
        }

        function confirmarEdicion(id) {
            Swal.fire({
                title: '¿Editar Cliente?',
                text: "Vas a ingresar al formulario de edición.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-edit-${id}`).submit();
                }
            })
        }
    </script>
@endsection
