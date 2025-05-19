@extends('layouts.app')

@section('content')
    <h2>Editar Inventario</h2>

    <form action="{{ route('inventarios.update', $inventario->id_inv) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Mostrar el tipo (Insumo o Material) -->
        <div class="mb-3">
            <label for="tipo">Tipo:</label>
            <input type="text" name="tipo" id="tipo" class="form-control" value="{{ $inventario->tipo }}" readonly>
        </div>

        <!-- Campos para Material -->
        @if($inventario->tipo === 'Material')
            <div class="mb-3">
                <label for="id_material">Material:</label>
                <select name="id_material" id="id_material" class="form-select">
                    @foreach($materiales as $material)
                        <option value="{{ $material->id_material }}" 
                            data-precio="{{ $material->costo_unitario }}"
                            {{ $material->id_material == $inventario->id_material ? 'selected' : '' }}>
                            {{ $material->nombre }} - Q {{ number_format($material->costo_unitario, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Precio del Material -->
            <div class="mb-3">
                <label for="precio_material">Precio Unitario:</label>
                <input type="number" id="precio_material" class="form-control" value="{{ $inventario->precio_unitario }}" readonly>
            </div>

        @endif

        <!-- Campos para Insumo -->
        @if($inventario->tipo === 'Insumo')
            <div class="mb-3">
                <label for="descripcion">Nombre del Insumo:</label>
                <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ $inventario->descripcion }}">
            </div>

            <div class="mb-3">
                <label for="tipo_material">Tipo de Material:</label>
                <input type="text" name="tipo_material" id="tipo_material" class="form-control" value="{{ $inventario->tipo_material }}">
            </div>

            <div class="mb-3">
                <label for="precio_unitario">Precio Unitario:</label>
                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control" step="0.01" value="{{ $inventario->precio_unitario }}">
            </div>
        @endif

        <!-- Dimensiones -->
        <div class="mb-3">
            <label for="largo">Largo (m):</label>
            <input type="number" name="largo" id="largo" class="form-control" value="{{ $inventario->largo }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="alto">Alto (m):</label>
            <input type="number" name="alto" id="alto" class="form-control" value="{{ $inventario->alto }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="ancho">Ancho (m):</label>
            <input type="number" name="ancho" id="ancho" class="form-control" value="{{ $inventario->ancho }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="{{ $inventario->cantidad }}" required>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Inventario</button>
    </form>

    <script>
        // Sincronización del precio al cambiar de material
        document.getElementById('id_material').addEventListener('change', function () {
            const precio = this.options[this.selectedIndex].getAttribute('data-precio');
            document.getElementById('precio_material').value = precio;
        });
    </script>
@endsection
