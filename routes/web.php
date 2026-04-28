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

    Route::get('viajes/cancelados', [ViajeController::class, 'cancelados'])->name('viajes.cancelados');
    Route::resource('viajes', ViajeController::class);
    Route::post('viajes/{viaje}/costos', [CostoViajeController::class, 'store'])->name('viajes.costos.store');
    Route::delete('viajes/{viaje}/costos/{costo}', [CostoViajeController::class, 'destroy'])->name('viajes.costos.destroy');
    Route::patch('viajes/{viaje}/cancelar', [ViajeController::class, 'cancelar'])->name('viajes.cancelar');
    Route::patch('viajes/{viaje}/renovar', [ViajeController::class, 'renovar'])->name('viajes.renovar');
    Route::patch('viajes/{viaje}/completar', [ViajeController::class, 'completar'])->name('viajes.completar');
    Route::resource('tiquetes', TiqueteController::class);
    Route::resource('tipos-bus', TipoBusController::class);
    Route::resource('buses', BusController::class);
    Route::post('buses/{bus}/reactivar', [BusController::class, 'reactivar'])->name('buses.reactivar');
    Route::resource('rutas', RutaController::class);
    Route::resource('propietarios', PropietarioController::class);
    Route::resource('conductores', ConductorController::class);

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    // Operativos
    Route::get('/reportes/viajes-hoy', [ReporteController::class, 'viajesHoy'])->name('reportes.viajes-hoy');
    Route::get('/reportes/manifiesto/{viaje}', [ReporteController::class, 'manifiesto'])->name('reportes.manifiesto');
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    // Financieros
    Route::get('/reportes/ingresos-ruta', [ReporteController::class, 'ingresosPorRuta'])->name('reportes.ingresos-ruta');
    Route::get('/reportes/ingresos-periodo', [ReporteController::class, 'ingresosPorPeriodo'])->name('reportes.ingresos-periodo');
    Route::get('/reportes/viajes-cancelados', [ReporteController::class, 'viajesCancelados'])->name('reportes.viajes-cancelados');
    Route::get('/reportes/utilidad-viaje', [ReporteController::class, 'utilidadPorViaje'])->name('reportes.utilidad-viaje');

    Route::get('/taquilla/recibo/{tiquete}', [TaquillaController::class, 'recibo'])->name('taquilla.recibo');
    Route::get('/taquilla/{viaje}', [TaquillaController::class, 'verBus'])->name('taquilla');
    Route::get('/taquilla/{viaje}/confirmar', [TaquillaController::class, 'confirmar'])->name('taquilla.confirmar');
    Route::post('/vender-tiquete', [TaquillaController::class, 'comprar'])->name('taquilla.comprar');
});
