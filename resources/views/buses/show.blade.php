@extends('layouts.app')

@section('title', 'Bus ' . $bus->placa)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/taquilla.css') }}">
    <style>
        .show-layout {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 28px;
            align-items: start;
        }

        .show-bus-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
        }
        .show-bus-panel h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            text-align: center;
        }

        .show-info-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 28px;
        }

        .show-info-panel h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }
        .show-tipo-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 28px;
        }
        .info-item label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .info-item span {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
        }

        .amenities-section {
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f3f4f6;
        }
        .amenities-section h3 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 12px;
        }
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .amenity-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .amenity-badge.yes {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .amenity-badge.no {
            background: #f9fafb;
            color: #9ca3af;
            border: 1px solid #e5e7eb;
        }

        .seat-types-section h3 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 12px;
        }
        .seat-type-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .seat-type-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            background: #fff;
        }
        .seat-type-dot {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .back-link:hover { color: #111827; }

        .btn-edit-bus {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            background: #1d4ed8;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s;
            margin-top: 24px;
        }
        .btn-edit-bus:hover { background: #1e40af; }
    </style>
@endpush

@section('content')

@php
    $tipoBus  = $bus->tipoBus;
    $colIzq   = $tipoBus->columnas_izquierda;
    $colDer   = $tipoBus->columnas_derecha;
    $gridCols = implode(' ', array_merge(
        array_fill(0, $colIzq, '1fr'),
        ['20px'],
        array_fill(0, $colDer, '1fr')
    ));

    $pisos = $bus->asientos
        ->sortBy('numero')
        ->groupBy('piso')
        ->map(fn($p) => $p->groupBy('pos_y')->sortKeys()->map->values())
        ->sortKeys();

    // Tipos de asiento únicos presentes en este bus
    $tiposUsados = $bus->asientos
        ->map(fn($a) => $a->tipoAsiento)
        ->filter()
        ->unique('id_tipo_asiento')
        ->values();
@endphp

<a href="{{ route('buses.index') }}" class="back-link">← Volver a Buses</a>

<div class="show-layout">

    {{-- ── Mapa visual del bus ── --}}
    <div class="show-bus-panel">
        <h2>Distribución de Asientos</h2>

        <div class="bus-body" style="max-width: 300px;">
            <div class="driver-row">
                <div class="driver-icon">🧑‍✈️</div>
            </div>

            @if($tipoBus->tiene_tv)
            <div class="bus-extras-row bus-extras-top">
                <div class="bus-extra-item tv">📺<span>TV</span></div>
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
                            @php $asiento = $fila->first(fn($a) => (int)$a->pos_x === $c); @endphp
                            @if($asiento)
                                @php
                                    $color = $asiento->tipoAsiento?->color;
                                    $style = $color
                                        ? "--seat-color:{$color}; --seat-bg:" . $color . '22;'
                                        : '';
                                @endphp
                                <div class="silla-static" style="{{ $style }}"
                                     title="Asiento #{{ $asiento->numero }}{{ $asiento->tipoAsiento ? ' — ' . $asiento->tipoAsiento->codigo : '' }}">
                                    {{ $asiento->numero }}
                                </div>
                            @else
                                <div class="silla vacio"></div>
                            @endif
                        @endfor

                        <div></div>{{-- pasillo --}}

                        @for($c = 0; $c < $colDer; $c++)
                            @php $targetX = $colIzq + 1 + $c; $asiento = $fila->first(fn($a) => (int)$a->pos_x === $targetX); @endphp
                            @if($asiento)
                                @php
                                    $color = $asiento->tipoAsiento?->color;
                                    $style = $color
                                        ? "--seat-color:{$color}; --seat-bg:" . $color . '22;'
                                        : '';
                                @endphp
                                <div class="silla-static" style="{{ $style }}"
                                     title="Asiento #{{ $asiento->numero }}{{ $asiento->tipoAsiento ? ' — ' . $asiento->tipoAsiento->codigo : '' }}">
                                    {{ $asiento->numero }}
                                </div>
                            @else
                                <div class="silla vacio"></div>
                            @endif
                        @endfor

                    </div>
                    @endforeach
                </div>
            @endforeach

            @if($tipoBus->tiene_bano)
            <div class="bus-extras-row">
                <div class="bus-extra-item bano">🚽<span>Baño</span></div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Información del bus ── --}}
    <div class="show-info-panel">
        <h1>{{ $bus->placa }}</h1>
        <div class="show-tipo-label">{{ $tipoBus->nombre ?? '—' }}</div>

        <div class="info-grid">
            <div class="info-item">
                <label>Propietario</label>
                <span>{{ $bus->propietario->nombre ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Capacidad</label>
                <span>{{ $bus->capacidad }} asientos</span>
            </div>
            <div class="info-item">
                <label>Columnas izq.</label>
                <span>{{ $tipoBus->columnas_izquierda }}</span>
            </div>
            <div class="info-item">
                <label>Columnas der.</label>
                <span>{{ $tipoBus->columnas_derecha }}</span>
            </div>
            @if($tipoBus->descripcion)
            <div class="info-item" style="grid-column: span 2;">
                <label>Descripción</label>
                <span>{{ $tipoBus->descripcion }}</span>
            </div>
            @endif
        </div>

        <div class="amenities-section">
            <h3>Amenidades del tipo</h3>
            <div class="amenities-list">
                <span class="amenity-badge {{ $tipoBus->tiene_bano ? 'yes' : 'no' }}">
                    🚽 Baño {{ $tipoBus->tiene_bano ? '✓' : '✗' }}
                </span>
                <span class="amenity-badge {{ $tipoBus->tiene_tv ? 'yes' : 'no' }}">
                    📺 TV {{ $tipoBus->tiene_tv ? '✓' : '✗' }}
                </span>
                <span class="amenity-badge {{ $tipoBus->doble_piso ? 'yes' : 'no' }}">
                    🏢 Doble piso {{ $tipoBus->doble_piso ? '✓' : '✗' }}
                </span>
            </div>
        </div>

        @if($tiposUsados->count() > 0)
        <div class="seat-types-section">
            <h3>Tipos de asiento</h3>
            <div class="seat-type-legend">
                @foreach($tiposUsados as $tipo)
                <div class="seat-type-chip">
                    <div class="seat-type-dot" style="background: {{ $tipo->color ?? '#d1d5db' }};"></div>
                    {{ ucfirst($tipo->codigo) }}
                    @if($tipo->descripcion)
                        <span style="font-weight:400; color:#9ca3af;">— {{ $tipo->descripcion }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('buses.edit', $bus->id_bus) }}" class="btn-edit-bus">Editar Bus</a>
    </div>

</div>

@endsection
