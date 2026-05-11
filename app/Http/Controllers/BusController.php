<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\TipoBus;
use App\Models\Asiento;
use App\Models\Propietario;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index(Request $request)
    {
        $verInactivos = $request->boolean('inactivos');

        $buses = Bus::with(['tipoBus', 'propietario'])
            ->withCount('asientos')
            ->where('activo', !$verInactivos)
            ->get();

        $totalInactivos = Bus::where('activo', false)->count();

        return view('buses.index', compact('buses', 'verInactivos', 'totalInactivos'));
    }

    public function create()
    {
        $tiposBus     = TipoBus::all();
        $propietarios = Propietario::orderBy('nombre')->get();
        return view('buses.create', compact('tiposBus', 'propietarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa'          => 'required|string|max:20|unique:buses,placa',
            'id_tipo_bus'    => 'required|exists:tipos_bus,id_tipo_bus',
            'id_propietario' => 'required|exists:propietarios,id_propietario',
            'capacidad'      => 'required|integer|min:1|max:80',
        ]);

        $bus = Bus::create([
            'placa'          => strtoupper(trim($request->placa)),
            'id_tipo_bus'    => $request->id_tipo_bus,
            'id_propietario' => $request->id_propietario,
            'capacidad'      => $request->capacidad,
        ]);

        $bus->load('tipoBus');
        $this->crearAsientos($bus, $request->capacidad);

        return redirect()->route('buses.index')
            ->with('success', "Bus {$bus->placa} creado con {$request->capacidad} asientos.");
    }

    public function show(Bus $bus)
    {
        $bus->load(['tipoBus', 'asientos.tipoAsiento', 'propietario']);
        return view('buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        $bus->load('asientos');
        $tiposBus     = TipoBus::all();
        $propietarios = Propietario::orderBy('nombre')->get();
        return view('buses.edit', compact('bus', 'tiposBus', 'propietarios'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'placa'          => 'required|string|max:20|unique:buses,placa,' . $bus->id_bus . ',id_bus',
            'id_tipo_bus'    => 'required|exists:tipos_bus,id_tipo_bus',
            'id_propietario' => 'required|exists:propietarios,id_propietario',
            'capacidad'      => 'required|integer|min:1|max:80',
        ]);

        $capacidadAnterior = $bus->capacidad;

        $bus->update([
            'placa'          => strtoupper(trim($request->placa)),
            'id_tipo_bus'    => $request->id_tipo_bus,
            'id_propietario' => $request->id_propietario,
            'capacidad'      => $request->capacidad,
        ]);

        if ($request->capacidad != $capacidadAnterior) {
            $bus->asientos()->delete();
            $bus->load('tipoBus');
            $this->crearAsientos($bus, $request->capacidad);
        }

        return redirect()->route('buses.index')
            ->with('success', "Bus {$bus->placa} actualizado.");
    }

    public function destroy(Bus $bus)
    {
        if ($bus->viajes()->whereIn('estado', ['programado', 'en_ruta'])->exists()) {
            return redirect()->route('buses.index')
                ->with('error', "No se puede dar de baja el bus {$bus->placa} porque tiene viajes activos.");
        }

        $tieneHistorial = $bus->viajes()->whereHas('asientosViaje.tiquetes')->exists();

        if ($tieneHistorial) {
            $bus->update(['activo' => false]);
            return redirect()->route('buses.index')
                ->with('success', "Bus {$bus->placa} dado de baja. Su historial de tiquetes se conserva.");
        }

        // Sin tiquetes vendidos: eliminación física
        $bus->viajes()->delete();
        $placa = $bus->placa;
        $bus->delete();

        return redirect()->route('buses.index')
            ->with('success', "Bus {$placa} eliminado.");
    }

    public function reactivar(Bus $bus)
    {
        $bus->update(['activo' => true]);
        return redirect()->route('buses.index', ['inactivos' => 1])
            ->with('success', "Bus {$bus->placa} reactivado.");
    }

    private function crearAsientos(Bus $bus, int $capacidad): void
    {
        $tipoBus  = $bus->tipoBus;
        $colIzq   = $tipoBus->columnas_izquierda;
        $colDer   = $tipoBus->columnas_derecha;
        $colTotal = $colIzq + $colDer;

        // Mapa col_index → pos_x (izquierda sin salto, derecha salta el pasillo)
        $posXMap = [];
        for ($c = 0; $c < $colIzq; $c++) {
            $posXMap[$c] = $c;
        }
        for ($c = 0; $c < $colDer; $c++) {
            $posXMap[$colIzq + $c] = $colIzq + 1 + $c;
        }

        // Distribución por pisos
        if ($tipoBus->doble_piso) {
            $s1 = (int) ceil($capacidad / 2);
            $floors = [1 => $s1, 2 => $capacidad - $s1];
        } else {
            $floors = [1 => $capacidad];
        }

        $numero = 1;
        foreach ($floors as $piso => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $colIndex = ($i - 1) % $colTotal;
                $fila     = (int) ceil($i / $colTotal);

                Asiento::create([
                    'id_bus'          => $bus->id_bus,
                    'numero'          => $numero,
                    'pos_x'           => $posXMap[$colIndex],
                    'pos_y'           => $fila,
                    'piso'            => $piso,
                    'id_tipo_asiento' => null,
                ]);
                $numero++;
            }
        }
    }
}
