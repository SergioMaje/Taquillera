@extends('layouts.app')

@section('title', 'Tiquete #' . $tiquete->id)

@push('styles')
<style>
    .ticket {
        border: 2px dashed #333;
        padding: 28px;
        width: 340px;
        font-family: monospace;
        background: #fff;
        border-radius: 4px;
    }
    .ticket h2 { text-align: center; margin-bottom: 12px; }
    .ticket hr { border-color: #aaa; margin: 12px 0; }
    .ticket p { margin: 6px 0; font-size: 0.95rem; }
    .ticket .footer { text-align: center; margin-top: 10px; font-style: italic; }
    .ticket-actions { margin-top: 16px; display: flex; gap: 12px; align-items: center; }
    .btn-print {
        background: #333;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
    }
    .btn-print:hover { background: #555; }
</style>
@endpush

@section('content')
    <h1 style="margin-bottom:20px;">Recibo de Venta</h1>

    <div class="ticket">
        <h2>TIQUETE DE BUS</h2>
        <hr>
        <p><strong>Pasajero:</strong> {{ $tiquete->cliente->nombre }}</p>
        <p><strong>Cédula:</strong> {{ $tiquete->cliente->cedula }}</p>
        <hr>
        <p><strong>Ruta:</strong> {{ $tiquete->viaje->ruta->origen }} → {{ $tiquete->viaje->ruta->destino }}</p>
        <p><strong>Bus:</strong> {{ $tiquete->bus->placa }} &nbsp;|&nbsp; <strong>Puesto:</strong> #{{ $tiquete->puesto }}</p>
        <p><strong>Precio:</strong> ${{ number_format($tiquete->precio_pagado, 0) }}</p>
        <hr>
        <p class="footer">¡Buen viaje!</p>
    </div>

    <div class="ticket-actions">
        <button class="btn-print" onclick="window.print()">Imprimir Tiquete</button>
        <a href="{{ route('taquilla') }}">Volver a la Taquilla</a>
    </div>
@endsection
