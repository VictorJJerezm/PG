<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;

Route::get('/', function () {
    return view('home');
});

/* Rutas Materiales */
Route::resource('materiales', MaterialController::class)->parameters([
    'materiales' => 'material'
]);

/* Rutas Inventarios */
Route::resource('inventarios', InventarioController::class)->parameters([
    'inventarios' => 'inventario'
]);

/* Rutas Productos */
Route::prefix('admin/productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
});

Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');

/* Rutas Clientes */
Route::resource('clientes', ClienteController::class)->parameters([
    'clientes' => 'cliente'
]);

/* Rutas Cotizaciones */
Route::prefix('cotizaciones')->group(function () {
    Route::get('/todas', [CotizacionController::class, 'indexAll'])->name('cotizaciones.indexAll');
    Route::get('/pendientes', [CotizacionController::class, 'pendientes'])->name('cotizaciones.pendientes');
    Route::get('/cliente/{id_cliente}', [CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('/crear/{id_cliente}', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::post('/guardar', [CotizacionController::class, 'store'])->name('cotizaciones.store');
    Route::get('/ver/{id}', [CotizacionController::class, 'show'])->name('cotizaciones.show');
    Route::get('/editar/{id}', [CotizacionController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/actualizar/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.update');
    Route::delete('/eliminar/{id}', [CotizacionController::class, 'destroy'])->name('cotizaciones.destroy');

    // Rutas para responder cotización
    Route::get('/responder/{id}', [CotizacionController::class, 'responder'])->name('cotizaciones.responder');
    Route::post('/responder/{id}', [CotizacionController::class, 'guardarRespuesta'])->name('cotizaciones.guardarRespuesta');
});

Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.update');
