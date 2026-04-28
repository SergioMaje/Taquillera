<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Tiquete;
use App\Models\CostoViaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    // Reporte 1: Viajes del día con ocupación
    public function viajesHoy()
    {
        $fecha = request('fecha');

        $query = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus',
            'conductor',
        ]);

        if ($fecha) {
            $query->whereDate('fecha_salida', $fecha);
        }

        $viajes = $query->orderBy('fecha_salida', 'desc')
            ->orderBy('hora_salida')
            ->get()
            ->map(function ($viaje) {
                $total    = $viaje->bus->capacidad ?? 0;
                $libres   = $viaje->asientos_libres;
                $ocupados = $total - $libres;
                $viaje->total_asientos = $total;
                $viaje->ocupados       = $ocupados;
                $viaje->pct_ocupacion  = $total > 0 ? round(($ocupados / $total) * 100) : 0;
                return $viaje;
            });

        return view('reportes.viajes_hoy', compact('viajes', 'fecha'));
    }

    // Reporte 2: Manifiesto de pasajeros de un viaje
    public function manifiesto(Viaje $viaje)
    {
        $viaje->load([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus',
            'conductor',
            'asientosViaje.asiento',
            'asientosViaje.tiquetes',
        ]);

        $pasajeros = $viaje->asientosViaje
            ->where('estado', 'ocupado')
            ->map(function ($av) {
                $tiquete = $av->tiquetes->first();
                return (object) [
                    'asiento'   => $av->asiento->numero ?? '—',
                    'nombre'    => $tiquete->nombre_pasajero ?? '—',
                    'documento' => $tiquete->documento_pasajero ?? '—',
                    'precio'    => $tiquete->precio_final ?? 0,
                ];
            })
            ->sortBy('asiento')
            ->values();

        $totalRecaudado = $pasajeros->sum('precio');

        return view('reportes.manifiesto', compact('viaje', 'pasajeros', 'totalRecaudado'));
    }

    // Reporte financiero 1: Ingresos por ruta
    public function ingresosPorRuta(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = DB::table('tiquetes')
            ->join('asientos_viaje', 'tiquetes.id_asiento_viaje', '=', 'asientos_viaje.id_asiento_viaje')
            ->join('viajes',         'asientos_viaje.id_viaje',   '=', 'viajes.id_viaje')
            ->join('rutas',          'viajes.id_ruta',            '=', 'rutas.id_ruta')
            ->join('municipios as mo', 'rutas.id_municipio_origen',  '=', 'mo.id_municipio')
            ->join('municipios as md', 'rutas.id_municipio_destino', '=', 'md.id_municipio')
            ->selectRaw('
                rutas.id_ruta,
                mo.nombre as origen,
                md.nombre as destino,
                COUNT(tiquetes.id_tiquete)   as total_tiquetes,
                SUM(tiquetes.precio_final)   as total_ingresos,
                AVG(tiquetes.precio_final)   as precio_promedio,
                COUNT(DISTINCT asientos_viaje.id_viaje) as total_viajes
            ');

        if ($desde) $query->whereDate('tiquetes.fecha_compra', '>=', $desde);
        if ($hasta) $query->whereDate('tiquetes.fecha_compra', '<=', $hasta);

        $rutas = $query->groupBy('rutas.id_ruta', 'mo.nombre', 'md.nombre')
            ->orderByDesc('total_ingresos')
            ->get();

        $totalGeneral = $rutas->sum('total_ingresos');

        return view('reportes.ingresos_ruta', compact('rutas', 'totalGeneral', 'desde', 'hasta'));
    }

    // Reporte financiero 2: Ingresos por período (agrupado por día)
    public function ingresosPorPeriodo(Request $request)
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $dias = DB::table('tiquetes')
            ->selectRaw('DATE(fecha_compra) as fecha, COUNT(*) as tiquetes, SUM(precio_final) as total')
            ->whereDate('fecha_compra', '>=', $desde)
            ->whereDate('fecha_compra', '<=', $hasta)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $totalRecaudado  = $dias->sum('total');
        $totalTiquetes   = $dias->sum('tiquetes');
        $promedioDiario  = $dias->count() > 0 ? $totalRecaudado / $dias->count() : 0;

        return view('reportes.ingresos_periodo', compact('dias', 'totalRecaudado', 'totalTiquetes', 'promedioDiario', 'desde', 'hasta'));
    }

    // Reporte financiero 3: Viajes cancelados e impacto
    public function viajesCancelados()
    {
        $viajes = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus',
            'conductor',
            'asientosViaje.tiquetes',
        ])
        ->where('estado', 'cancelado')
        ->orderBy('fecha_salida', 'desc')
        ->get()
        ->map(function ($viaje) {
            $tiquetesVendidos = $viaje->asientosViaje
                ->flatMap(fn($av) => $av->tiquetes);
            $viaje->tiquetes_count    = $tiquetesVendidos->count();
            $viaje->ingresos_afectados = $tiquetesVendidos->sum('precio_final');
            return $viaje;
        });

        $totalAfectado = $viajes->sum('ingresos_afectados');

        return view('reportes.viajes_cancelados', compact('viajes', 'totalAfectado'));
    }

    // Reporte financiero 4: Utilidad por viaje (ingresos - costos)
    public function utilidadPorViaje(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus',
            'conductor',
            'costos',
            'asientosViaje.tiquetes',
        ]);

        if ($desde) $query->whereDate('fecha_salida', '>=', $desde);
        if ($hasta) $query->whereDate('fecha_salida', '<=', $hasta);

        $viajes = $query->orderBy('fecha_salida', 'desc')->get()->map(function ($viaje) {
            $ingresos = $viaje->asientosViaje->flatMap(fn($av) => $av->tiquetes)->sum('precio_final');
            $costos   = $viaje->costos->sum('monto');
            $viaje->total_ingresos = $ingresos;
            $viaje->total_costos   = $costos;
            $viaje->utilidad       = $ingresos - $costos;
            return $viaje;
        });

        $totalIngresos = $viajes->sum('total_ingresos');
        $totalCostos   = $viajes->sum('total_costos');
        $totalUtilidad = $viajes->sum('utilidad');

        return view('reportes.utilidad_viaje', compact(
            'viajes', 'totalIngresos', 'totalCostos', 'totalUtilidad', 'desde', 'hasta'
        ));
    }

    // Reporte 3: Tiquetes vendidos por rango de fechas
    public function ventas(Request $request)
    {
        $desde = $request->input('desde', now()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $tiquetes = Tiquete::with([
            'asientoViaje.viaje.ruta.municipioOrigen',
            'asientoViaje.viaje.ruta.municipioDestino',
            'asientoViaje.asiento',
        ])
        ->whereDate('fecha_compra', '>=', $desde)
        ->whereDate('fecha_compra', '<=', $hasta)
        ->orderBy('fecha_compra', 'desc')
        ->get();

        $totalRecaudado = $tiquetes->sum('precio_final');

        return view('reportes.ventas', compact('tiquetes', 'totalRecaudado', 'desde', 'hasta'));
    }
}
