@extends('layouts.app')

@section('content')
    <h2>Nuevo Inventario</h2>

    <form action="{{ route('inventarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" class="form-select" onchange="mostrarCampos(this.value)">
                <option value="Material">Material</option>
                <option value="Insumo">Insumo</option>
            </select>
        </div>

        <div id="materialFields">
            <label for="id_material">Seleccionar Material:</label>
            <select name="id_material" id="id_material" class="form-select">
                <option value="">-- Seleccionar --</option>
                @foreach($materiales as $material)
                    <option value="{{ $material->id_material }}" data-precio="{{ $material->costo_unitario }}">
                        {{ $material->nombre }} - Q {{ number_format($material->costo_unitario, 2) }}
                    </option>
                @endforeach
            </select>

            <div class="mb-3 mt-3">
                <label for="precio_material">Precio Unitario:</label>
                <input type="number" id="precio_material" class="form-control" readonly>
            </div>
        </div>

        <div id="insumoFields" style="display: none;">
            <label for="descripcion">Nombre del Insumo:</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control">

            <label for="tipo_material">Tipo de Material:</label>
            <input type="text" name="tipo_material" id="tipo_material" class="form-control">

            <label for="precio_unitario">Precio Unitario:</label>
            <input type="number" name="precio_unitario" id="precio_unitario" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label for="largo">Largo (m):</label>
            <input type="number" name="largo" id="largo" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label for="alto">Alto (m):</label>
            <input type="number" name="alto" id="alto" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label for="ancho">Ancho (m):</label>
            <input type="number" name="ancho" id="ancho" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Inventario</button>
    </form>

    <script>
        function mostrarCampos(tipo) {
            if (tipo === 'Material') {
                document.getElementById('materialFields').style.display = 'block';
                document.getElementById('insumoFields').style.display = 'none';
            } else {
                document.getElementById('materialFields').style.display = 'none';
                document.getElementById('insumoFields').style.display = 'block';
            }
        }

        document.getElementById('id_material').addEventListener('change', function () {
            const precio = this.options[this.selectedIndex].getAttribute('data-precio');
            document.getElementById('precio_material').value = precio;
        });
    </script>
@endsection
