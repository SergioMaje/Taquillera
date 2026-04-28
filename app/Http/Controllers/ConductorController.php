<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::withCount('viajes')->get();
        return view('conductores.index', compact('conductores'));
    }

    public function create()
    {
        return view('conductores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'cedula'   => 'required|string|max:20|unique:conductores,cedula',
            'licencia' => 'required|string|max:30|unique:conductores,licencia',
            'telefono' => 'nullable|string|max:20',
        ]);

        Conductor::create($request->only('nombre', 'cedula', 'licencia', 'telefono'));

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor registrado exitosamente.');
    }

    public function edit(Conductor $conductor)
    {
        return view('conductores.edit', compact('conductor'));
    }

    public function update(Request $request, Conductor $conductor)
    {
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'cedula'   => 'required|string|max:20|unique:conductores,cedula,' . $conductor->id_conductor . ',id_conductor',
            'licencia' => 'required|string|max:30|unique:conductores,licencia,' . $conductor->id_conductor . ',id_conductor',
            'telefono' => 'nullable|string|max:20',
        ]);

        $conductor->update($request->only('nombre', 'cedula', 'licencia', 'telefono'));

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor actualizado exitosamente.');
    }

    public function destroy(Conductor $conductor)
    {
        if ($conductor->viajes()->exists()) {
            return redirect()->route('conductores.index')
                ->with('error', 'No se puede eliminar: este conductor tiene viajes asociados.');
        }

        $conductor->delete();

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor eliminado exitosamente.');
    }
}
