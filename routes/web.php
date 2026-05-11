<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\TaquillaController;
use App\Http\Controllers\TiqueteController;
use App\Http\Controllers\TipoBusController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CostoViajeController;

// --- Rutas de autenticación (públicas) ---
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Rutas protegidas (requieren sesión activa) ---
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect('/viajes'));
    Route::get('/taquilla', fn() => redirect('/viajes'));

    Route::middleware('permission:taquilla.ver')->group(function () {
        Route::get('/taquilla/{viaje}', [TaquillaController::class, 'verBus'])
            ->whereNumber('viaje')
            ->name('taquilla');
    });

    Route::middleware('permission:taquilla.venta')->group(function () {
        Route::post('/vender-tiquete', [TaquillaController::class, 'comprar'])->name('taquilla.comprar');
        Route::get('/taquilla/{viaje}/confirmar', [TaquillaController::class, 'confirmar'])
            ->whereNumber('viaje')
            ->name('taquilla.confirmar');
    });

    Route::middleware('permission:taquilla.recibo')->group(function () {
        Route::get('/taquilla/recibo/{tiquete}', [TaquillaController::class, 'recibo'])
            ->whereNumber('tiquete')
            ->name('taquilla.recibo');
    });

    Route::middleware('permission:viajes.crear')->group(function () {
        Route::get('viajes/create', [ViajeController::class, 'create'])->name('viajes.create');
        Route::post('viajes', [ViajeController::class, 'store'])->name('viajes.store');
    });

    Route::middleware('permission:viajes.ver')->group(function () {
        Route::get('viajes', [ViajeController::class, 'index'])->name('viajes.index');
        Route::get('viajes/cancelados', [ViajeController::class, 'cancelados'])->name('viajes.cancelados');
        Route::get('viajes/{viaje}', [ViajeController::class, 'show'])
            ->whereNumber('viaje')
            ->name('viajes.show');
    });

    Route::middleware('permission:viajes.editar')->group(function () {
        Route::get('viajes/{viaje}/edit', [ViajeController::class, 'edit'])
            ->whereNumber('viaje')
            ->name('viajes.edit');
        Route::put('viajes/{viaje}', [ViajeController::class, 'update'])
            ->whereNumber('viaje')
            ->name('viajes.update');
    });

    Route::middleware('permission:viajes.eliminar')->group(function () {
        Route::delete('viajes/{viaje}', [ViajeController::class, 'destroy'])
            ->whereNumber('viaje')
            ->name('viajes.destroy');
    });

    Route::middleware('permission:viajes.cancelar')->group(function () {
        Route::patch('viajes/{viaje}/cancelar', [ViajeController::class, 'cancelar'])
            ->whereNumber('viaje')
            ->name('viajes.cancelar');
    });

    Route::middleware('permission:viajes.renovar')->group(function () {
        Route::patch('viajes/{viaje}/renovar', [ViajeController::class, 'renovar'])
            ->whereNumber('viaje')
            ->name('viajes.renovar');
    });

    Route::middleware('permission:viajes.completar')->group(function () {
        Route::patch('viajes/{viaje}/completar', [ViajeController::class, 'completar'])
            ->whereNumber('viaje')
            ->name('viajes.completar');
    });

    Route::middleware('permission:viajes.costos.crear')->group(function () {
        Route::post('viajes/{viaje}/costos', [CostoViajeController::class, 'store'])
            ->whereNumber('viaje')
            ->name('viajes.costos.store');
    });

    Route::middleware('permission:viajes.costos.eliminar')->group(function () {
        Route::delete('viajes/{viaje}/costos/{costo}', [CostoViajeController::class, 'destroy'])
            ->whereNumber('viaje')
            ->whereNumber('costo')
            ->name('viajes.costos.destroy');
    });

    Route::middleware('permission:tiquetes.crear')->group(function () {
        Route::get('tiquetes/create', [TiqueteController::class, 'create'])->name('tiquetes.create');
        Route::post('tiquetes', [TiqueteController::class, 'store'])->name('tiquetes.store');
    });

    Route::middleware('permission:tiquetes.ver')->group(function () {
        Route::get('tiquetes', [TiqueteController::class, 'index'])->name('tiquetes.index');
        Route::get('tiquetes/{tiquete}', [TiqueteController::class, 'show'])
            ->whereNumber('tiquete')
            ->name('tiquetes.show');
    });

    Route::middleware('permission:tiquetes.editar')->group(function () {
        Route::get('tiquetes/{tiquete}/edit', [TiqueteController::class, 'edit'])
            ->whereNumber('tiquete')
            ->name('tiquetes.edit');
        Route::put('tiquetes/{tiquete}', [TiqueteController::class, 'update'])
            ->whereNumber('tiquete')
            ->name('tiquetes.update');
    });

    Route::middleware('permission:tiquetes.eliminar')->group(function () {
        Route::delete('tiquetes/{tiquete}', [TiqueteController::class, 'destroy'])
            ->whereNumber('tiquete')
            ->name('tiquetes.destroy');
    });

    Route::middleware('permission:buses.crear')->group(function () {
        Route::get('buses/create', [BusController::class, 'create'])->name('buses.create');
        Route::post('buses', [BusController::class, 'store'])->name('buses.store');
    });

    Route::middleware('permission:buses.ver')->group(function () {
        Route::get('buses', [BusController::class, 'index'])->name('buses.index');
        Route::get('buses/{bus}', [BusController::class, 'show'])
            ->whereNumber('bus')
            ->name('buses.show');
    });

    Route::middleware('permission:buses.editar')->group(function () {
        Route::get('buses/{bus}/edit', [BusController::class, 'edit'])
            ->whereNumber('bus')
            ->name('buses.edit');
        Route::put('buses/{bus}', [BusController::class, 'update'])
            ->whereNumber('bus')
            ->name('buses.update');
    });

    Route::middleware('permission:buses.eliminar')->group(function () {
        Route::delete('buses/{bus}', [BusController::class, 'destroy'])
            ->whereNumber('bus')
            ->name('buses.destroy');
    });

    Route::middleware('permission:buses.reactivar')->group(function () {
        Route::post('buses/{bus}/reactivar', [BusController::class, 'reactivar'])
            ->whereNumber('bus')
            ->name('buses.reactivar');
    });

    Route::middleware('permission:tipos_bus.crear')->group(function () {
        Route::get('tipos-bus/create', [TipoBusController::class, 'create'])->name('tipos-bus.create');
        Route::post('tipos-bus', [TipoBusController::class, 'store'])->name('tipos-bus.store');
    });

    Route::middleware('permission:tipos_bus.ver')->group(function () {
        Route::get('tipos-bus', [TipoBusController::class, 'index'])->name('tipos-bus.index');
        Route::get('tipos-bus/{tipo}', [TipoBusController::class, 'show'])
            ->whereNumber('tipo')
            ->name('tipos-bus.show');
    });

    Route::middleware('permission:tipos_bus.editar')->group(function () {
        Route::get('tipos-bus/{tipo}/edit', [TipoBusController::class, 'edit'])
            ->whereNumber('tipo')
            ->name('tipos-bus.edit');
        Route::put('tipos-bus/{tipo}', [TipoBusController::class, 'update'])
            ->whereNumber('tipo')
            ->name('tipos-bus.update');
    });

    Route::middleware('permission:tipos_bus.eliminar')->group(function () {
        Route::delete('tipos-bus/{tipo}', [TipoBusController::class, 'destroy'])
            ->whereNumber('tipo')
            ->name('tipos-bus.destroy');
    });

    Route::middleware('permission:rutas.crear')->group(function () {
        Route::get('rutas/create', [RutaController::class, 'create'])->name('rutas.create');
        Route::post('rutas', [RutaController::class, 'store'])->name('rutas.store');
    });

    Route::middleware('permission:rutas.ver')->group(function () {
        Route::get('rutas', [RutaController::class, 'index'])->name('rutas.index');
        Route::get('rutas/{ruta}', [RutaController::class, 'show'])
            ->whereNumber('ruta')
            ->name('rutas.show');
    });

    Route::middleware('permission:rutas.editar')->group(function () {
        Route::get('rutas/{ruta}/edit', [RutaController::class, 'edit'])
            ->whereNumber('ruta')
            ->name('rutas.edit');
        Route::put('rutas/{ruta}', [RutaController::class, 'update'])
            ->whereNumber('ruta')
            ->name('rutas.update');
    });

    Route::middleware('permission:rutas.eliminar')->group(function () {
        Route::delete('rutas/{ruta}', [RutaController::class, 'destroy'])
            ->whereNumber('ruta')
            ->name('rutas.destroy');
    });

    Route::middleware('permission:propietarios.crear')->group(function () {
        Route::get('propietarios/create', [PropietarioController::class, 'create'])->name('propietarios.create');
        Route::post('propietarios', [PropietarioController::class, 'store'])->name('propietarios.store');
    });

    Route::middleware('permission:propietarios.ver')->group(function () {
        Route::get('propietarios', [PropietarioController::class, 'index'])->name('propietarios.index');
        Route::get('propietarios/{propietario}', [PropietarioController::class, 'show'])
            ->whereNumber('propietario')
            ->name('propietarios.show');
    });

    Route::middleware('permission:propietarios.editar')->group(function () {
        Route::get('propietarios/{propietario}/edit', [PropietarioController::class, 'edit'])
            ->whereNumber('propietario')
            ->name('propietarios.edit');
        Route::put('propietarios/{propietario}', [PropietarioController::class, 'update'])
            ->whereNumber('propietario')
            ->name('propietarios.update');
    });

    Route::middleware('permission:propietarios.eliminar')->group(function () {
        Route::delete('propietarios/{propietario}', [PropietarioController::class, 'destroy'])
            ->whereNumber('propietario')
            ->name('propietarios.destroy');
    });

    Route::middleware('permission:conductores.crear')->group(function () {
        Route::get('conductores/create', [ConductorController::class, 'create'])->name('conductores.create');
        Route::post('conductores', [ConductorController::class, 'store'])->name('conductores.store');
    });

    Route::middleware('permission:conductores.ver')->group(function () {
        Route::get('conductores', [ConductorController::class, 'index'])->name('conductores.index');
        Route::get('conductores/{conductor}', [ConductorController::class, 'show'])
            ->whereNumber('conductor')
            ->name('conductores.show');
    });

    Route::middleware('permission:conductores.editar')->group(function () {
        Route::get('conductores/{conductor}/edit', [ConductorController::class, 'edit'])
            ->whereNumber('conductor')
            ->name('conductores.edit');
        Route::put('conductores/{conductor}', [ConductorController::class, 'update'])
            ->whereNumber('conductor')
            ->name('conductores.update');
    });

    Route::middleware('permission:conductores.eliminar')->group(function () {
        Route::delete('conductores/{conductor}', [ConductorController::class, 'destroy'])
            ->whereNumber('conductor')
            ->name('conductores.destroy');
    });

    Route::middleware('permission:reportes.ver')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes.index');

        Route::get('/reportes/viajes-hoy', [ReporteController::class, 'viajesHoy'])
            ->name('reportes.viajes-hoy');

        Route::get('/reportes/manifiesto/{viaje}', [ReporteController::class, 'manifiesto'])
            ->whereNumber('viaje')
            ->name('reportes.manifiesto');

        Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])
            ->name('reportes.ventas');

        Route::get('/reportes/ingresos-ruta', [ReporteController::class, 'ingresosPorRuta'])
            ->name('reportes.ingresos-ruta');

        Route::get('/reportes/ingresos-periodo', [ReporteController::class, 'ingresosPorPeriodo'])
            ->name('reportes.ingresos-periodo');

        Route::get('/reportes/viajes-cancelados', [ReporteController::class, 'viajesCancelados'])
            ->name('reportes.viajes-cancelados');

        Route::get('/reportes/utilidad-viaje', [ReporteController::class, 'utilidadPorViaje'])
            ->name('reportes.utilidad-viaje');
    });

});