@extends('layouts.app')

@section('title', 'Propietarios')

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

    .tipo-tag {
        display: inline-block; padding: 2px 10px; border-radius: 12px;
        font-size: 0.78rem; font-weight: 600;
    }
    .tipo-empresa { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .tipo-socio   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

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
    <div class="page-header"><h1>Propietarios</h1></div>
    <a href="{{ route('propietarios.create') }}" class="btn-new">+ Nuevo Propietario</a>
</div>

@if($propietarios->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        No hay propietarios registrados.
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Cédula / NIT</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Buses</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($propietarios as $propietario)
            <tr>
                <td>{{ $propietario->id_propietario }}</td>
                <td>
                    <span class="tipo-tag tipo-{{ $propietario->tipo }}">
                        {{ ucfirst($propietario->tipo) }}
                    </span>
                </td>
                <td><strong>{{ $propietario->nombre }}</strong></td>
                <td>{{ $propietario->cedula_nit }}</td>
                <td>{{ $propietario->telefono ?? '—' }}</td>
                <td>{{ $propietario->email ?? '—' }}</td>
                <td>{{ $propietario->buses_count }}</td>
                <td style="display:flex; gap:12px; align-items:center;">
                    <a href="{{ route('propietarios.edit', $propietario->id_propietario) }}" class="btn-edit">Editar</a>
                    <form method="POST" action="{{ route('propietarios.destroy', $propietario->id_propietario) }}"
                          onsubmit="return confirm('¿Eliminar este propietario?')">
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
