<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Asiento;
use App\Models\AsientoViaje;
use App\Models\Orden;
use App\Models\Tiquete;
use App\Models\Usuario;

class TaquillaController extends Controller
{
    public function verBus(Viaje $viaje)
    {
        $viaje->load([
            'bus.tipoBus',
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
        ]);

        $asientosViaje = AsientoViaje::where('id_viaje', $viaje->id_viaje)
            ->with('asiento.tipoAsiento')
            ->get()
            ->sortBy('asiento.numero')
            ->values();

        return view('taquilla', compact('viaje', 'asientosViaje'));
    }

    public function confirmar(Request $request, Viaje $viaje)
    {
        $viaje->load([
            'bus.tipoBus',
            'ruta.municipioOrigen',
            'ruta.municipioDestino',
        ]);

        $numeroPuesto = $request->query('numero_puesto');

        $asiento = Asiento::where('id_bus', $viaje->bus->id_bus)
            ->where('numero', $numeroPuesto)
            ->firstOrFail();

        $asientoViaje = AsientoViaje::where('id_viaje', $viaje->id_viaje)
            ->where('id_asiento', $asiento->id_asiento)
            ->where('estado', 'disponible')
            ->firstOrFail();

        return view('taquilla.confirmar', compact('viaje', 'asiento', 'asientoViaje'));
    }

    public function comprar(Request $request)
    {
        $request->validate([
            'id_asiento_viaje' => 'required|exists:asientos_viaje,id_asiento_viaje',
            'viaje_id'         => 'required|exists:viajes,id_viaje',
            'nombre'           => 'required|string|max:150',
            'cedula'           => 'required|string|max:20',
            'email'            => 'required|email|max:150',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $asientoViaje = AsientoViaje::where('id_asiento_viaje', $request->id_asiento_viaje)
            ->where('estado', 'disponible')
            ->firstOrFail();

        $viaje = Viaje::findOrFail($request->viaje_id);

        $usuario = Usuario::firstOrCreate(
            ['email' => $request->email],
            [
                'nombre'   => $request->nombre,
                'cedula'   => $request->cedula,
                'telefono' => $request->telefono,
                'password' => bcrypt(\Illuminate\Support\Str::random(12)),
            ]
        );

        $orden = Orden::create([
            'id_usuario'  => $usuario->id_usuario,
            'fecha_orden' => now()->toDateString(),
            'estado'      => 'pagada',
            'total'       => $viaje->precio_final,
        ]);

        $tiquete = Tiquete::create([
            'id_orden'           => $orden->id_orden,
            'id_asiento_viaje'   => $asientoViaje->id_asiento_viaje,
            'nombre_pasajero'    => $request->nombre,
            'documento_pasajero' => $request->cedula,
            'fecha_compra'       => now()->toDateString(),
            'estado'             => 'vendido',
            'precio_base'        => $viaje->precio_base,
            'precio_final'       => $viaje->precio_final,
        ]);

        $asientoViaje->update(['estado' => 'ocupado']);
        $viaje->decrement('asientos_libres');

        return redirect()->route('taquilla.recibo', $tiquete->id_tiquete)
            ->with('success', '¡Tiquete vendido exitosamente!');
    }

    public function recibo(Tiquete $tiquete)
    {
        $tiquete->load([
            'orden.usuario',
            'asientoViaje.asiento',
            'asientoViaje.viaje.ruta.municipioOrigen',
            'asientoViaje.viaje.ruta.municipioDestino',
            'asientoViaje.viaje.bus',
        ]);

        return view('taquilla.recibo', compact('tiquete'));
    }
}
