@extends('layouts.app')

@section('title', 'Editar Ruta')

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
        border-radius: 12px; padding: 32px 36px; max-width: 620px;
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
    .form-group select,
    .form-group input {
        width: 100%; padding: 10px 14px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 0.9rem; color: #111827;
        background: #fff; transition: border-color 0.15s; box-sizing: border-box;
    }
    .form-group select:focus,
    .form-group input:focus {
        outline: none; border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
    }
    .form-group .is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }

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
    <a href="{{ route('rutas.index') }}" class="back-link">← Volver a rutas</a>
    <h1>Editar Ruta</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('rutas.update', $ruta->id_ruta) }}">
        @csrf @method('PUT')

        {{-- ORIGEN --}}
        <p class="form-section-title">Origen — Huila</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_municipio_origen">Municipio de origen</label>
                <select name="id_municipio_origen" id="id_municipio_origen"
                    class="{{ $errors->has('id_municipio_origen') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un municipio —</option>
                    @foreach($municipios as $mun)
                        <option value="{{ $mun->id_municipio }}"
                            {{ old('id_municipio_origen', $ruta->id_municipio_origen) == $mun->id_municipio ? 'selected' : '' }}>
                            {{ $mun->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_municipio_origen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- DESTINO --}}
        <p class="form-section-title">Destino — Huila</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_municipio_destino">Municipio de destino</label>
                <select name="id_municipio_destino" id="id_municipio_destino"
                    class="{{ $errors->has('id_municipio_destino') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un municipio —</option>
                    @foreach($municipios as $mun)
                        <option value="{{ $mun->id_municipio }}"
                            {{ old('id_municipio_destino', $ruta->id_municipio_destino) == $mun->id_municipio ? 'selected' : '' }}>
                            {{ $mun->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_municipio_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- DURACIÓN --}}
        <p class="form-section-title">Duración</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="duracion_estimada">Duración estimada (minutos)</label>
                <input type="number" name="duracion_estimada" id="duracion_estimada"
                    value="{{ old('duracion_estimada', $ruta->duracion_estimada) }}"
                    min="1"
                    class="{{ $errors->has('duracion_estimada') ? 'is-invalid' : '' }}">
                @error('duracion_estimada') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <hr class="divider">
        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Cambios</button>
            <a href="{{ route('rutas.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

@endsection
