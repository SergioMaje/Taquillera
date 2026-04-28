@extends('layouts.app')

@section('title', 'Vender Tiquetes')
@section('page-title', 'Vender Tiquetes')

@push('styles')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px 36px;
        max-width: 680px;
    }

    .section-label {
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: #9ca3af;
        margin-bottom: 14px; margin-top: 28px;
    }
    .section-label:first-child { margin-top: 0; }

    .form-stack { display: flex; flex-direction: column; gap: 14px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .form-group label {
        display: block; font-size: 0.83rem; font-weight: 600;
        color: #374151; margin-bottom: 5px;
    }
    .form-group input, .form-group select {
        width: 100%; padding: 9px 13px;
        border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 0.9rem; color: #111827;
        background: #fff; transition: border-color 0.15s;
    }
    .form-group input:focus, .form-group select:focus {
        outline: none; border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .is-invalid { border-color: #ef4444 !important; }
    .invalid-feedback { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }

    /* Grilla de asientos */
    .asientos-grid {
        display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;
    }
    .asiento-btn {
        display: flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px; cursor: pointer;
        border: 2px solid #d1d5db; background: #f9fafb;
        font-size: 0.875rem; font-weight: 500; color: #374151;
        transition: all 0.15s; user-select: none;
    }
    .asiento-btn:hover { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; }
    .asiento-btn.seleccionado { border-color: #2563eb; background: #dbeafe; color: #1d4ed8; }
    .asiento-btn input[type="checkbox"] { display: none; }

    /* Resumen precio */
    .resumen-total {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        color: #15803d; border-radius: 8px;
        padding: 8px 14px; font-size: 0.875rem; font-weight: 600;
        margin-top: 10px;
    }

    /* Cards de pasajeros */
    #pasajeros-section { margin-top: 4px; }
    .pasajero-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 12px;
    }
    .pasajero-card-title {
        font-size: 0.85rem; font-weight: 700; color: #1e40af;
        margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .pasajero-card-title .badge {
        background: #2563eb; color: #fff;
        border-radius: 6px; padding: 2px 8px; font-size: 0.75rem;
    }

    .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }

    .btn-submit {
        background: #2563eb; color: #fff; border: none;
        padding: 12px 32px; border-radius: 8px;
        font-size: 0.95rem; font-weight: 600; cursor: pointer;
        transition: background 0.2s;
    }
    .btn-submit:hover { background: #1d4ed8; }
    .btn-cancel { color: #6b7280; text-decoration: none; font-size: 0.9rem; margin-left: 16px; }
    .btn-cancel:hover { color: #111827; }

    #asientos-wrap { display: none; }
    #pasajeros-section { display: none; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Vender Tiquetes</h1>
</div>

<div class="form-card">
    <form action="{{ route('tiquetes.store') }}" method="POST" id="form-venta">
        @csrf

        {{-- JSON con asientos disponibles por viaje --}}
        @php
            $viajesData = $viajes->mapWithKeys(fn($v) => [
                $v->id_viaje => $v->asientosViaje->map(fn($av) => [
                    'id'     => $av->id_asiento_viaje,
                    'numero' => $av->asiento->numero ?? $av->id_asiento_viaje,
                ])->values()
            ]);
        @endphp
        <script id="viajes-json" type="application/json">{{ $viajesData->toJson() }}</script>

        {{-- 1. VIAJE --}}
        <p class="section-label">1. Seleccionar viaje</p>
        <div class="form-stack">
            <div class="form-group">
                <label for="id_viaje">Viaje</label>
                <select name="id_viaje" id="id_viaje" required
                    class="{{ $errors->has('id_viaje') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccione un viaje —</option>
                    @foreach($viajes as $viaje)
                        <option value="{{ $viaje->id_viaje }}"
                            data-precio="{{ $viaje->precio_final }}"
                            {{ old('id_viaje') == $viaje->id_viaje ? 'selected' : '' }}>
                            {{ $viaje->ruta->municipioOrigen->nombre }}
                            → {{ $viaje->ruta->municipioDestino->nombre }}
                            — {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}
                            {{ substr($viaje->hora_salida, 0, 5) }}
                            — ${{ number_format($viaje->precio_final, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('id_viaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 2. ASIENTOS --}}
        <div id="asientos-wrap">
            <p class="section-label">2. Seleccionar asientos</p>
            @error('id_asientos') <div class="invalid-feedback" style="margin-bottom:8px;">{{ $message }}</div> @enderror
            <div class="asientos-grid" id="asientos-grid"></div>
            <p id="sin-asientos" style="display:none; color:#9ca3af; font-size:0.875rem; margin-top:6px;">
                No hay asientos disponibles para este viaje.
            </p>
            <div id="resumen-total" class="resumen-total" style="display:none;">
                <span id="resumen-texto"></span>
            </div>
        </div>

        {{-- 3. PASAJEROS (generado dinámicamente) --}}
        <div id="pasajeros-section">
            <p class="section-label">3. Datos de cada pasajero</p>
            <div id="pasajeros-cards"></div>
        </div>

        {{-- 4. COMPRADOR --}}
        <div id="comprador-section" style="display:none;">
            <p class="section-label">4. Datos del comprador</p>
            <div class="form-stack">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_comprador">Nombre</label>
                        <input type="text" name="nombre_comprador" id="nombre_comprador"
                            value="{{ old('nombre_comprador') }}"
                            class="{{ $errors->has('nombre_comprador') ? 'is-invalid' : '' }}">
                        @error('nombre_comprador') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email_comprador">Correo electrónico</label>
                        <input type="email" name="email_comprador" id="email_comprador"
                            value="{{ old('email_comprador') }}"
                            class="{{ $errors->has('email_comprador') ? 'is-invalid' : '' }}">
                        @error('email_comprador') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <hr class="divider">
        <div>
            <button type="submit" class="btn-submit" id="btn-submit" disabled>Finalizar Venta</button>
            <a href="{{ route('viajes.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const viajesData       = JSON.parse(document.getElementById('viajes-json').textContent);
    const selectViaje      = document.getElementById('id_viaje');
    const asientosWrap     = document.getElementById('asientos-wrap');
    const asientosGrid     = document.getElementById('asientos-grid');
    const sinAsientos      = document.getElementById('sin-asientos');
    const resumenTotal     = document.getElementById('resumen-total');
    const resumenTexto     = document.getElementById('resumen-texto');
    const pasajerosSection = document.getElementById('pasajeros-section');
    const pasajerosCards   = document.getElementById('pasajeros-cards');
    const compradorSection = document.getElementById('comprador-section');
    const btnSubmit        = document.getElementById('btn-submit');

    // Lee los botones actualmente marcados en la grilla
    function getSeleccionados() {
        return Array.from(asientosGrid.querySelectorAll('.asiento-btn.seleccionado'));
    }

    function actualizarResumen() {
        const n     = getSeleccionados().length;
        const precio = parseFloat(selectViaje.selectedOptions[0]?.dataset.precio || 0);
        if (n === 0) {
            resumenTotal.style.display = 'none';
        } else {
            resumenTexto.textContent = n + ' asiento(s) — Total: $' + (precio * n).toLocaleString('es-CO');
            resumenTotal.style.display = 'inline-flex';
        }
    }

    // Agrega card si no existe, elimina card si el asiento fue desmarcado
    function sincronizarCards() {
        const seleccionados = getSeleccionados();

        // Eliminar cards de asientos ya no seleccionados
        Array.from(pasajerosCards.querySelectorAll('.pasajero-card')).forEach(card => {
            const idAv = card.dataset.idAv;
            const sigueSeleccionado = seleccionados.some(b => b.dataset.id === idAv);
            if (!sigueSeleccionado) card.remove();
        });

        // Agregar cards nuevas para asientos recién seleccionados
        seleccionados.forEach((btnEl, i) => {
            const idAv = btnEl.dataset.id;
            if (pasajerosCards.querySelector('[data-id-av="' + idAv + '"]')) return; // ya existe

            const numAsiento = btnEl.dataset.numero;
            const card = document.createElement('div');
            card.className = 'pasajero-card';
            card.dataset.idAv = idAv;
            card.innerHTML =
                '<div class="pasajero-card-title">' +
                    '<span class="badge">Asiento ' + numAsiento + '</span> Pasajero' +
                '</div>' +
                '<div class="form-row">' +
                    '<div class="form-group">' +
                        '<label>Nombre completo *</label>' +
                        '<input type="text" name="pasajeros[' + idAv + '][nombre_pasajero]" placeholder="Ej: Juan Pérez" required>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label>Documento de identidad *</label>' +
                        '<input type="text" name="pasajeros[' + idAv + '][documento_pasajero]" placeholder="Ej: 1075432100" required>' +
                    '</div>' +
                '</div>';
            pasajerosCards.appendChild(card);
        });

        const hay = seleccionados.length > 0;
        pasajerosSection.style.display = hay ? 'block' : 'none';
        compradorSection.style.display = hay ? 'block' : 'none';
        btnSubmit.disabled = !hay;
    }

    function toggleAsiento(btnEl) {
        btnEl.classList.toggle('seleccionado');
        btnEl.querySelector('input[type="checkbox"]').checked =
            btnEl.classList.contains('seleccionado');
        actualizarResumen();
        sincronizarCards();
    }

    function cargarAsientos(idViaje) {
        asientosGrid.innerHTML = '';
        pasajerosCards.innerHTML = '';
        sinAsientos.style.display   = 'none';
        resumenTotal.style.display  = 'none';
        pasajerosSection.style.display = 'none';
        compradorSection.style.display = 'none';
        btnSubmit.disabled = true;

        if (!idViaje || !viajesData[idViaje]) {
            asientosWrap.style.display = 'none';
            return;
        }

        asientosWrap.style.display = 'block';
        const asientos = viajesData[idViaje];

        if (asientos.length === 0) {
            sinAsientos.style.display = 'block';
            return;
        }

        asientos.forEach(a => {
            const btn = document.createElement('div');
            btn.className    = 'asiento-btn';
            btn.dataset.id   = a.id;
            btn.dataset.numero = a.numero;
            btn.innerHTML =
                '<input type="checkbox" name="id_asientos[]" value="' + a.id + '">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">' +
                    '<path d="M7 13c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V7H7v6zm2-4h6v4H9V9zM18 4h-2.5l-1-1h-5l-1 1H6v2h12V4z"/>' +
                '</svg>' +
                'Asiento ' + a.numero;
            btn.addEventListener('click', function () { toggleAsiento(btn); });
            asientosGrid.appendChild(btn);
        });
    }

    selectViaje.addEventListener('change', () => cargarAsientos(selectViaje.value));
    if (selectViaje.value) cargarAsientos(selectViaje.value);
</script>
@endpush
