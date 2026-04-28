@extends('layouts.app')

@section('title', 'Buses')

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

    .tab-bar {
        display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb;
    }
    .tab-link {
        padding: 8px 16px; font-size: 0.875rem; font-weight: 600; color: #6b7280;
        text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
        transition: color 0.15s, border-color 0.15s;
    }
    .tab-link:hover { color: #1d4ed8; }
    .tab-link.active { color: #1d4ed8; border-bottom-color: #1d4ed8; }
    .tab-badge {
        background: #fee2e2; color: #dc2626; border-radius: 10px;
        padding: 1px 7px; font-size: 0.75rem; margin-left: 4px;
    }

    .tipo-tag {
        display: inline-block; padding: 2px 10px; border-radius: 12px;
        font-size: 0.78rem; font-weight: 600; background: #eff6ff; color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .badge-inactivo {
        display: inline-block; padding: 2px 10px; border-radius: 12px;
        font-size: 0.75rem; font-weight: 600; background: #f3f4f6; color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-ver    { color: #059669; text-decoration: none; font-size: 0.85rem; font-weight: 500; }
    .btn-ver:hover    { text-decoration: underline; }
    .btn-edit   { color: #1d4ed8; text-decoration: none; font-size: 0.85rem; font-weight: 500; }
    .btn-edit:hover   { text-decoration: underline; }
    .btn-delete {
        background: none; border: none; color: #ef4444;
        font-size: 0.85rem; font-weight: 500; cursor: pointer; padding: 0;
    }
    .btn-delete:hover { text-decoration: underline; }
    .btn-reactivar {
        background: none; border: none; color: #059669;
        font-size: 0.85rem; font-weight: 500; cursor: pointer; padding: 0;
    }
    .btn-reactivar:hover { text-decoration: underline; }

    tr.inactivo td { opacity: 0.55; }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div class="page-header"><h1>Buses</h1></div>
    @if(!$verInactivos)
        <a href="{{ route('buses.create') }}" class="btn-new">+ Nuevo Bus</a>
    @endif
</div>

<div class="tab-bar">
    <a href="{{ route('buses.index') }}"
       class="tab-link {{ !$verInactivos ? 'active' : '' }}">
        Activos
    </a>
    <a href="{{ route('buses.index', ['inactivos' => 1]) }}"
       class="tab-link {{ $verInactivos ? 'active' : '' }}">
        Dados de baja
        @if($totalInactivos > 0)
            <span class="tab-badge">{{ $totalInactivos }}</span>
        @endif
    </a>
</div>

@if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        {{ session('error') }}
    </div>
@endif

@if($buses->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        {{ $verInactivos ? 'No hay buses dados de baja.' : 'No hay buses registrados.' }}
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Placa</th>
                <th>Tipo de Bus</th>
                <th>Propietario</th>
                <th>Capacidad</th>
                <th>Asientos creados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buses as $bus)
            <tr class="{{ $verInactivos ? 'inactivo' : '' }}">
                <td>{{ $bus->id_bus }}</td>
                <td>
                    <strong>{{ $bus->placa }}</strong>
                    @if($verInactivos)
                        <span class="badge-inactivo" style="margin-left:6px;">Baja</span>
                    @endif
                </td>
                <td><span class="tipo-tag">{{ $bus->tipoBus->nombre ?? '—' }}</span></td>
                <td>{{ $bus->propietario->nombre ?? '—' }}</td>
                <td>{{ $bus->capacidad }}</td>
                <td>{{ $bus->asientos_count }}</td>
                <td style="display:flex; gap:12px; align-items:center;">
                    @if($verInactivos)
                        <a href="{{ route('buses.show', $bus->id_bus) }}" class="btn-ver">Ver</a>
                        <form method="POST" action="{{ route('buses.reactivar', $bus->id_bus) }}"
                              onsubmit="return confirm('¿Reactivar el bus {{ $bus->placa }}?')">
                            @csrf
                            <button type="submit" class="btn-reactivar">Reactivar</button>
                        </form>
                    @else
                        <a href="{{ route('buses.show', $bus->id_bus) }}" class="btn-ver">Ver</a>
                        <a href="{{ route('buses.edit', $bus->id_bus) }}" class="btn-edit">Editar</a>
                        <form method="POST" action="{{ route('buses.destroy', $bus->id_bus) }}"
                              onsubmit="return confirm('¿Dar de baja el bus {{ $bus->placa }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Dar de baja</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
