<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Tiquete;
use App\Models\Orden;
use App\Models\AsientoViaje;
use App\Models\User;

class TiqueteController extends Controller
{
    public function index()
    {
        $tiquetes = Tiquete::with([
            'orden.usuario',
            'asientoViaje.asiento',
            'asientoViaje.viaje.ruta.municipioOrigen',
            'asientoViaje.viaje.ruta.municipioDestino',
        ])->get();

        return view('tiquetes.index', compact('tiquetes'));
    }

    public function create()
    {
        $viajes = Viaje::with([
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
            'asientosViaje' => fn($q) => $q->where('estado', 'disponible')->with('asiento'),
        ])->get();

        return view('tiquetes.create', compact('viajes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_viaje'                          => 'required|exists:viajes,id_viaje',
            'id_asientos'                       => 'required|array|min:1',
            'id_asientos.*'                     => 'exists:asientos_viaje,id_asiento_viaje',
            'nombre_comprador'                  => 'required|string|max:255',
            'email_comprador'                   => 'required|email',
            'pasajeros'                         => 'required|array',
            'pasajeros.*.nombre_pasajero'       => 'required|string|max:255',
            'pasajeros.*.documento_pasajero'    => 'required|string|max:20',
        ]);

        $viaje = Viaje::findOrFail($request->id_viaje);

        $asientosViaje = AsientoViaje::whereIn('id_asiento_viaje', $request->id_asientos)
            ->where('id_viaje', $request->id_viaje)
            ->where('estado', 'disponible')
            ->get();

        if ($asientosViaje->count() !== count($request->id_asientos)) {
            return back()->withErrors(['id_asientos' => 'Uno o más asientos seleccionados ya no están disponibles.'])->withInput();
        }

        $comprador = User::firstOrCreate(
            ['email' => $request->email_comprador],
            ['nombre' => $request->nombre_comprador, 'password' => bcrypt('temporal')]
        );

        $cantidad = $asientosViaje->count();

        $orden = Orden::create([
            'id_usuario'  => $comprador->id_usuario,
            'fecha_orden' => now(),
            'estado'      => 'pendiente',
            'total'       => $viaje->precio_final * $cantidad,
        ]);

        foreach ($asientosViaje as $asientoViaje) {
            $idAv      = $asientoViaje->id_asiento_viaje;
            $pasajero  = $request->pasajeros[$idAv] ?? [];

            Tiquete::create([
                'id_orden'           => $orden->id_orden,
                'id_asiento_viaje'   => $idAv,
                'nombre_pasajero'    => $pasajero['nombre_pasajero'] ?? null,
                'documento_pasajero' => $pasajero['documento_pasajero'] ?? null,
                'fecha_compra'       => now(),
                'estado'             => 'pagado',
                'precio_base'        => $viaje->precio_base,
                'precio_final'       => $viaje->precio_final,
            ]);

            $asientoViaje->update(['estado' => 'ocupado']);
        }

        $viaje->decrement('asientos_libres', $cantidad);

        return redirect()->route('tiquetes.index')->with('success', "¡Venta realizada! {$cantidad} tiquete(s) generados.");
    }

    public function show($id)
    {
        $tiquete = Tiquete::with(['orden.usuario', 'asientoViaje.asiento.tipoAsiento', 'asientoViaje.viaje.ruta'])->findOrFail($id);

        return view('tiquetes.show', compact('tiquete'));
    }

    public function destroy(Tiquete $tiquete)
    {
        $asientoViaje = $tiquete->asientoViaje;
        $viaje        = $asientoViaje->viaje;
        $orden        = $tiquete->orden;

        $tiquete->delete();

        $asientoViaje->update(['estado' => 'disponible']);
        $viaje->increment('asientos_libres');

        // Eliminar la orden si ya no tiene tiquetes
        if ($orden->tiquetes()->count() === 0) {
            $orden->delete();
        }

        return redirect()->route('tiquetes.index')
            ->with('success', 'Tiquete eliminado y asiento liberado.');
    }
}
