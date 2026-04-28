@extends('layouts.app')

@section('title', 'Conductores')

@push('styles')
<style>
    .page-header-row {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; margin-bottom: 24px;
    }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; margin-bottom: 0; }

    .btn-new {
        background: #1d4ed8; color: #fff; border: none; border-radius: 8px;
        padding: 10px 20px; font-size: 0.875rem; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap; flex-shrink: 0; transition: background 0.2s;
    }
    .btn-new:hover { background: #1e40af; }

    .btn-edit { color: #1d4ed8; text-decoration: none; font-size: 0.85rem; font-weight: 500; }
    .btn-edit:hover { text-decoration: underline; }
    .btn-delete {
        background: none; border: none; color: #ef4444;
        font-size: 0.85rem; font-weight: 500; cursor: pointer; padding: 0;
    }
    .btn-delete:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div class="page-header"><h1>Conductores</h1></div>
    <a href="{{ route('conductores.create') }}" class="btn-new">+ Nuevo Conductor</a>
</div>

@if($conductores->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        No hay conductores registrados.
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Licencia</th>
                <th>Teléfono</th>
                <th>Viajes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conductores as $conductor)
            <tr>
                <td>{{ $conductor->id_conductor }}</td>
                <td><strong>{{ $conductor->nombre }}</strong></td>
                <td>{{ $conductor->cedula }}</td>
                <td>{{ $conductor->licencia }}</td>
                <td>{{ $conductor->telefono ?? '—' }}</td>
                <td>{{ $conductor->viajes_count }}</td>
                <td style="display:flex; gap:12px; align-items:center;">
                    <a href="{{ route('conductores.edit', $conductor->id_conductor) }}" class="btn-edit">Editar</a>
                    <form method="POST" action="{{ route('conductores.destroy', $conductor->id_conductor) }}"
                          onsubmit="return confirm('¿Eliminar este conductor?')">
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
