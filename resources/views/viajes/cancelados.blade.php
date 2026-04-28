@extends('layouts.app')

@section('title', 'Viajes Cancelados')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }
    .page-header .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.85rem; color: #6b7280; text-decoration: none; margin-bottom: 12px;
    }
    .page-header .back-link:hover { color: #1d4ed8; }

    .trip-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px 28px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 32px;
        opacity: 0.75;
    }

    .trip-endpoint { min-width: 110px; }
    .trip-endpoint .hour { font-size: 1.75rem; font-weight: 700; color: #374151; line-height: 1; }
    .trip-endpoint .station { font-size: 0.82rem; color: #6b7280; margin-top: 4px; }

    .trip-middle { flex: 1; text-align: center; }
    .trip-duration-text { font-size: 0.78rem; color: #9ca3af; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.5px; }
    .trip-line { display: flex; align-items: center; }
    .trip-dot { width: 9px; height: 9px; border-radius: 50%; background: #d1d5db; flex-shrink: 0; }
    .trip-line-bar { flex: 1; height: 2px; background: #e5e7eb; }
    .trip-bus-icon { font-size: 1.1rem; margin: 0 6px; flex-shrink: 0; }
    .trip-class-tag {
        display: inline-block; margin-top: 10px; padding: 3px 12px;
        border-radius: 20px; font-size: 0.75rem; border: 1px solid #e5e7eb;
        color: #9ca3af; font-weight: 500;
    }

    .trip-price { text-align: right; min-width: 110px; }
    .trip-price .original { color: #9ca3af; text-decoration: line-through; font-size: 0.85rem; }
    .trip-price .final { font-size: 1.6rem; font-weight: 700; color: #374151; line-height: 1.1; }
    .trip-price .date { font-size: 0.78rem; color: #9ca3af; margin-top: 6px; }

    .btn-renovar {
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 22px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .btn-renovar:hover { background: #1e40af; }

    .empty-state {
        text-align: center; padding: 60px 20px; color: #9ca3af; font-size: 1rem;
    }

    .cancelled-label {
        display: inline-block; padding: 3px 10px; border-radius: 12px;
        font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; background: #fee2e2; color: #991b1b;
        margin-left: 8px;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <a href="{{ route('viajes.index') }}" class="back-link">← Volver a viajes</a>
    <h1>Viajes Cancelados</h1>
</div>

@if($viajes->isEmpty())
    <div class="empty-state">
        <p>No hay viajes cancelados.</p>
    </div>
@else
    @foreach($viajes as $viaje)
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

    <div class="trip-card">

        {{-- Hora salida --}}
        <div class="trip-endpoint">
            <div class="hour">{{ $salida->format('H:i') }}</div>
            <div class="station">{{ $origen }}</div>
        </div>

        {{-- Línea central --}}
        <div class="trip-middle">
            <div class="trip-duration-text">{{ $horas }}H {{ str_pad($minutos, 2, '0') }}M</div>
            <div class="trip-line">
                <div class="trip-dot"></div>
                <div class="trip-line-bar"></div>
                <div class="trip-bus-icon">🚌</div>
                <div class="trip-line-bar"></div>
                <div class="trip-dot"></div>
            </div>
            <div>
                <span class="trip-class-tag">{{ $clase }}</span>
                <span class="cancelled-label">Cancelado</span>
            </div>
        </div>

        {{-- Hora llegada --}}
        <div class="trip-endpoint">
            <div class="hour">{{ $llegada->format('H:i') }}</div>
            <div class="station">{{ $destino }}</div>
        </div>

        {{-- Precio + fecha --}}
        <div class="trip-price">
            @if($viaje->precio_base != $viaje->precio_final)
                <div class="original">${{ number_format($viaje->precio_base, 0, ',', '.') }}</div>
            @endif
            <div class="final">${{ number_format($viaje->precio_final, 0, ',', '.') }}</div>
            <div class="date">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</div>
        </div>

        {{-- Renovar --}}
        <form method="POST" action="{{ route('viajes.renovar', $viaje->id_viaje) }}"
              onsubmit="return confirm('¿Renovar este viaje? Volverá al estado Programado.')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-renovar">Renovar Viaje</button>
        </form>

    </div>
    @endforeach
@endif

@endsection
