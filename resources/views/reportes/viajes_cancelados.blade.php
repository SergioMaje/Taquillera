@extends('layouts.app')
@section('title', 'Viajes Cancelados')
@section('page-title', 'Viajes Cancelados')

@push('styles')
<style>
    .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:24px; }
    .back-link { color:#6b7280; font-size:0.85rem; text-decoration:none; }
    .back-link:hover { color:#111827; }

    .summary-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .summary-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px 20px; }
    .summary-card .label { font-size:0.72rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
    .summary-card .value { font-size:1.8rem; font-weight:700; color:#111827; margin-top:4px; }
    .summary-card .value.red { color:#dc2626; }

    .badge-cancelado { background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; text-transform:uppercase; }
    .empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }
</style>
@endpush

@section('content')

<div class="toolbar">
    <a href="{{ route('reportes.index') }}" class="back-link">← Reportes</a>
</div>

<div class="summary-row">
    <div class="summary-card">
        <div class="label">Viajes cancelados</div>
        <div class="value red">{{ $viajes->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Tiquetes afectados</div>
        <div class="value">{{ $viajes->sum('tiquetes_count') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Ingresos afectados</div>
        <div class="value red">${{ number_format($totalAfectado, 0, ',', '.') }}</div>
    </div>
</div>

@if($viajes->isEmpty())
    <div class="empty-state">No hay viajes cancelados registrados.</div>
@else
<table>
    <thead>
        <tr>
            <th>Fecha programada</th>
            <th>Ruta</th>
            <th>Bus</th>
            <th>Conductor</th>
            <th>Tiquetes vendidos</th>
            <th>Ingresos afectados</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($viajes as $v)
        <tr>
            <td>{{ \Carbon\Carbon::parse($v->fecha_salida)->format('d/m/Y') }}
                <span style="color:#9ca3af; font-size:0.8rem;"> {{ substr($v->hora_salida,0,5) }}</span>
            </td>
            <td>{{ $v->ruta->municipioOrigen->nombre }} → {{ $v->ruta->municipioDestino->nombre }}</td>
            <td>{{ $v->bus->placa ?? '—' }}</td>
            <td>{{ $v->conductor->nombre ?? 'Sin asignar' }}</td>
            <td>
                @if($v->tiquetes_count > 0)
                    <span style="color:#dc2626; font-weight:600;">{{ $v->tiquetes_count }}</span>
                @else
                    <span style="color:#9ca3af;">0</span>
                @endif
            </td>
            <td>
                @if($v->ingresos_afectados > 0)
                    <strong style="color:#dc2626;">${{ number_format($v->ingresos_afectados, 0, ',', '.') }}</strong>
                @else
                    <span style="color:#9ca3af;">$0</span>
                @endif
            </td>
            <td><span class="badge-cancelado">Cancelado</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
