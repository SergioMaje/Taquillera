@extends('layouts.app')
@section('title', 'Detalle del Viaje')
@section('page-title', 'Detalle del Viaje')

@push('styles')
<style>
    .back-link { color:#6b7280; font-size:0.85rem; text-decoration:none; display:inline-block; margin-bottom:20px; }
    .back-link:hover { color:#111827; }

    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px; }
    .detail-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:20px 24px; }
    .detail-card h3 { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; margin:0 0 14px; }
    .detail-row { display:flex; justify-content:space-between; align-items:center; padding:7px 0; border-bottom:1px solid #f3f4f6; font-size:0.88rem; }
    .detail-row:last-child { border-bottom:none; }
    .detail-row .key { color:#6b7280; }
    .detail-row .val { font-weight:600; color:#111827; }

    .summary-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .summary-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px 20px; }
    .summary-card .label { font-size:0.72rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
    .summary-card .value { font-size:1.6rem; font-weight:700; color:#111827; margin-top:4px; }
    .summary-card .value.green  { color:#16a34a; }
    .summary-card .value.red    { color:#dc2626; }
    .summary-card .value.blue   { color:#2563eb; }

    .section-title { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; margin:0 0 12px; }

    .costos-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:24px; margin-bottom:24px; }
    .add-costo-form { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #f3f4f6; }
    .form-field { display:flex; flex-direction:column; gap:4px; }
    .form-field label { font-size:0.78rem; font-weight:600; color:#374151; }
    .form-field select,
    .form-field input { padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; }
    .form-field input[type="number"] { width:130px; }
    .form-field input[type="text"]   { width:200px; }
    .btn-add { background:#2563eb; color:#fff; border:none; padding:9px 20px; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; align-self:flex-end; }
    .btn-add:hover { background:#1d4ed8; }

    .concepto-badge { display:inline-block; padding:2px 10px; border-radius:12px; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; }
    .concepto-combustible  { background:#fef9c3; color:#854d0e; }
    .concepto-peajes       { background:#dbeafe; color:#1e40af; }
    .concepto-conductor    { background:#f3e8ff; color:#6b21a8; }
    .concepto-mantenimiento{ background:#fee2e2; color:#991b1b; }
    .concepto-otros        { background:#f3f4f6; color:#4b5563; }

    .btn-delete { background:none; border:none; color:#ef4444; font-size:0.8rem; cursor:pointer; padding:4px 8px; border-radius:6px; }
    .btn-delete:hover { background:#fee2e2; }

    .empty-costos { text-align:center; padding:30px; color:#9ca3af; font-size:0.9rem; }

    .estado-badge { display:inline-block; padding:3px 10px; border-radius:12px; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; }
    .estado-programado   { background:#dbeafe; color:#1e40af; }
    .estado-en_ruta      { background:#d1fae5; color:#065f46; }
    .estado-completado   { background:#f3f4f6; color:#6b7280; }
    .estado-cancelado    { background:#fee2e2; color:#991b1b; }
</style>
@endpush

@section('content')

<a href="{{ route('viajes.index') }}" class="back-link">← Volver a Viajes</a>

@if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:10px 16px; border-radius:8px; margin-bottom:16px; font-size:0.875rem;">
        {{ session('success') }}
    </div>
@endif

{{-- Resumen financiero --}}
@php
    $utilidad = $totalIngresos - $totalCostos;
@endphp
<div class="summary-row">
    <div class="summary-card">
        <div class="label">Ingresos (tiquetes)</div>
        <div class="value green">${{ number_format($totalIngresos, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total costos</div>
        <div class="value red">${{ number_format($totalCostos, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Utilidad</div>
        <div class="value {{ $utilidad >= 0 ? 'green' : 'red' }}">${{ number_format($utilidad, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Info del viaje --}}
<div class="detail-grid">
    <div class="detail-card">
        <h3>Ruta</h3>
        <div class="detail-row">
            <span class="key">Origen</span>
            <span class="val">{{ $viaje->ruta->municipioOrigen->nombre }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Destino</span>
            <span class="val">{{ $viaje->ruta->municipioDestino->nombre }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Fecha salida</span>
            <span class="val">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Hora salida</span>
            <span class="val">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Estado</span>
            <span class="val">
                <span class="estado-badge estado-{{ $viaje->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
                </span>
            </span>
        </div>
    </div>
    <div class="detail-card">
        <h3>Operación</h3>
        <div class="detail-row">
            <span class="key">Bus</span>
            <span class="val">{{ $viaje->bus->placa }} — {{ $viaje->bus->tipoBus->nombre ?? '—' }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Conductor</span>
            <span class="val">{{ $viaje->conductor->nombre ?? '—' }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Precio base</span>
            <span class="val">${{ number_format($viaje->precio_base, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Precio final</span>
            <span class="val">${{ number_format($viaje->precio_final, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="key">Asientos libres</span>
            <span class="val">{{ $viaje->asientos_libres }}</span>
        </div>
    </div>
</div>

{{-- Costos --}}
<div class="costos-wrap">
    <h3 class="section-title">Costos del Viaje</h3>

    {{-- Formulario agregar --}}
    <form method="POST" action="{{ route('viajes.costos.store', $viaje->id_viaje) }}" class="add-costo-form">
        @csrf
        <div class="form-field">
            <label>Concepto</label>
            <select name="concepto" required>
                <option value="combustible">Combustible</option>
                <option value="peajes">Peajes</option>
                <option value="conductor">Conductor</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="otros">Otros</option>
            </select>
        </div>
        <div class="form-field">
            <label>Descripción (opcional)</label>
            <input type="text" name="descripcion" placeholder="Ej: Pago conductor Bogotá">
        </div>
        <div class="form-field">
            <label>Monto ($)</label>
            <input type="number" name="monto" min="0" step="0.01" placeholder="0" required>
        </div>
        <button type="submit" class="btn-add">+ Agregar</button>
    </form>

    {{-- Lista de costos --}}
    @if($viaje->costos->isEmpty())
        <div class="empty-costos">No hay costos registrados para este viaje.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($viaje->costos as $costo)
            <tr>
                <td>
                    <span class="concepto-badge concepto-{{ $costo->concepto }}">
                        {{ ucfirst($costo->concepto) }}
                    </span>
                </td>
                <td style="color:#6b7280;">{{ $costo->descripcion ?? '—' }}</td>
                <td><strong>${{ number_format($costo->monto, 0, ',', '.') }}</strong></td>
                <td>
                    <form method="POST" action="{{ route('viajes.costos.destroy', [$viaje->id_viaje, $costo->id_costo_viaje]) }}"
                          onsubmit="return confirm('¿Eliminar este costo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            <tr style="border-top:2px solid #e5e7eb;">
                <td colspan="2" style="font-weight:700; text-align:right; padding-right:12px;">Total costos</td>
                <td><strong style="color:#dc2626;">${{ number_format($totalCostos, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif
</div>

@endsection
