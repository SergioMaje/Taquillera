@extends('layouts.app')
@section('title', 'Tiquetes Vendidos')
@section('page-title', 'Tiquetes Vendidos')

@push('styles')
<style>
    .toolbar {
        display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;
    }
    .toolbar form { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .toolbar label { font-size: 0.82rem; font-weight: 600; color: #374151; }
    .toolbar input[type="date"] {
        padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 0.875rem; color: #111827;
    }
    .btn-filtrar {
        background: #2563eb; color: #fff; border: none;
        padding: 8px 18px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer;
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
        background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 18px 20px;
    }
    .summary-card .label { font-size: 0.72rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .summary-card .value { font-size: 1.8rem; font-weight: 700; color: #111827; margin-top: 4px; }
    .summary-card .value.green { color: #16a34a; }

    .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
    <form method="GET" action="{{ route('reportes.ventas') }}">
        <label>Desde</label>
        <input type="date" name="desde" value="{{ $desde }}">
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}">
        <button type="submit" class="btn-filtrar">Filtrar</button>
    </form>
</div>

{{-- Resumen --}}
<div class="summary-row">
    <div class="summary-card">
        <div class="label">Tiquetes vendidos</div>
        <div class="value">{{ $tiquetes->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total recaudado</div>
        <div class="value green">${{ number_format($totalRecaudado, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Promedio por tiquete</div>
        <div class="value">
            ${{ $tiquetes->count() > 0 ? number_format($totalRecaudado / $tiquetes->count(), 0, ',', '.') : 0 }}
        </div>
    </div>
</div>

{{-- Tabla --}}
@if($tiquetes->isEmpty())
    <div class="empty-state">No hay tiquetes vendidos en el período seleccionado.</div>
@else
<table>
    <thead>
        <tr>
            <th>Tiquete</th>
            <th>Fecha Venta</th>
            <th>Pasajero</th>
            <th>Documento</th>
            <th>Ruta</th>
            <th>Asiento</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiquetes as $t)
        @php
            $viaje  = $t->asientoViaje->viaje;
            $ruta   = $viaje->ruta;
        @endphp
        <tr>
            <td>#{{ $t->id_tiquete }}</td>
            <td>{{ \Carbon\Carbon::parse($t->fecha_compra)->format('d/m/Y H:i') }}</td>
            <td>{{ $t->nombre_pasajero ?? '—' }}</td>
            <td>{{ $t->documento_pasajero ?? '—' }}</td>
            <td>{{ $ruta->municipioOrigen->nombre }} → {{ $ruta->municipioDestino->nombre }}</td>
            <td>#{{ $t->asientoViaje->asiento->numero ?? '—' }}</td>
            <td>${{ number_format($t->precio_final, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
