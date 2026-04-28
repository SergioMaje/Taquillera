<?php

namespace App\Http\Controllers;

use App\Models\CostoViaje;
use App\Models\Viaje;
use Illuminate\Http\Request;

class CostoViajeController extends Controller
{
    public function store(Request $request, Viaje $viaje)
    {
        $request->validate([
            'concepto'    => 'required|in:combustible,peajes,conductor,mantenimiento,otros',
            'descripcion' => 'nullable|string|max:255',
            'monto'       => 'required|numeric|min:0',
        ]);

        CostoViaje::create([
            'id_viaje'    => $viaje->id_viaje,
            'concepto'    => $request->concepto,
            'descripcion' => $request->descripcion,
            'monto'       => $request->monto,
        ]);

        return redirect()->route('viajes.show', $viaje->id_viaje)
            ->with('success', 'Costo registrado.');
    }

    public function destroy(Viaje $viaje, CostoViaje $costo)
    {
        $costo->delete();

        return redirect()->route('viajes.show', $viaje->id_viaje)
            ->with('success', 'Costo eliminado.');
    }
}
