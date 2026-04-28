@extends('layouts.app')
@section('title', 'Ingresos por Ruta')
@section('page-title', 'Ingresos por Ruta')

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

    .bar-wrap { display:flex; align-items:center; gap:10px; }
    .bar-bg { flex:1; height:8px; background:#f1f5f9; border-radius:4px; overflow:hidden; }
    .bar-fill { height:100%; background:#2563eb; border-radius:4px; }
    .bar-pct { font-size:0.75rem; color:#6b7280; min-width:36px; text-align:right; }

    .empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
    <form method="GET" action="{{ route('reportes.ingresos-ruta') }}">
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
        <div class="value green">${{ number_format($totalGeneral, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Rutas activas</div>
        <div class="value">{{ $rutas->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total tiquetes</div>
        <div class="value">{{ $rutas->sum('total_tiquetes') }}</div>
    </div>
</div>

@if($rutas->isEmpty())
    <div class="empty-state">No hay ingresos registrados{{ $desde ? ' en el período seleccionado' : '' }}.</div>
@else
<table>
    <thead>
        <tr>
            <th>Ruta</th>
            <th>Viajes</th>
            <th>Tiquetes</th>
            <th>Precio promedio</th>
            <th>Total recaudado</th>
            <th>Participación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rutas as $r)
        @php $pct = $totalGeneral > 0 ? round(($r->total_ingresos / $totalGeneral) * 100) : 0; @endphp
        <tr>
            <td><strong>{{ $r->origen }}</strong> → {{ $r->destino }}</td>
            <td>{{ $r->total_viajes }}</td>
            <td>{{ $r->total_tiquetes }}</td>
            <td>${{ number_format($r->precio_promedio, 0, ',', '.') }}</td>
            <td><strong>${{ number_format($r->total_ingresos, 0, ',', '.') }}</strong></td>
            <td>
                <div class="bar-wrap">
                    <div class="bar-bg"><div class="bar-fill" style="width:{{ $pct }}%"></div></div>
                    <span class="bar-pct">{{ $pct }}%</span>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
