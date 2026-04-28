@extends('layouts.app')

@section('title', 'Editar Conductor')

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
    <a href="{{ route('conductores.index') }}" class="back-link">← Volver a conductores</a>
    <h1>Editar Conductor — {{ $conductor->nombre }}</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('conductores.update', $conductor->id_conductor) }}">
        @csrf @method('PUT')

        <p class="form-section-title">Datos personales</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre"
                    value="{{ old('nombre', $conductor->nombre) }}"
                    class="{{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cedula">Cédula</label>
                    <input type="text" name="cedula" id="cedula"
                        value="{{ old('cedula', $conductor->cedula) }}"
                        class="{{ $errors->has('cedula') ? 'is-invalid' : '' }}">
                    @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional)</label>
                    <input type="text" name="telefono" id="telefono"
                        value="{{ old('telefono', $conductor->telefono) }}"
                        class="{{ $errors->has('telefono') ? 'is-invalid' : '' }}">
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <p class="form-section-title">Licencia de conducción</p>
        <div class="form-group">
            <label for="licencia">Número de licencia</label>
            <input type="text" name="licencia" id="licencia"
                value="{{ old('licencia', $conductor->licencia) }}"
                class="{{ $errors->has('licencia') ? 'is-invalid' : '' }}">
            @error('licencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <hr class="divider">
        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Cambios</button>
            <a href="{{ route('conductores.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

@endsection
