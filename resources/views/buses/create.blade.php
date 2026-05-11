@extends('layouts.app')

@section('title', 'Nuevo Bus')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }
    .page-header .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.85rem; color: #6b7280; text-decoration: none; margin-bottom: 12px;
    }
    .page-header .back-link:hover { color: #1d4ed8; }

    .form-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 12px; padding: 32px 36px; max-width: 680px;
    }
    .form-section-title {
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: #9ca3af;
        margin-bottom: 16px; margin-top: 28px;
    }
    .form-section-title:first-child { margin-top: 0; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-stack { display: flex; flex-direction: column; gap: 16px; }
    .form-group label {
        display: block; font-size: 0.85rem; font-weight: 600;
        color: #374151; margin-bottom: 6px;
    }
    .form-group select, .form-group input {
        width: 100%; padding: 10px 14px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 0.9rem; color: #111827;
        background: #fff; transition: border-color 0.15s; box-sizing: border-box;
    }
    .form-group select:focus, .form-group input:focus {
        outline: none; border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
    }
    .form-group .is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }
    .form-hint { font-size: 0.78rem; color: #9ca3af; margin-top: 4px; }

    .divider { border: none; border-top: 1px solid #f3f4f6; margin: 28px 0 0; }
    .form-actions { display: flex; align-items: center; gap: 12px; margin-top: 28px; }
    .btn-primary {
        background: #1d4ed8; color: #fff; border: none; border-radius: 8px;
        padding: 12px 28px; font-size: 0.9rem; font-weight: 600; cursor: pointer;
        transition: background 0.2s;
    }
    .btn-primary:hover { background: #1e40af; }
    .btn-cancel { color: #6b7280; text-decoration: none; font-size: 0.9rem; }
    .btn-cancel:hover { color: #111827; }
</style>
@endpush

@section('content')

<div class="page-header">
    <a href="{{ route('buses.index') }}" class="back-link">← Volver a buses</a>
    <h1>Nuevo Bus</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('buses.store') }}">
        @csrf

        <p class="form-section-title">Datos del Bus</p>
        <div class="form-row">
            <div class="form-group">
                <label for="placa">Placa</label>
                <input type="text" name="placa" id="placa"
                    value="{{ old('placa') }}" placeholder="Ej: ABC-123"
                    class="{{ $errors->has('placa') ? 'is-invalid' : '' }}">
                @error('placa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="capacidad">Capacidad (asientos)</label>
                <input type="number" name="capacidad" id="capacidad"
                    value="{{ old('capacidad') }}" min="1" max="80"
                    class="{{ $errors->has('capacidad') ? 'is-invalid' : '' }}">
                <div class="form-hint">Se auto-completa al seleccionar el tipo de bus. Puedes ajustarla si este bus tiene una capacidad diferente.</div>
                @error('capacidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <p class="form-section-title">Propietario</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_propietario">Propietario del Bus</label>
                <select name="id_propietario" id="id_propietario"
                    class="{{ $errors->has('id_propietario') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un propietario —</option>
                    @foreach($propietarios as $propietario)
                        <option value="{{ $propietario->id_propietario }}"
                            {{ old('id_propietario') == $propietario->id_propietario ? 'selected' : '' }}>
                            {{ $propietario->nombre }} ({{ ucfirst($propietario->tipo) }} · {{ $propietario->cedula_nit }})
                        </option>
                    @endforeach
                </select>
                @error('id_propietario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($propietarios->isEmpty())
                    <div class="form-hint" style="color:#ef4444;">
                        No hay propietarios. <a href="{{ route('propietarios.create') }}">Registrar uno</a>.
                    </div>
                @endif
            </div>
        </div>

        <p class="form-section-title">Clasificación</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_tipo_bus">Tipo de Bus</label>
                <select name="id_tipo_bus" id="id_tipo_bus"
                    class="{{ $errors->has('id_tipo_bus') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un tipo —</option>
                    @foreach($tiposBus as $tipo)
                        <option value="{{ $tipo->id_tipo_bus }}"
                            data-capacidad="{{ $tipo->capacidad_default }}"
                            {{ old('id_tipo_bus') == $tipo->id_tipo_bus ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                            {{ $tipo->tiene_bano ? '· Baño' : '' }}
                            {{ $tipo->tiene_tv ? '· TV' : '' }}
                            {{ $tipo->doble_piso ? '· Doble piso' : '' }}
                            {{ $tipo->capacidad_default ? '· ' . $tipo->capacidad_default . ' asientos' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_tipo_bus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($tiposBus->isEmpty())
                    <div class="form-hint" style="color:#ef4444;">
                        No hay tipos de bus. <a href="{{ route('tipos-bus.create') }}">Crear uno</a>.
                    </div>
                @endif
            </div>

        </div>

        <hr class="divider">
        <div class="form-actions">
            <button type="submit" class="btn-primary">Crear Bus</button>
            <a href="{{ route('buses.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('id_tipo_bus').addEventListener('change', function () {
    var cap = this.options[this.selectedIndex].dataset.capacidad;
    if (cap) {
        document.getElementById('capacidad').value = cap;
    }
});
</script>
@endpush

@endsection
