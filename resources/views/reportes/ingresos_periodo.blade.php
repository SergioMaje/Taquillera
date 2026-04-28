@extends('layouts.app')
@section('title', 'Ingresos por Período')
@section('page-title', 'Ingresos por Período')

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

    .summary-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .summary-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px 20px; }
    .summary-card .label { font-size:0.72rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
    .summary-card .value { font-size:1.8rem; font-weight:700; color:#111827; margin-top:4px; }
    .summary-card .value.green { color:#16a34a; }

    /* Gráfico de barras */
    .chart-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:24px; margin-bottom:24px; }
    .chart-title { font-size:0.8rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; }
    .chart-bars { display:flex; align-items:flex-end; gap:6px; height:120px; }
    .chart-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; }
    .chart-bar { width:100%; border-radius:4px 4px 0 0; background:#2563eb; min-height:4px; transition:opacity 0.15s; }
    .chart-bar:hover { opacity:0.8; }
    .chart-label { font-size:0.65rem; color:#9ca3af; white-space:nowrap; }

    .empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
    <form method="GET" action="{{ route('reportes.ingresos-periodo') }}">
        <label>Desde</label>
        <input type="date" name="desde" value="{{ $desde }}">
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}">
        <button type="submit" class="btn-filtrar">Filtrar</button>
    </form>
</div>

<div class="summary-row">
    <div class="summary-card">
        <div class="label">Total recaudado</div>
        <div class="value green">${{ number_format($totalRecaudado, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Tiquetes vendidos</div>
        <div class="value">{{ $totalTiquetes }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Promedio diario</div>
        <div class="value">${{ number_format($promedioDiario, 0, ',', '.') }}</div>
    </div>
</div>

@if($dias->isEmpty())
    <div class="empty-state">No hay ingresos en el período seleccionado.</div>
@else

{{-- Gráfico de barras --}}
@php $maxTotal = $dias->max('total') ?: 1; @endphp
<div class="chart-wrap">
    <div class="chart-title">Ingresos diarios</div>
    <div class="chart-bars">
        @foreach($dias as $dia)
        @php $altura = round(($dia->total / $maxTotal) * 100); @endphp
        <div class="chart-bar-wrap" title="{{ $dia->fecha }}: ${{ number_format($dia->total, 0, ',', '.') }}">
            <div class="chart-bar" style="height:{{ $altura }}%"></div>
            <div class="chart-label">{{ \Carbon\Carbon::parse($dia->fecha)->format('d/m') }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Tabla detalle --}}
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Día</th>
            <th>Tiquetes vendidos</th>
            <th>Total recaudado</th>
            <th>% del período</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dias as $dia)
        @php
            $pct = $totalRecaudado > 0 ? round(($dia->total / $totalRecaudado) * 100, 1) : 0;
        @endphp
        <tr>
            <td>{{ \Carbon\Carbon::parse($dia->fecha)->format('d/m/Y') }}</td>
            <td style="color:#6b7280;">{{ \Carbon\Carbon::parse($dia->fecha)->locale('es')->isoFormat('dddd') }}</td>
            <td>{{ $dia->tiquetes }}</td>
            <td><strong>${{ number_format($dia->total, 0, ',', '.') }}</strong></td>
            <td>{{ $pct }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
