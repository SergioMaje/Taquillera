@extends('layouts.app')
@section('title', 'Manifiesto de Pasajeros')
@section('page-title', 'Manifiesto de Pasajeros')

@push('styles')
<style>
    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .back-link {
        color: #6b7280; font-size: 0.85rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .back-link:hover { color: #111827; }
    .btn-print {
        background: #0f172a; color: #fff; border: none;
        padding: 8px 18px; border-radius: 8px; font-size: 0.85rem;
        font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-print:hover { background: #1e293b; }

    .viaje-header {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 22px 28px; margin-bottom: 24px;
        display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;
    }
    .viaje-header .campo { }
    .viaje-header .campo-label { font-size: 0.72rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }
    .viaje-header .campo-valor { font-size: 0.95rem; font-weight: 600; color: #111827; margin-top: 3px; }

    .summary-row {
        display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
    }
    .chip {
        background: #f1f5f9; border-radius: 8px; padding: 10px 16px;
        font-size: 0.85rem; color: #374151; font-weight: 500;
    }
    .chip span { font-weight: 700; color: #111827; }

    table { margin-top: 0; }
    td .no-data { color: #9ca3af; font-style: italic; }

    @media print {
        .toolbar, .sidebar, .topbar { display: none !important; }
        .page-wrapper { margin-left: 0 !important; padding-top: 0 !important; }
        .main-content { padding: 20px !important; }
        .print-header { display: block !important; }
    }
    .print-header { display: none; margin-bottom: 16px; }
    .print-header h2 { font-size: 1.2rem; font-weight: 700; }
    .print-header p  { font-size: 0.85rem; color: #555; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.viajes-hoy') }}?fecha={{ $viaje->fecha_salida }}" class="back-link">← Viajes del Día</a>
    <button class="btn-print" onclick="window.print()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
        </svg>
        Imprimir
    </button>
</div>

{{-- Encabezado para impresión --}}
<div class="print-header">
    <h2>Manifiesto de Pasajeros — CoMotor Huila</h2>
    <p>{{ $viaje->ruta->municipioOrigen->nombre }} → {{ $viaje->ruta->municipioDestino->nombre }}
       &nbsp;|&nbsp; {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}
       &nbsp;|&nbsp; Salida: {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}</p>
</div>

{{-- Info del viaje --}}
<div class="viaje-header">
    <div class="campo">
        <div class="campo-label">Ruta</div>
        <div class="campo-valor">
            {{ $viaje->ruta->municipioOrigen->nombre }} → {{ $viaje->ruta->municipioDestino->nombre }}
        </div>
    </div>
    <div class="campo">
        <div class="campo-label">Fecha y hora de salida</div>
        <div class="campo-valor">
            {{ \Carbon\Carbon::parse($viaje->fecha_salida)->locale('es')->isoFormat('D MMM YYYY') }}
            a las {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}
        </div>
    </div>
    <div class="campo">
        <div class="campo-label">Bus</div>
        <div class="campo-valor">{{ $viaje->bus->placa ?? '—' }} — {{ $viaje->bus->tipoBus->nombre ?? '—' }}</div>
    </div>
    <div class="campo">
        <div class="campo-label">Conductor</div>
        <div class="campo-valor">{{ $viaje->conductor->nombre ?? 'Sin asignar' }}</div>
    </div>
    <div class="campo">
        <div class="campo-label">Estado</div>
        <div class="campo-valor">{{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}</div>
    </div>
    <div class="campo">
        <div class="campo-label">Total recaudado</div>
        <div class="campo-valor">${{ number_format($totalRecaudado, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Resumen rápido --}}
<div class="summary-row">
    <div class="chip">Pasajeros: <span>{{ $pasajeros->count() }}</span></div>
    <div class="chip">Asientos libres: <span>{{ $viaje->asientos_libres }}</span></div>
    <div class="chip">Capacidad total: <span>{{ $viaje->bus->capacidad ?? '—' }}</span></div>
</div>

{{-- Tabla de pasajeros --}}
@if($pasajeros->isEmpty())
    <p style="color:#9ca3af; text-align:center; padding:40px 0;">
        No hay pasajeros registrados para este viaje.
    </p>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Asiento</th>
            <th>Nombre del Pasajero</th>
            <th>Documento</th>
            <th>Precio Pagado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pasajeros as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $p->asiento }}</strong></td>
            <td>{!! $p->nombre && $p->nombre !== '—' ? e($p->nombre) : '<span class="no-data">Sin nombre</span>' !!}</td>
            <td>{!! $p->documento && $p->documento !== '—' ? e($p->documento) : '<span class="no-data">—</span>' !!}</td>
            <td>${{ number_format($p->precio, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
