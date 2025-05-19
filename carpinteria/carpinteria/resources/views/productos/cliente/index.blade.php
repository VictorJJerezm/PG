@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Catálogo de Productos</h2>

        <div class="row">
            @foreach ($productos as $producto)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        @if($producto->fotografia && file_exists(public_path('storage/' .$producto->fotografia)))
                        <img src ="{{ asset('storage/' .$producto->fotografia) }}" class="card-img-top" alt="Producto">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text">{{ $producto->descripcion }}</p>
                            <p><strong>Precio:</strong> Q {{ number_format($producto->precio_estimado, 2) }}</p>
                            <p><strong>Tiempo Estimado:</strong> {{ $producto->tiempo_estimado }} días</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
