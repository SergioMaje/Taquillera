@extends('layouts.app')

@section('title', 'Rutas')

@push('styles')
<style>
    .page-header-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; margin-bottom: 0; }

    .btn-new {
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.2s;
    }
    .btn-new:hover { background: #1e40af; }

    .route-arrow { color: #9ca3af; margin: 0 4px; }

    .btn-edit {
        color: #1d4ed8;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .btn-edit:hover { text-decoration: underline; }

    .btn-delete {
        background: none;
        border: none;
        color: #ef4444;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
    }
    .btn-delete:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div class="page-header">
        <h1>Rutas</h1>
    </div>
    <a href="{{ route('rutas.create') }}" class="btn-new">+ Nueva Ruta</a>
</div>

@if($rutas->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        No hay rutas registradas.
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Duración</th>
                <th>Viajes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rutas as $ruta)
            <tr>
                <td>{{ $ruta->id_ruta }}</td>
                <td>
                    <span style="color:#6b7280; font-size:0.8rem;">{{ $ruta->departamentoOrigen->nombre }}</span><br>
                    <strong>{{ $ruta->municipioOrigen->nombre }}</strong>
                </td>
                <td>
                    <span style="color:#6b7280; font-size:0.8rem;">{{ $ruta->departamentoDestino->nombre }}</span><br>
                    <strong>{{ $ruta->municipioDestino->nombre }}</strong>
                </td>
                <td>{{ $ruta->duracion_estimada }} min</td>
                <td>{{ $ruta->viajes_count }}</td>
                <td style="display:flex; gap:12px; align-items:center;">
                    <a href="{{ route('rutas.edit', $ruta->id_ruta) }}" class="btn-edit">Editar</a>
                    <form method="POST" action="{{ route('rutas.destroy', $ruta->id_ruta) }}" onsubmit="return confirm('¿Eliminar esta ruta?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
