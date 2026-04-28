@extends('layouts.app')

@section('title', 'Viajes Disponibles')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }
    .page-header .meta { color: #6b7280; font-size: 0.9rem; margin-top: 4px; display: flex; align-items: center; gap: 6px; }

    .sort-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
    .sort-tab {
        padding: 8px 22px;
        border-radius: 20px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: default;
    }
    .sort-tab.active {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
    }

    .trip-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px 28px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 32px;
    }
    .trip-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }

    /* Tiempos */
    .trip-endpoint { min-width: 110px; }
    .trip-endpoint .hour { font-size: 1.75rem; font-weight: 700; color: #111827; line-height: 1; }
    .trip-endpoint .station { font-size: 0.82rem; color: #6b7280; margin-top: 4px; }

    /* Línea central */
    .trip-middle { flex: 1; text-align: center; }
    .trip-duration-text { font-size: 0.78rem; color: #9ca3af; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.5px; }
    .trip-line {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .trip-dot-left {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: #1d4ed8;
        flex-shrink: 0;
    }
    .trip-dot-right {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: #d1d5db;
        flex-shrink: 0;
    }
    .trip-line-bar {
        flex: 1;
        height: 2px;
        background: linear-gradient(to right, #1d4ed8, #d1d5db);
    }
    .trip-bus-icon {
        font-size: 1.1rem;
        margin: 0 6px;
        flex-shrink: 0;
    }
    .trip-class-tag {
        display: inline-block;
        margin-top: 10px;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        border: 1px solid #d1d5db;
        color: #6b7280;
        font-weight: 500;
    }

    /* Precio */
    .trip-price { text-align: right; min-width: 110px; }
    .trip-price .original {
        color: #9ca3af;
        text-decoration: line-through;
        font-size: 0.85rem;
    }
    .trip-price .final {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.1;
    }

    /* Botón */
    .btn-select {
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 22px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .btn-select:hover { background: #1e40af; }

    .seats-badge {
        font-size: 0.75rem;
        color: #059669;
        margin-top: 6px;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
        font-size: 1rem;
    }

    .page-header-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }
    .btn-new-trip {
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
    .btn-new-trip:hover { background: #1e40af; }

    .trip-card.is-cancelled {
        opacity: 0.55;
        background: #f9fafb;
    }

    .estado-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .estado-programado  { background: #dbeafe; color: #1e40af; }
    .estado-en_ruta     { background: #d1fae5; color: #065f46; }
    .estado-completado  { background: #f3f4f6; color: #6b7280; }
    .estado-cancelado   { background: #fee2e2; color: #991b1b; }

    .btn-cancel-trip {
        background: none;
        border: 1px solid #fca5a5;
        color: #ef4444;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s, color 0.2s;
        flex-shrink: 0;
    }
    .btn-cancel-trip:hover { background: #fef2f2; }

    .btn-complete-trip {
        background: none;
        border: 1px solid #6ee7b7;
        color: #059669;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s;
        flex-shrink: 0;
        width: 100%;
    }
    .btn-complete-trip:hover { background: #f0fdf4; }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div class="page-header" style="margin-bottom:0">
        <h1>Viajes Disponibles</h1>
        <p class="meta">
            📅 {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
        </p>
    </div>
    <div style="display:flex; gap:10px; align-items:center; flex-shrink:0;">
        @if($totalCancelados > 0)
            <a href="{{ route('viajes.cancelados') }}" style="
                display:inline-flex; align-items:center; gap:8px;
                padding:10px 18px; border-radius:8px; font-size:0.875rem; font-weight:600;
                text-decoration:none; border:1px solid #fca5a5; color:#ef4444;
                background:#fff; transition:background 0.2s;">
                Cancelados
                <span style="background:#ef4444; color:#fff; border-radius:10px;
                    padding:1px 7px; font-size:0.75rem;">{{ $totalCancelados }}</span>
            </a>
        @endif
        <a href="{{ route('viajes.create') }}" class="btn-new-trip">+ Nuevo Viaje</a>
    </div>
</div>

<div class="sort-tabs">
    <button class="sort-tab active">Más Barato</button>
    <button class="sort-tab">Más Rápido</button>
    <button class="sort-tab">Más Temprano</button>
</div>

@forelse ($viajes as $viaje)
@php
    $origen   = $viaje->ruta->municipioOrigen->nombre;
    $destino  = $viaje->ruta->municipioDestino->nombre;
    $duracion = $viaje->ruta->duracion_estimada;
    $horas    = intdiv($duracion, 60);
    $minutos  = $duracion % 60;
    $salida   = \Carbon\Carbon::parse($viaje->hora_salida);
    $llegada  = $salida->copy()->addMinutes($duracion);
    $clase    = $viaje->bus->tipoBus->nombre ?? 'Estándar';
@endphp

<div class="trip-card {{ $viaje->estado === 'cancelado' ? 'is-cancelled' : '' }}">

    {{-- Hora salida --}}
    <div class="trip-endpoint">
        <div class="hour">{{ $salida->format('H:i') }}</div>
        <div class="station">{{ $origen }}</div>
    </div>

    {{-- Línea central --}}
    <div class="trip-middle">
        <div class="trip-duration-text">{{ $horas }}H {{ str_pad($minutos, 2, '0') }}M</div>
        <div class="trip-line">
            <div class="trip-dot-left"></div>
            <div class="trip-line-bar"></div>
            <div class="trip-bus-icon">🚌</div>
            <div class="trip-line-bar"></div>
            <div class="trip-dot-right"></div>
        </div>
        <div>
            <span class="trip-class-tag">{{ $clase }}</span>
            <span class="estado-badge estado-{{ $viaje->estado }}" style="margin-left:8px;">
                {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
            </span>
        </div>
        @if($viaje->conductor)
            <div style="font-size:0.75rem; color:#6b7280; margin-top:6px;">
                Conductor: {{ $viaje->conductor->nombre }}
            </div>
        @endif
    </div>

    {{-- Hora llegada --}}
    <div class="trip-endpoint">
        <div class="hour">{{ $llegada->format('H:i') }}</div>
        <div class="station">{{ $destino }}</div>
    </div>

    {{-- Precio --}}
    <div class="trip-price">
        @if($viaje->precio_base != $viaje->precio_final)
            <div class="original">${{ number_format($viaje->precio_base, 0, ',', '.') }}</div>
        @endif
        <div class="final">${{ number_format($viaje->precio_final, 0, ',', '.') }}</div>
        <div class="seats-badge">{{ $viaje->asientos_libres }} asientos disponibles</div>
    </div>

    {{-- Acciones --}}
    <div style="display:flex; flex-direction:column; align-items:stretch; gap:8px; flex-shrink:0;">
        @if($viaje->estado === 'programado')
            <a href="{{ route('taquilla', $viaje->id_viaje) }}" class="btn-select">
                Seleccionar Asiento
            </a>
            <a href="{{ route('viajes.show', $viaje->id_viaje) }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff;
                      border:1px solid #d1d5db; color:#374151; border-radius:8px;
                      padding:8px 16px; font-size:0.82rem; font-weight:600; text-decoration:none;">
                Costos
            </a>
            <form method="POST" action="{{ route('viajes.completar', $viaje->id_viaje) }}"
                  onsubmit="return confirm('¿Marcar este viaje como completado? Ya no se podrán vender tiquetes.')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-complete-trip">Completar Viaje</button>
            </form>
            <form method="POST" action="{{ route('viajes.cancelar', $viaje->id_viaje) }}"
                  onsubmit="return confirm('¿Cancelar este viaje? Esta acción no se puede deshacer.')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-cancel-trip" style="width:100%;">
                    Cancelar Viaje
                </button>
            </form>
        @elseif($viaje->estado === 'en_ruta')
            <a href="{{ route('viajes.show', $viaje->id_viaje) }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff;
                      border:1px solid #d1d5db; color:#374151; border-radius:8px;
                      padding:8px 16px; font-size:0.82rem; font-weight:600; text-decoration:none;">
                Costos
            </a>
            <form method="POST" action="{{ route('viajes.completar', $viaje->id_viaje) }}"
                  onsubmit="return confirm('¿Marcar este viaje como completado?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-complete-trip">Completar Viaje</button>
            </form>
        @elseif($viaje->estado === 'completado')
            <a href="{{ route('reportes.manifiesto', $viaje->id_viaje) }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#f0fdf4;
                      border:1px solid #6ee7b7; color:#059669; border-radius:8px;
                      padding:8px 16px; font-size:0.82rem; font-weight:600; text-decoration:none;">
                Ver Manifiesto
            </a>
            <a href="{{ route('viajes.show', $viaje->id_viaje) }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff;
                      border:1px solid #d1d5db; color:#374151; border-radius:8px;
                      padding:8px 16px; font-size:0.82rem; font-weight:600; text-decoration:none;">
                Costos
            </a>
        @elseif($viaje->estado === 'cancelado')
            <span style="color:#9ca3af; font-size:0.85rem; text-align:center;">Viaje cancelado</span>
        @endif
    </div>

</div>
@empty
<div class="empty-state">
    <p>No hay viajes programados disponibles.</p>
</div>
@endforelse

@endsection
