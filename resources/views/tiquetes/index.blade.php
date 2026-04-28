@extends('layouts.app')

@section('title', 'Tiquetes Vendidos')

@push('styles')
<style>
    .page-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-header-row h1 { font-size: 1.75rem; font-weight: 700; color: #111827; }

    .btn-delete {
        background: none;
        border: none;
        color: #ef4444;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
    }
    .btn-delete:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <h1>Tiquetes Vendidos</h1>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Pasajero</th>
            <th>Viaje</th>
            <th>Asiento</th>
            <th>Precio Pagado</th>
            <th>Fecha Venta</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tiquetes as $tiquete)
        <tr>
            <td>{{ $tiquete->id_tiquete }}</td>
            <td>{{ $tiquete->nombre_pasajero ?? $tiquete->orden->usuario->nombre }}</td>
            <td>
                {{ $tiquete->asientoViaje->viaje->ruta->municipioOrigen->nombre }}
                →
                {{ $tiquete->asientoViaje->viaje->ruta->municipioDestino->nombre }}
            </td>
            <td>#{{ $tiquete->asientoViaje->asiento->numero }}</td>
            <td>${{ number_format($tiquete->precio_final, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($tiquete->fecha_compra)->format('d/m/Y') }}</td>
            <td>
                <form method="POST" action="{{ route('tiquetes.destroy', $tiquete->id_tiquete) }}"
                      onsubmit="return confirm('¿Eliminar este tiquete? El asiento quedará disponible nuevamente.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center; color:#888;">No hay tiquetes vendidos aún.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
