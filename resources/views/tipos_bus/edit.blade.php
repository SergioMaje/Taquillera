@extends('layouts.app')

@section('title', 'Editar Tipo de Bus')

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
        border-radius: 12px; padding: 32px 36px; max-width: 560px;
    }
    .form-section-title {
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: #9ca3af;
        margin-bottom: 16px; margin-top: 28px;
    }
    .form-section-title:first-child { margin-top: 0; }
    .form-stack { display: flex; flex-direction: column; gap: 16px; }
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

    .checkbox-group { display: flex; flex-direction: column; gap: 12px; }
    .checkbox-label {
        display: flex; align-items: center; gap: 10px;
        font-size: 0.9rem; color: #374151; cursor: pointer;
    }
    .checkbox-label input[type=checkbox] {
        width: 16px; height: 16px; cursor: pointer; accent-color: #1d4ed8;
    }

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
    <a href="{{ route('tipos-bus.index') }}" class="back-link">← Volver a tipos de bus</a>
    <h1>Editar Tipo de Bus</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('tipos-bus.update', $tipoBus->id_tipo_bus) }}">
        @csrf @method('PUT')

        <p class="form-section-title">Información General</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre"
                    value="{{ old('nombre', $tipoBus->nombre) }}"
                    class="{{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción <span style="color:#9ca3af;font-weight:400">(opcional)</span></label>
                <input type="text" name="descripcion" id="descripcion"
                    value="{{ old('descripcion', $tipoBus->descripcion) }}">
            </div>
        </div>

        <p class="form-section-title">Características</p>
        <div class="checkbox-group">
            <label class="checkbox-label">
                <input type="checkbox" name="tiene_bano" value="1"
                    {{ old('tiene_bano', $tipoBus->tiene_bano) ? 'checked' : '' }}>
                Tiene baño
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="tiene_tv" value="1"
                    {{ old('tiene_tv', $tipoBus->tiene_tv) ? 'checked' : '' }}>
                Tiene TV
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="doble_piso" value="1"
                    {{ old('doble_piso', $tipoBus->doble_piso) ? 'checked' : '' }}>
                Doble piso
            </label>
        </div>

        <p class="form-section-title">Distribución de Asientos</p>
        <div class="form-stack">
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1">
                    <label for="columnas_izquierda">Columnas izquierda</label>
                    <input type="number" name="columnas_izquierda" id="columnas_izquierda"
                           min="1" max="3" value="{{ old('columnas_izquierda', $tipoBus->columnas_izquierda) }}" required
                           class="{{ $errors->has('columnas_izquierda') ? 'is-invalid' : '' }}">
                    @error('columnas_izquierda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="flex:1">
                    <label for="columnas_derecha">Columnas derecha</label>
                    <input type="number" name="columnas_derecha" id="columnas_derecha"
                           min="1" max="3" value="{{ old('columnas_derecha', $tipoBus->columnas_derecha) }}" required
                           class="{{ $errors->has('columnas_derecha') ? 'is-invalid' : '' }}">
                    @error('columnas_derecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="flex:1">
                    <label for="capacidad_default">Capacidad por defecto</label>
                    <input type="number" name="capacidad_default" id="capacidad_default"
                           min="1" max="80" value="{{ old('capacidad_default', $tipoBus->capacidad_default) }}" placeholder="Ej: 40"
                           class="{{ $errors->has('capacidad_default') ? 'is-invalid' : '' }}">
                    @error('capacidad_default')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <p style="font-size:0.8rem;color:#6b7280;margin:0">
                Ej: 2 + 2 = bus estándar &nbsp;|&nbsp; 1 + 2 = bus ejecutivo &nbsp;|&nbsp; 1 + 1 = minivan.
                La capacidad por defecto se auto-completa al crear un bus de este tipo.
            </p>
        </div>

        <hr class="divider">
        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Cambios</button>
            <a href="{{ route('tipos-bus.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

@endsection
