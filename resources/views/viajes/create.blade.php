@extends('layouts.app')

@section('title', 'Nuevo Viaje')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }
    .page-header .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: #6b7280;
        text-decoration: none;
        margin-bottom: 12px;
    }
    .page-header .back-link:hover { color: #1d4ed8; }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px 36px;
        max-width: 680px;
    }

    .form-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 16px;
        margin-top: 28px;
    }
    .form-section-title:first-child { margin-top: 0; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-row.single { grid-template-columns: 1fr; }

    .form-group { margin-bottom: 0; }
    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-group select,
    .form-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #111827;
        background: #fff;
        transition: border-color 0.15s;
        box-sizing: border-box;
    }
    .form-group select:focus,
    .form-group input:focus {
        outline: none;
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
    }
    .form-group .is-invalid {
        border-color: #ef4444;
    }
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 4px;
    }

    .form-stack { display: flex; flex-direction: column; gap: 16px; }

    .divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 28px 0 0;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
    }
    .btn-primary {
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 28px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-primary:hover { background: #1e40af; }
    .btn-cancel {
        color: #6b7280;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .btn-cancel:hover { color: #111827; }
</style>
@endpush

@section('content')

<div class="page-header">
    <a href="{{ route('viajes.index') }}" class="back-link">← Volver a viajes</a>
    <h1>Nuevo Viaje</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('viajes.store') }}">
        @csrf

        {{-- RUTA --}}
        <p class="form-section-title">Ruta</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_ruta">Ruta</label>
                <select name="id_ruta" id="id_ruta" class="{{ $errors->has('id_ruta') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione una ruta —</option>
                    @foreach ($rutas as $ruta)
                        <option value="{{ $ruta->id_ruta }}" {{ old('id_ruta') == $ruta->id_ruta ? 'selected' : '' }}>
                            {{ $ruta->municipioOrigen->nombre }} → {{ $ruta->municipioDestino->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_ruta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- CONDUCTOR --}}
        <p class="form-section-title">Conductor</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_conductor">Conductor asignado</label>
                <select name="id_conductor" id="id_conductor" class="{{ $errors->has('id_conductor') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un conductor —</option>
                    @foreach ($conductores as $conductor)
                        <option value="{{ $conductor->id_conductor }}" {{ old('id_conductor') == $conductor->id_conductor ? 'selected' : '' }}>
                            {{ $conductor->nombre }} — Lic: {{ $conductor->licencia }}
                        </option>
                    @endforeach
                </select>
                @error('id_conductor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($conductores->isEmpty())
                    <div style="font-size:0.78rem; color:#ef4444; margin-top:4px;">
                        No hay conductores registrados. <a href="{{ route('conductores.create') }}">Registrar uno</a>.
                    </div>
                @endif
            </div>
        </div>

        {{-- BUS --}}
        <p class="form-section-title">Bus</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="filtro_tipo_bus">Filtrar por tipo de bus</label>
                <select id="filtro_tipo_bus">
                    <option value="">— Todos los tipos —</option>
                    @foreach($tiposBus as $tipo)
                        <option value="{{ $tipo->id_tipo_bus }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_bus">Bus</label>
                <select name="id_bus" id="id_bus" class="{{ $errors->has('id_bus') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un bus —</option>
                    @foreach ($buses as $bus)
                        <option value="{{ $bus->id_bus }}"
                                data-tipo="{{ $bus->id_tipo_bus }}"
                                {{ old('id_bus') == $bus->id_bus ? 'selected' : '' }}>
                            {{ $bus->placa }} — {{ $bus->tipoBus->nombre ?? 'Sin tipo' }} ({{ $bus->capacidad }} asientos)
                        </option>
                    @endforeach
                </select>
                @error('id_bus')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- FECHA Y HORA --}}
        <p class="form-section-title">Horario</p>
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_salida">Fecha de salida</label>
                <input type="date" name="fecha_salida" id="fecha_salida"
                    value="{{ old('fecha_salida') }}"
                    class="{{ $errors->has('fecha_salida') ? 'is-invalid' : '' }}">
                @error('fecha_salida')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="hora_salida">Hora de salida</label>
                <input type="time" name="hora_salida" id="hora_salida"
                    value="{{ old('hora_salida') }}"
                    class="{{ $errors->has('hora_salida') ? 'is-invalid' : '' }}">
                @error('hora_salida')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- PRECIOS --}}
        <p class="form-section-title">Precios</p>
        <div class="form-row">
            <div class="form-group">
                <label for="precio_base">Precio base ($)</label>
                <input type="number" name="precio_base" id="precio_base"
                    value="{{ old('precio_base') }}"
                    min="0" step="100"
                    class="{{ $errors->has('precio_base') ? 'is-invalid' : '' }}">
                @error('precio_base')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="precio_final">Precio final ($)</label>
                <input type="number" name="precio_final" id="precio_final"
                    value="{{ old('precio_final') }}"
                    min="0" step="100"
                    class="{{ $errors->has('precio_final') ? 'is-invalid' : '' }}">
                @error('precio_final')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ESTADO --}}
        <p class="form-section-title">Estado</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="estado">Estado del viaje</label>
                <select name="estado" id="estado" class="{{ $errors->has('estado') ? 'is-invalid' : '' }}">
                    <option value="programado"  {{ old('estado', 'programado') === 'programado'  ? 'selected' : '' }}>Programado</option>
                    <option value="en_ruta"     {{ old('estado') === 'en_ruta'     ? 'selected' : '' }}>En ruta</option>
                    <option value="completado"  {{ old('estado') === 'completado'  ? 'selected' : '' }}>Completado</option>
                    <option value="cancelado"   {{ old('estado') === 'cancelado'   ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="divider">

        <div class="form-actions">
            <button type="submit" class="btn-primary">Crear Viaje</button>
            <a href="{{ route('viajes.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Sincronizar precio_final con precio_base si está vacío
    document.getElementById('precio_base').addEventListener('input', function () {
        const pf = document.getElementById('precio_final');
        if (!pf.value) pf.value = this.value;
    });

    // Filtrar buses por tipo de bus
    document.getElementById('filtro_tipo_bus').addEventListener('change', function () {
        const tipoId = this.value;
        const selectBus = document.getElementById('id_bus');
        const options = selectBus.querySelectorAll('option');

        options.forEach(function (opt) {
            if (!opt.value) return; // skip placeholder
            opt.hidden = tipoId !== '' && opt.dataset.tipo !== tipoId;
        });

        // Reset selection if current option is now hidden
        const selected = selectBus.options[selectBus.selectedIndex];
        if (selected && selected.hidden) selectBus.value = '';
    });
</script>
@endpush

@endsection
