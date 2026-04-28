@extends('layouts.app')

@section('title', 'Tipos de Bus')

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

    .badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-yes { background: #d1fae5; color: #065f46; }
    .badge-no  { background: #f3f4f6; color: #9ca3af; }

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

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px 16px;
        border-radius: 6px;
        margin-bottom: 18px;
    }
</style>
@endpush

@section('content')

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="page-header-row">
    <div class="page-header">
        <h1>Tipos de Bus</h1>
    </div>
    <a href="{{ route('tipos-bus.create') }}" class="btn-new">+ Nuevo Tipo</a>
</div>

@if($tiposBus->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        No hay tipos de bus registrados.
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Baño</th>
                <th>TV</th>
                <th>Doble Piso</th>
                <th>Buses</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposBus as $tipo)
            <tr>
                <td>{{ $tipo->id_tipo_bus }}</td>
                <td><strong>{{ $tipo->nombre }}</strong></td>
                <td>{{ $tipo->descripcion ?? '—' }}</td>
                <td><span class="badge {{ $tipo->tiene_bano ? 'badge-yes' : 'badge-no' }}">{{ $tipo->tiene_bano ? 'Sí' : 'No' }}</span></td>
                <td><span class="badge {{ $tipo->tiene_tv ? 'badge-yes' : 'badge-no' }}">{{ $tipo->tiene_tv ? 'Sí' : 'No' }}</span></td>
                <td><span class="badge {{ $tipo->doble_piso ? 'badge-yes' : 'badge-no' }}">{{ $tipo->doble_piso ? 'Sí' : 'No' }}</span></td>
                <td>{{ $tipo->buses_count }}</td>
                <td style="display:flex; gap:12px; align-items:center;">
                    <a href="{{ route('tipos-bus.edit', $tipo->id_tipo_bus) }}" class="btn-edit">Editar</a>
                    <form method="POST" action="{{ route('tipos-bus.destroy', $tipo->id_tipo_bus) }}" onsubmit="return confirm('¿Eliminar este tipo?')">
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
