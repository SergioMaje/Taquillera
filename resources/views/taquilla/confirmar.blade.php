@extends('layouts.app')

@section('title', 'Datos del Pasajero')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/taquilla.css') }}">
<style>
    .confirm-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 28px;
        align-items: start;
    }

    /* Formulario de pasajero */
    .passenger-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px 36px;
    }
    .passenger-card h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 24px;
    }
    .form-section-title {
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: #9ca3af;
        margin-bottom: 14px; margin-top: 24px;
    }
    .form-section-title:first-child { margin-top: 0; }
    .form-stack { display: flex; flex-direction: column; gap: 16px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group label {
        display: block; font-size: 0.85rem; font-weight: 600;
        color: #374151; margin-bottom: 6px;
    }
    .form-group input {
        width: 100%; padding: 10px 14px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 0.9rem; color: #111827;
        background: #fff; transition: border-color 0.15s; box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none; border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
    }
    .form-group .is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }

    .divider { border: none; border-top: 1px solid #f3f4f6; margin: 28px 0 0; }
    .form-actions { display: flex; align-items: center; gap: 14px; margin-top: 24px; }
    .btn-comprar {
        background: #1d4ed8; color: #fff; border: none; border-radius: 8px;
        padding: 13px 32px; font-size: 0.95rem; font-weight: 700; cursor: pointer;
        transition: background 0.2s; flex: 1;
    }
    .btn-comprar:hover { background: #1e40af; }
    .btn-back { color: #6b7280; text-decoration: none; font-size: 0.9rem; white-space: nowrap; }
    .btn-back:hover { color: #111827; }
</style>
@endpush

@section('content')

@php
    $origen   = $viaje->ruta->municipioOrigen->nombre;
    $destino  = $viaje->ruta->municipioDestino->nombre;
    $duracion = $viaje->ruta->duracion_estimada;
    $salida   = \Carbon\Carbon::parse($viaje->hora_salida);
    $llegada  = $salida->copy()->addMinutes($duracion);
    $clase    = $viaje->bus->tipoBus->nombre ?? 'Estándar';
    $total    = number_format($viaje->precio_final, 0, ',', '.');
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
    <div class="step active">
        <div class="step-circle">3</div>
        <div class="step-label">Datos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step">
        <div class="step-circle">4</div>
        <div class="step-label">Listo</div>
    </div>
</div>

<div class="confirm-layout">

    {{-- ── Formulario de pasajero ── --}}
    <div class="passenger-card">
        <h2>Datos del Pasajero</h2>

        <form method="POST" action="{{ route('taquilla.comprar') }}">
            @csrf
            <input type="hidden" name="id_asiento_viaje" value="{{ $asientoViaje->id_asiento_viaje }}">
            <input type="hidden" name="viaje_id"         value="{{ $viaje->id_viaje }}">

            <p class="form-section-title">Información Personal</p>
            <div class="form-stack">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre"
                        value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez"
                        class="{{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cedula">Cédula</label>
                    <input type="text" name="cedula" id="cedula"
                        value="{{ old('cedula') }}" placeholder="Ej: 1234567890"
                        class="{{ $errors->has('cedula') ? 'is-invalid' : '' }}">
                    @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <p class="form-section-title">Contacto</p>
            <div class="form-stack">
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}" placeholder="correo@ejemplo.com"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono <span style="color:#9ca3af;font-weight:400">(opcional)</span></label>
                        <input type="text" name="telefono" id="telefono"
                            value="{{ old('telefono') }}" placeholder="Ej: 3001234567">
                    </div>
                </div>
            </div>

            <hr class="divider">
            <div class="form-actions">
                <a href="{{ route('taquilla', $viaje->id_viaje) }}" class="btn-back">← Cambiar asiento</a>
                <button type="submit" class="btn-comprar">Confirmar Compra → ${{ $total }}</button>
            </div>
        </form>
    </div>

    {{-- ── Resumen del viaje ── --}}
    <div class="summary-panel">
        <h3>Resumen del Viaje</h3>

        <div class="summary-route">
            <div class="route-point">
                <div class="route-dot from"></div>
                <div>
                    <div class="rp-label">Desde</div>
                    <div class="rp-city">{{ $origen }}</div>
                    <div class="rp-time">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d M Y') }}, {{ $salida->format('h:i A') }}</div>
                </div>
            </div>
            <div class="route-point">
                <div class="route-dot to"></div>
                <div>
                    <div class="rp-label">Hasta</div>
                    <div class="rp-city">{{ $destino }}</div>
                    <div class="rp-time">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d M Y') }}, {{ $llegada->format('h:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="summary-details">
            <div class="summary-row">
                <span class="key">Operador</span>
                <span class="val">CoMotor</span>
            </div>
            <div class="summary-row">
                <span class="key">Clase</span>
                <span class="val">{{ $clase }}</span>
            </div>
            <div class="summary-row">
                <span class="key">Placa</span>
                <span class="val">{{ $viaje->bus->placa }}</span>
            </div>
            <div class="summary-row">
                <span class="key">Asiento</span>
                <span class="val blue">#{{ $asiento->numero }}</span>
            </div>
        </div>

        <div class="total-section">
            <div class="total-label">Precio Total</div>
            <div class="total-price">${{ $total }}</div>
            <div class="total-taxes">Impuestos y tasas incluidos</div>
        </div>
    </div>

</div>

@endsection
