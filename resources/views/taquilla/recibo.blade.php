@extends('layouts.app')

@section('title', 'Tiquete #' . $tiquete->id_tiquete)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/taquilla.css') }}">
<style>
    .receipt-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 16px 0 40px;
    }

    .success-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 10px;
        padding: 14px 24px;
        margin-bottom: 28px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        max-width: 480px;
    }
    .success-banner .icon { font-size: 1.4rem; }

    .ticket {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    }

    .ticket-header {
        background: #1d4ed8;
        color: #fff;
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .ticket-header .company { font-size: 1.1rem; font-weight: 700; letter-spacing: 0.5px; }
    .ticket-header .ticket-num { font-size: 0.8rem; opacity: 0.75; margin-top: 2px; }
    .ticket-header .badge-estado {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .ticket-route {
        padding: 22px 28px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #f3f4f6;
    }
    .route-city { flex: 1; }
    .route-city .city-name { font-size: 1.5rem; font-weight: 700; color: #111827; line-height: 1; }
    .route-city .city-time { font-size: 0.82rem; color: #6b7280; margin-top: 4px; }
    .route-arrow { color: #9ca3af; font-size: 1.4rem; }

    .ticket-body {
        padding: 22px 28px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        border-bottom: 2px dashed #e5e7eb;
    }
    .info-item .label {
        font-size: 0.73rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .info-item .value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }

    .ticket-passenger {
        padding: 18px 28px;
        border-bottom: 1px solid #f3f4f6;
    }
    .ticket-passenger .label {
        font-size: 0.73rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.06em; color: #9ca3af; margin-bottom: 4px;
    }
    .ticket-passenger .name { font-size: 1.05rem; font-weight: 700; color: #111827; }
    .ticket-passenger .contact { font-size: 0.85rem; color: #6b7280; margin-top: 2px; }

    .ticket-price {
        padding: 18px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ticket-price .price-label { font-size: 0.85rem; color: #6b7280; font-weight: 500; }
    .ticket-price .price-value { font-size: 1.6rem; font-weight: 700; color: #111827; }

    .ticket-footer {
        background: #f9fafb;
        padding: 14px 28px;
        text-align: center;
        font-size: 0.8rem;
        color: #9ca3af;
    }

    .ticket-actions {
        margin-top: 24px;
        display: flex;
        gap: 12px;
        width: 100%;
        max-width: 480px;
    }
    .btn-print {
        background: #111827; color: #fff; border: none; border-radius: 8px;
        padding: 12px 24px; font-size: 0.9rem; font-weight: 600;
        cursor: pointer; transition: background 0.2s; flex: 1;
    }
    .btn-print:hover { background: #374151; }
    .btn-new {
        background: #fff; color: #1d4ed8; border: 2px solid #1d4ed8; border-radius: 8px;
        padding: 12px 24px; font-size: 0.9rem; font-weight: 600;
        text-decoration: none; transition: all 0.2s; flex: 1; text-align: center;
    }
    .btn-new:hover { background: #1d4ed8; color: #fff; }

    @media print {
        .navbar, .main-content > *:not(.receipt-wrapper) { display: none !important; }
        .ticket-actions { display: none !important; }
        .success-banner { display: none !important; }
        .ticket { box-shadow: none; }
    }
</style>
@endpush

@section('content')

@php
    $viaje    = $tiquete->asientoViaje->viaje;
    $asiento  = $tiquete->asientoViaje->asiento;
    $usuario  = $tiquete->orden->usuario;
    $origen   = $viaje->ruta->municipioOrigen->nombre;
    $destino  = $viaje->ruta->municipioDestino->nombre;
    $salida   = \Carbon\Carbon::parse($viaje->hora_salida);
    $duracion = $viaje->ruta->duracion_estimada;
    $llegada  = $salida->copy()->addMinutes($duracion);
    $fecha    = \Carbon\Carbon::parse($viaje->fecha_salida)->locale('es')->isoFormat('D MMM YYYY');
@endphp

{{-- Stepper --}}
<div class="stepper">
    <div class="step done">
        <div class="step-circle">✓</div>
        <div class="step-label">Ruta</div>
    </div>
    <div class="step-connector done"></div>
    <div class="step done">
        <div class="step-circle">✓</div>
        <div class="step-label">Asiento</div>
    </div>
    <div class="step-connector done"></div>
    <div class="step done">
        <div class="step-circle">✓</div>
        <div class="step-label">Datos</div>
    </div>
    <div class="step-connector done"></div>
    <div class="step active">
        <div class="step-circle">4</div>
        <div class="step-label">Listo</div>
    </div>
</div>

<div class="receipt-wrapper">

    <div class="success-banner">
        <span class="icon">✓</span>
        ¡Tiquete generado exitosamente!
    </div>

    <div class="ticket">

        {{-- Header --}}
        <div class="ticket-header">
            <div>
                <div class="company">CoMotor</div>
                <div class="ticket-num">Tiquete #{{ $tiquete->id_tiquete }}</div>
            </div>
            <div class="badge-estado">{{ strtoupper($tiquete->estado) }}</div>
        </div>

        {{-- Ruta --}}
        <div class="ticket-route">
            <div class="route-city">
                <div class="city-name">{{ $origen }}</div>
                <div class="city-time">{{ $salida->format('h:i A') }}</div>
            </div>
            <div class="route-arrow">→</div>
            <div class="route-city" style="text-align:right;">
                <div class="city-name">{{ $destino }}</div>
                <div class="city-time">{{ $llegada->format('h:i A') }}</div>
            </div>
        </div>

        {{-- Detalles --}}
        <div class="ticket-body">
            <div class="info-item">
                <div class="label">Fecha</div>
                <div class="value">{{ $fecha }}</div>
            </div>
            <div class="info-item">
                <div class="label">Asiento</div>
                <div class="value">#{{ $asiento->numero }}</div>
            </div>
            <div class="info-item">
                <div class="label">Bus</div>
                <div class="value">{{ $viaje->bus->placa }}</div>
            </div>
            <div class="info-item">
                <div class="label">Clase</div>
                <div class="value">{{ $viaje->bus->tipoBus->nombre ?? 'Estándar' }}</div>
            </div>
        </div>

        {{-- Pasajero --}}
        <div class="ticket-passenger">
            <div class="label">Pasajero</div>
            <div class="name">{{ $usuario->nombre }}</div>
            <div class="contact">
                CC {{ $usuario->cedula ?? '—' }}
                {{ $usuario->telefono ? ' · ' . $usuario->telefono : '' }}
            </div>
            <div class="contact">{{ $usuario->email }}</div>
        </div>

        {{-- Precio --}}
        <div class="ticket-price">
            <div class="price-label">Total pagado</div>
            <div class="price-value">${{ number_format($tiquete->precio_final, 0, ',', '.') }}</div>
        </div>

        <div class="ticket-footer">
            Emitido el {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY, h:mm A') }}
        </div>
    </div>

    <div class="ticket-actions">
        <button class="btn-print" onclick="window.print()">Imprimir Tiquete</button>
        <a href="{{ route('viajes.index') }}" class="btn-new">Nueva Venta</a>
    </div>

</div>

@endsection
