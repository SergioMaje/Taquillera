<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::with([
            'departamentoOrigen', 'municipioOrigen',
            'departamentoDestino', 'municipioDestino',
        ])->withCount('viajes')->get();

        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        $huila = Departamento::first();
        $municipios = Municipio::orderBy('nombre')->get();

        return view('rutas.create', compact('huila', 'municipios'));
    }

    public function store(Request $request)
    {
        $huila = Departamento::first();

        $request->validate([
            'id_municipio_origen'  => 'required|exists:municipios,id_municipio',
            'id_municipio_destino' => 'required|exists:municipios,id_municipio',
            'duracion_estimada'    => 'required|integer|min:1',
        ]);

        Ruta::create([
            'id_departamento_origen'  => $huila->id_departamento,
            'id_municipio_origen'     => $request->id_municipio_origen,
            'id_departamento_destino' => $huila->id_departamento,
            'id_municipio_destino'    => $request->id_municipio_destino,
            'duracion_estimada'       => $request->duracion_estimada,
        ]);

        return redirect()->route('rutas.index')->with('success', 'Ruta creada exitosamente.');
    }

    public function edit(Ruta $ruta)
    {
        $huila = Departamento::first();
        $municipios = Municipio::orderBy('nombre')->get();

        return view('rutas.edit', compact('ruta', 'huila', 'municipios'));
    }

    public function update(Request $request, Ruta $ruta)
    {
        $huila = Departamento::first();

        $request->validate([
            'id_municipio_origen'  => 'required|exists:municipios,id_municipio',
            'id_municipio_destino' => 'required|exists:municipios,id_municipio',
            'duracion_estimada'    => 'required|integer|min:1',
        ]);

        $ruta->update([
            'id_departamento_origen'  => $huila->id_departamento,
            'id_municipio_origen'     => $request->id_municipio_origen,
            'id_departamento_destino' => $huila->id_departamento,
            'id_municipio_destino'    => $request->id_municipio_destino,
            'duracion_estimada'       => $request->duracion_estimada,
        ]);

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy(Ruta $ruta)
    {
        if ($ruta->viajes()->count() > 0) {
            return redirect()->route('rutas.index')
                ->with('error', 'No se puede eliminar: hay viajes asociados a esta ruta.');
        }

        $ruta->delete();

        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada.');
    }
}
