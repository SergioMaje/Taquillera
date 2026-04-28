@extends('layouts.app')
@section('title', 'Viajes')
@section('page-title', 'Viajes')

@push('styles')
<style>
    .toolbar {
        display: flex; align-items: center; gap: 12px; margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .toolbar form { display: flex; align-items: center; gap: 8px; }
    .toolbar input[type="date"] {
        padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 0.875rem; color: #111827;
    }
    .btn-filtrar {
        background: #2563eb; color: #fff; border: none;
        padding: 8px 18px; border-radius: 8px; font-size: 0.875rem;
        font-weight: 600; cursor: pointer;
    }
    .btn-filtrar:hover { background: #1d4ed8; }
    .back-link {
        color: #6b7280; font-size: 0.85rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .back-link:hover { color: #111827; }

    .summary-row {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .summary-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 18px 20px;
    }
    .summary-card .label { font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .summary-card .value { font-size: 1.8rem; font-weight: 700; color: #111827; margin-top: 4px; }

    .viaje-row {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 18px 22px; margin-bottom: 12px;
        display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
    }
    .ruta-info { flex: 1; min-width: 180px; }
    .ruta-info .ruta-nombre { font-size: 1rem; font-weight: 700; color: #111827; }
    .ruta-info .ruta-meta  { font-size: 0.8rem; color: #6b7280; margin-top: 3px; }

    .ocupacion-wrap { min-width: 160px; }
    .ocupacion-bar-bg {
        height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; margin-bottom: 5px;
    }
    .ocupacion-bar-fill { height: 100%; border-radius: 4px; }
    .ocupacion-text { font-size: 0.78rem; color: #374151; font-weight: 600; }

    .estado-badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
    }
    .badge-programado { background: #dbeafe; color: #1e40af; }
    .badge-en_ruta    { background: #d1fae5; color: #065f46; }
    .badge-completado { background: #f3f4f6; color: #6b7280; }
    .badge-cancelado  { background: #fee2e2; color: #991b1b; }

    .btn-manifiesto {
        background: #0f172a; color: #fff; text-decoration: none;
        padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600;
        white-space: nowrap; transition: background 0.2s;
    }
    .btn-manifiesto:hover { background: #1e293b; }

    .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
    <form method="GET" action="{{ route('reportes.viajes-hoy') }}">
        <input type="date" name="fecha" value="{{ $fecha }}">
        <button type="submit" class="btn-filtrar">Consultar</button>
    </form>
</div>

{{-- Resumen --}}
@php
    $totalViajes  = $viajes->count();
    $totalPasajeros = $viajes->sum('ocupados');
    $totalAsientos  = $viajes->sum('total_asientos');
    $pctGeneral = $totalAsientos > 0 ? round(($totalPasajeros / $totalAsientos) * 100) : 0;
@endphp

<div class="summary-row">
    <div class="summary-card">
        <div class="label">Viajes programados</div>
        <div class="value">{{ $totalViajes }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Pasajeros en total</div>
        <div class="value">{{ $totalPasajeros }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Ocupación general</div>
        <div class="value">{{ $pctGeneral }}%</div>
    </div>
</div>

@forelse($viajes as $viaje)
@php
    $pct   = $viaje->pct_ocupacion;
    $color = $pct >= 80 ? '#ef4444' : ($pct >= 50 ? '#f59e0b' : '#22c55e');
@endphp
<div class="viaje-row">

    <div class="ruta-info">
        <div class="ruta-nombre">
            {{ $viaje->ruta->municipioOrigen->nombre }} → {{ $viaje->ruta->municipioDestino->nombre }}
        </div>
        <div class="ruta-meta">
            {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }} &nbsp;·&nbsp;
            Bus: {{ $viaje->bus->placa ?? '—' }} &nbsp;·&nbsp;
            {{ $viaje->bus->tipoBus->nombre ?? '—' }}
            @if($viaje->conductor)
                &nbsp;·&nbsp; Conductor: {{ $viaje->conductor->nombre }}
            @endif
        </div>
    </div>

    <div class="ocupacion-wrap">
        <div class="ocupacion-bar-bg">
            <div class="ocupacion-bar-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
        </div>
        <div class="ocupacion-text">
            {{ $viaje->ocupados }} / {{ $viaje->total_asientos }} asientos — {{ $pct }}%
        </div>
    </div>

    <span class="estado-badge badge-{{ $viaje->estado }}">
        {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
    </span>

    <a href="{{ route('reportes.manifiesto', $viaje->id_viaje) }}" class="btn-manifiesto">
        Ver Manifiesto
    </a>

</div>
@empty
<div class="empty-state">
    No hay viajes registrados{{ $fecha ? ' para el ' . \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') : '' }}.
</div>
@endforelse

@endsection
