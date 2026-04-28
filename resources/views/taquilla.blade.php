@extends('layouts.app')

@section('title', 'Selección de Asiento')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/taquilla.css') }}">
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

    $tipoBus  = $viaje->bus->tipoBus;
    $colIzq   = $tipoBus->columnas_izquierda;
    $colDer   = $tipoBus->columnas_derecha;
    $gridCols = implode(' ', array_merge(
        array_fill(0, $colIzq, '1fr'),
        ['20px'],
        array_fill(0, $colDer, '1fr')
    ));
    // Agrupar por piso → por fila (pos_y)
    $pisos = $asientosViaje
        ->sortBy('asiento.numero')
        ->groupBy('asiento.piso')
        ->map(fn($p) => $p->groupBy('asiento.pos_y')->sortKeys()->map->values())
        ->sortKeys();
@endphp

{{-- Stepper --}}
<div class="stepper">
    <div class="step done">
        <div class="step-circle">✓</div>
        <div class="step-label">Ruta</div>
    </div>
    <div class="step-connector done"></div>
    <div class="step active">
        <div class="step-circle">2</div>
        <div class="step-label">Asientos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step">
        <div class="step-circle">3</div>
        <div class="step-label">Datos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step">
        <div class="step-circle">4</div>
        <div class="step-label">Listo</div>
    </div>
</div>

{{-- Formulario GET que lleva al paso de datos del pasajero --}}
<form id="form-compra" method="GET" action="{{ route('taquilla.confirmar', $viaje->id_viaje) }}">
    <input type="hidden" id="input-numero" name="numero_puesto" value="">
</form>

<div class="seat-layout">

    {{-- ── Mapa de asientos ── --}}
    <div class="bus-panel">
        <div class="bus-panel-header">
            <h2>Seleccionar Asiento</h2>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-dot disponible"></div> Disponible
                </div>
                <div class="legend-item">
                    <div class="legend-dot ocupado"></div> Ocupado
                </div>
                <div class="legend-item">
                    <div class="legend-dot seleccionado"></div> Seleccionado
                </div>
            </div>
        </div>

        <div class="bus-body">
            <div class="driver-row">
                <div class="driver-icon">🧑‍✈️</div>
            </div>

            @if($tipoBus->tiene_tv || $tipoBus->tiene_bano)
            <div class="bus-extras-row bus-extras-top">
                @if($tipoBus->tiene_tv)
                    <div class="bus-extra-item tv">📺<span>TV</span></div>
                @endif
            </div>
            @endif

            @foreach($pisos as $pisoNum => $filasDelPiso)
                @if($tipoBus->doble_piso && $loop->index > 0)
                    <div class="floor-divider"><span>Piso {{ $pisoNum }}</span></div>
                @elseif($tipoBus->doble_piso)
                    <div class="floor-label">Piso 1</div>
                @endif

                <div class="seats-grid">
                    @foreach($filasDelPiso as $fila)
                    <div class="seat-row" style="grid-template-columns: {{ $gridCols }};">

                        @for($c = 0; $c < $colIzq; $c++)
                            @php $av = $fila->first(fn($item) => (int)$item->asiento->pos_x === $c); @endphp
                            @if($av)
                                @php $estado = $av->estado; @endphp
                                <div class="silla {{ $estado }}"
                                     data-numero="{{ $av->asiento->numero }}"
                                     @if($estado === 'disponible') onclick="seleccionarAsiento(this)" @endif
                                     title="{{ $estado === 'ocupado' ? 'Ocupado' : 'Disponible' }}">
                                    {{ $av->asiento->numero }}
                                </div>
                            @else
                                <div class="silla vacio"></div>
                            @endif
                        @endfor

                        <div></div>{{-- pasillo --}}

                        @for($c = 0; $c < $colDer; $c++)
                            @php $targetX = $colIzq + 1 + $c; $av = $fila->first(fn($item) => (int)$item->asiento->pos_x === $targetX); @endphp
                            @if($av)
                                @php $estado = $av->estado; @endphp
                                <div class="silla {{ $estado }}"
                                     data-numero="{{ $av->asiento->numero }}"
                                     @if($estado === 'disponible') onclick="seleccionarAsiento(this)" @endif
                                     title="{{ $estado === 'ocupado' ? 'Ocupado' : 'Disponible' }}">
                                    {{ $av->asiento->numero }}
                                </div>
                            @else
                                <div class="silla vacio"></div>
                            @endif
                        @endfor

                    </div>
                    @endforeach
                </div>
            @endforeach
            @if($tipoBus->tiene_bano || $tipoBus->tiene_tv)
            <div class="bus-extras-row">
                @if($tipoBus->tiene_bano)
                    <div class="bus-extra-item bano">🚽<span>Baño</span></div>
                @endif
            </div>
            @endif
        </div>
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
                <span class="val blue" id="resumen-asiento">— Selecciona uno</span>
            </div>
        </div>

        <div class="total-section">
            <div class="total-label">Precio Total</div>
            <div class="total-price">${{ $total }}</div>
            <div class="total-taxes">Impuestos y tasas incluidos</div>
        </div>

        <button id="btn-confirmar" type="button"
                onclick="document.getElementById('form-compra').submit()">
            Continuar &rarr;
        </button>
        <div id="btn-placeholder">Selecciona un asiento</div>
    </div>

</div>

<script>
function seleccionarAsiento(el) {
    document.querySelectorAll('.silla.seleccionado').forEach(function(s) {
        s.classList.remove('seleccionado');
        s.classList.add('disponible');
    });

    el.classList.remove('disponible');
    el.classList.add('seleccionado');

    var numero = el.dataset.numero;
    document.getElementById('input-numero').value = numero;
    document.getElementById('resumen-asiento').textContent = '#' + numero;

    document.getElementById('btn-confirmar').style.display = 'flex';
    document.getElementById('btn-placeholder').style.display = 'none';
}
</script>

@endsection
