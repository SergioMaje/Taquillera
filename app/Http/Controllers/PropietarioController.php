<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    public function index()
    {
        $propietarios = Propietario::withCount('buses')->get();
        return view('propietarios.index', compact('propietarios'));
    }

    public function create()
    {
        return view('propietarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'       => 'required|in:empresa,socio',
            'nombre'     => 'required|string|max:150',
            'cedula_nit' => 'required|string|max:20|unique:propietarios,cedula_nit',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:100',
        ]);

        Propietario::create($request->only('tipo', 'nombre', 'cedula_nit', 'telefono', 'email'));

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario registrado exitosamente.');
    }

    public function edit(Propietario $propietario)
    {
        return view('propietarios.edit', compact('propietario'));
    }

    public function update(Request $request, Propietario $propietario)
    {
        $request->validate([
            'tipo'       => 'required|in:empresa,socio',
            'nombre'     => 'required|string|max:150',
            'cedula_nit' => 'required|string|max:20|unique:propietarios,cedula_nit,' . $propietario->id_propietario . ',id_propietario',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:100',
        ]);

        $propietario->update($request->only('tipo', 'nombre', 'cedula_nit', 'telefono', 'email'));

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario actualizado exitosamente.');
    }

    public function destroy(Propietario $propietario)
    {
        if ($propietario->buses()->exists()) {
            return redirect()->route('propietarios.index')
                ->with('error', 'No se puede eliminar: este propietario tiene buses asociados.');
        }

        $propietario->delete();

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario eliminado exitosamente.');
    }
}
