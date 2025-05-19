@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Nueva Cotización para {{ $cliente->nombre }}</h2>

        <form action="{{ route('cotizaciones.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
            <input type="hidden" name="id_usuario" value="{{ auth()->id() }}">

            <div class="mb-3">
                <label for="comentario" class="form-label">Comentario:</label>
                <textarea name="comentario" id="comentario" class="form-control"></textarea>
            </div>

            <h4>Productos</h4>
            <div id="productos-container">
                <div class="producto-item mb-3">
                    <select name="productos[0][id_producto]" class="form-select mb-2">
                        <option value="" disabled selected>-- Selecciona un producto --</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>

                    <select name="productos[0][id_material]" class="form-select mb-2">
                        <option value="" disabled selected>-- Selecciona un material --</option>
                        @foreach ($materiales as $material)
                            <option value="{{ $material->id_material }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="productos[0][cantidad]" class="form-control mb-2" placeholder="Cantidad">
                    <input type="number" step="0.01" name="productos[0][ancho]" class="form-control mb-2" placeholder="Ancho (m)">
                    <input type="number" step="0.01" name="productos[0][alto]" class="form-control mb-2" placeholder="Alto (m)">
                    <input type="number" step="0.01" name="productos[0][profundidad]" class="form-control mb-2" placeholder="Profundidad (m)">
                </div>
            </div>

            <button type="button" onclick="agregarProducto()" class="btn btn-secondary mb-3">+ Agregar Producto</button>
            <button type="submit" class="btn btn-success">Guardar Cotización</button>
        </form>
    </div>

    <script>
        let index = 1;
        function agregarProducto() {
            const container = document.getElementById('productos-container');
            const template = `
                <div class="producto-item mb-3">
                    <select name="productos[${index}][id_producto]" class="form-select mb-2">
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>

                    <select name="productos[${index}][id_material]" class="form-select mb-2">
                        @foreach ($materiales as $material)
                            <option value="{{ $material->id_material }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="productos[${index}][cantidad]" class="form-control mb-2" placeholder="Cantidad">
                    <input type="number" step="0.01" name="productos[${index}][ancho]" class="form-control mb-2" placeholder="Ancho (m)">
                    <input type="number" step="0.01" name="productos[${index}][alto]" class="form-control mb-2" placeholder="Alto (m)">
                    <input type="number" step="0.01" name="productos[${index}][profundidad]" class="form-control mb-2" placeholder="Profundidad (m)">
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            index++;
        }
    </script>
@endsection
