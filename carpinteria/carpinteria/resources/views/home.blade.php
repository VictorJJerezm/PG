@extends('layouts.app')

@section('title', 'Página Principal - Carpintería')

@section('content')
    <div class="container">
        <h1>Bienvenido al Sistema de Cotizaciones de Carpintería</h1>
        <p>Seleccione un módulo del menú superior para comenzar.</p>

        <div class="alert alert-primary mt-3">
            Este es el panel principal donde podrás acceder a los módulos:
            <ul>
                <li>Materiales</li>
                <li>Inventarios</li>
                <li>Productos</li>
                <li>Clientes</li>
                <li>Cotizaciones</li>
            </ul>
        </div>
    </div>
@endsection
