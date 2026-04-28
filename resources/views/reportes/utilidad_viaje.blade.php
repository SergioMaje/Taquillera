@extends('layouts.app')
@section('title', 'Utilidad por Viaje')
@section('page-title', 'Utilidad por Viaje')

@push('styles')
<style>
    .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
    .toolbar form { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .toolbar label { font-size:0.82rem; font-weight:600; color:#374151; }
    .toolbar input[type="date"] { padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; }
    .btn-filtrar { background:#2563eb; color:#fff; border:none; padding:8px 18px; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; }
    .btn-filtrar:hover { background:#1d4ed8; }
    .back-link { color:#6b7280; font-size:0.85rem; text-decoration:none; }
    .back-link:hover { color:#111827; }

    .summary-row { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .summary-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px 20px; }
    .summary-card .label { font-size:0.72rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
    .summary-card .value { font-size:1.6rem; font-weight:700; color:#111827; margin-top:4px; }
    .summary-card .value.green  { color:#16a34a; }
    .summary-card .value.red    { color:#dc2626; }
    .summary-card .value.orange { color:#ea580c; }

    .utilidad-bar { display:flex; align-items:center; gap:8px; }
    .utilidad-bg { flex:1; height:8px; background:#f1f5f9; border-radius:4px; overflow:hidden; }
    .utilidad-fill-pos { height:100%; background:#16a34a; border-radius:4px; }
    .utilidad-fill-neg { height:100%; background:#dc2626; border-radius:4px; }

    .estado-badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
    .estado-programado   { background:#dbeafe; color:#1e40af; }
    .estado-en_ruta      { background:#d1fae5; color:#065f46; }
    .estado-completado   { background:#f3f4f6; color:#6b7280; }
    .estado-cancelado    { background:#fee2e2; color:#991b1b; }

    .empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }

    td.positive { color:#16a34a; font-weight:700; }
    td.negative { color:#dc2626; font-weight:700; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
    <form method="GET" action="{{ route('reportes.utilidad-viaje') }}">
        <label>Desde</label>
        <input type="date" name="desde" value="{{ $desde }}">
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}">
        <button type="submit" class="btn-filtrar">Filtrar</button>
    </form>
</div>

<div class="summary-row">
    <div class="summary-card">
        <div class="label">Total ingresos</div>
        <div class="value green">${{ number_format($totalIngresos, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total costos</div>
        <div class="value red">${{ number_format($totalCostos, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Utilidad neta</div>
        <div class="value {{ $totalUtilidad >= 0 ? 'green' : 'red' }}">${{ number_format($totalUtilidad, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Viajes</div>
        <div class="value">{{ $viajes->count() }}</div>
    </div>
</div>

@if($viajes->isEmpty())
    <div class="empty-state">No hay viajes registrados{{ $desde ? ' en el período seleccionado' : '' }}.</div>
@else
@php $maxAbs = max($viajes->max(fn($v) => abs($v->utilidad)), 1); @endphp
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Ruta</th>
            <th>Bus / Conductor</th>
            <th>Estado</th>
            <th>Ingresos</th>
            <th>Costos</th>
            <th>Utilidad</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($viajes as $v)
        @php $pct = round((abs($v->utilidad) / $maxAbs) * 100); @endphp
        <tr>
            <td>{{ \Carbon\Carbon::parse($v->fecha_salida)->format('d/m/Y') }}</td>
            <td>
                <strong>{{ $v->ruta->municipioOrigen->nombre }}</strong>
                → {{ $v->ruta->municipioDestino->nombre }}
            </td>
            <td style="color:#6b7280; font-size:0.82rem;">
                {{ $v->bus->placa ?? '—' }}<br>{{ $v->conductor->nombre ?? '—' }}
            </td>
            <td>
                <span class="estado-badge estado-{{ $v->estado }}">
                    {{ ucfirst(str_replace('_',' ',$v->estado)) }}
                </span>
            </td>
            <td>${{ number_format($v->total_ingresos, 0, ',', '.') }}</td>
            <td>${{ number_format($v->total_costos, 0, ',', '.') }}</td>
            <td class="{{ $v->utilidad >= 0 ? 'positive' : 'negative' }}">
                {{ $v->utilidad >= 0 ? '' : '-' }}${{ number_format(abs($v->utilidad), 0, ',', '.') }}
            </td>
            <td>
                <div class="utilidad-bar">
                    <div class="utilidad-bg">
                        @if($v->utilidad >= 0)
                            <div class="utilidad-fill-pos" style="width:{{ $pct }}%"></div>
                        @else
                            <div class="utilidad-fill-neg" style="width:{{ $pct }}%"></div>
                        @endif
                    </div>
                    <a href="{{ route('viajes.show', $v->id_viaje) }}"
                       style="font-size:0.75rem; color:#2563eb; white-space:nowrap;">Ver</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
