<?php

namespace App\Http\Controllers;

use App\Models\TipoBus;
use Illuminate\Http\Request;

class TipoBusController extends Controller
{
    public function index()
    {
        $tiposBus = TipoBus::withCount('buses')->get();
        return view('tipos_bus.index', compact('tiposBus'));
    }

    public function create()
    {
        return view('tipos_bus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:100',
            'descripcion'        => 'nullable|string|max:255',
            'tiene_bano'         => 'nullable|boolean',
            'tiene_tv'           => 'nullable|boolean',
            'doble_piso'         => 'nullable|boolean',
            'columnas_izquierda' => 'required|integer|min:1|max:3',
            'columnas_derecha'   => 'required|integer|min:1|max:3',
            'capacidad_default'  => 'nullable|integer|min:1|max:80',
        ]);

        TipoBus::create([
            'nombre'             => $request->nombre,
            'descripcion'        => $request->descripcion,
            'tiene_bano'         => $request->boolean('tiene_bano'),
            'tiene_tv'           => $request->boolean('tiene_tv'),
            'doble_piso'         => $request->boolean('doble_piso'),
            'columnas_izquierda' => $request->columnas_izquierda,
            'columnas_derecha'   => $request->columnas_derecha,
            'capacidad_default'  => $request->capacidad_default ?: null,
        ]);

        return redirect()->route('tipos-bus.index')
            ->with('success', 'Tipo de bus creado exitosamente.');
    }

    public function edit(TipoBus $tiposBu)
    {
        return view('tipos_bus.edit', ['tipoBus' => $tiposBu]);
    }

    public function update(Request $request, TipoBus $tiposBu)
    {
        $request->validate([
            'nombre'             => 'required|string|max:100',
            'descripcion'        => 'nullable|string|max:255',
            'tiene_bano'         => 'nullable|boolean',
            'tiene_tv'           => 'nullable|boolean',
            'doble_piso'         => 'nullable|boolean',
            'columnas_izquierda' => 'required|integer|min:1|max:3',
            'columnas_derecha'   => 'required|integer|min:1|max:3',
            'capacidad_default'  => 'nullable|integer|min:1|max:80',
        ]);

        $tiposBu->update([
            'nombre'             => $request->nombre,
            'descripcion'        => $request->descripcion,
            'tiene_bano'         => $request->boolean('tiene_bano'),
            'tiene_tv'           => $request->boolean('tiene_tv'),
            'doble_piso'         => $request->boolean('doble_piso'),
            'columnas_izquierda' => $request->columnas_izquierda,
            'columnas_derecha'   => $request->columnas_derecha,
            'capacidad_default'  => $request->capacidad_default ?: null,
        ]);

        return redirect()->route('tipos-bus.index')
            ->with('success', 'Tipo de bus actualizado exitosamente.');
    }

    public function destroy(TipoBus $tiposBu)
    {
        if ($tiposBu->buses()->count() > 0) {
            return redirect()->route('tipos-bus.index')
                ->with('error', 'No se puede eliminar: hay buses asociados a este tipo.');
        }

        $tiposBu->delete();

        return redirect()->route('tipos-bus.index')
            ->with('success', 'Tipo de bus eliminado.');
    }
}
