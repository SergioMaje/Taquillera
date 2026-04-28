<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Ruta;
use App\Models\Bus;
use App\Models\TipoBus;
use App\Models\Conductor;
use App\Models\AsientoViaje;
use Illuminate\Http\Request;

class ViajeController extends Controller
{
    public function index()
    {
        $viajes = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus.tipoBus',
            'conductor',
        ])->whereNotIn('estado', ['cancelado', 'completado'])->get();

        $totalCancelados = Viaje::where('estado', 'cancelado')->count();

        return view('viajes.index', compact('viajes', 'totalCancelados'));
    }

    public function cancelados()
    {
        $viajes = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus.tipoBus',
        ])->where('estado', 'cancelado')->get();

        return view('viajes.cancelados', compact('viajes'));
    }

    public function renovar(Viaje $viaje)
    {
        if ($viaje->estado !== 'cancelado') {
            return redirect()->route('viajes.cancelados')
                ->with('error', 'Solo se pueden renovar viajes cancelados.');
        }

        $viaje->update(['estado' => 'programado']);

        return redirect()->route('viajes.cancelados')
            ->with('success', 'Viaje renovado y vuelto a programar exitosamente.');
    }

    public function create()
    {
        $rutas       = Ruta::with(['municipioOrigen', 'municipioDestino'])->get();
        $buses       = Bus::activos()->with('tipoBus')->get();
        $tiposBus    = TipoBus::all();
        $conductores = Conductor::orderBy('nombre')->get();

        return view('viajes.create', compact('rutas', 'buses', 'tiposBus', 'conductores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ruta'        => 'required|exists:rutas,id_ruta',
            'id_bus'         => 'required|exists:buses,id_bus',
            'id_conductor'   => 'required|exists:conductores,id_conductor',
            'fecha_salida'   => 'required|date',
            'hora_salida'    => 'required',
            'precio_base'    => 'required|numeric|min:0',
            'precio_final'   => 'required|numeric|min:0',
            'estado'         => 'required|in:programado,en_ruta,completado,cancelado',
        ]);

        $bus = Bus::with('asientos')->findOrFail($request->id_bus);

        $viaje = Viaje::create([
            'id_bus'          => $bus->id_bus,
            'id_conductor'    => $request->id_conductor,
            'id_ruta'         => $request->id_ruta,
            'fecha_salida'    => $request->fecha_salida,
            'hora_salida'     => $request->hora_salida,
            'precio_base'     => $request->precio_base,
            'precio_final'    => $request->precio_final,
            'estado'          => $request->estado,
            'estado_real'     => null,
            'asientos_libres' => $bus->asientos->count(),
        ]);

        foreach ($bus->asientos as $asiento) {
            AsientoViaje::create([
                'id_viaje'   => $viaje->id_viaje,
                'id_asiento' => $asiento->id_asiento,
                'estado'     => 'disponible',
            ]);
        }

        return redirect()->route('viajes.index')
            ->with('success', 'Viaje creado exitosamente.');
    }

    public function show(Viaje $viaje)
    {
        $viaje->load([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'bus.tipoBus',
            'conductor',
            'costos',
            'asientosViaje.tiquetes',
        ]);

        $totalIngresos = $viaje->asientosViaje
            ->flatMap(fn($av) => $av->tiquetes)
            ->sum('precio_final');

        $totalCostos = $viaje->costos->sum('monto');

        return view('viajes.show', compact('viaje', 'totalIngresos', 'totalCostos'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function completar(Viaje $viaje)
    {
        if (in_array($viaje->estado, ['completado', 'cancelado'])) {
            return redirect()->route('viajes.index')
                ->with('error', 'No se puede completar un viaje ' . $viaje->estado . '.');
        }

        $viaje->update(['estado' => 'completado']);

        return redirect()->route('viajes.index')
            ->with('success', 'Viaje marcado como completado. El reporte quedó registrado.');
    }

    public function cancelar(Viaje $viaje)
    {
        if (in_array($viaje->estado, ['completado', 'cancelado'])) {
            return redirect()->route('viajes.index')
                ->with('error', 'No se puede cancelar un viaje ' . $viaje->estado . '.');
        }

        $viaje->update(['estado' => 'cancelado']);

        return redirect()->route('viajes.index')
            ->with('success', 'Viaje cancelado exitosamente.');
    }

    public function destroy(string $id)
    {
        //
    }
}
