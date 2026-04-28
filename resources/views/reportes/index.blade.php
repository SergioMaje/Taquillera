@extends('layouts.app')
@section('title', 'Reportes Operativos')
@section('page-title', 'Reportes Operativos')

@push('styles')
<style>
    .reportes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 8px;
    }
    .reporte-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 28px;
        text-decoration: none;
        color: inherit;
        transition: box-shadow 0.2s, border-color 0.2s;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .reporte-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-color: #2563eb;
    }
    .reporte-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .reporte-icon svg { width: 24px; height: 24px; }
    .reporte-card h2 { font-size: 1rem; font-weight: 700; color: #111827; margin: 0; }
    .reporte-card p  { font-size: 0.85rem; color: #6b7280; margin: 0; line-height: 1.5; }
    .reporte-arrow   { margin-top: auto; font-size: 0.8rem; color: #2563eb; font-weight: 600; }
</style>
@endpush

@section('content')

<p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Selecciona el reporte que deseas generar.</p>

<p style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9ca3af; margin-bottom:12px;">Operativos</p>
<div class="reportes-grid" style="margin-bottom:32px;">

    <a href="{{ route('reportes.viajes-hoy') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#dbeafe;">
            <svg viewBox="0 0 24 24" fill="#2563eb">
                <path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/>
            </svg>
        </div>
        <h2>Viajes del Día</h2>
        <p>Consulta todos los viajes programados para una fecha con su estado de ocupación.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

    <a href="{{ route('reportes.ventas') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#dcfce7;">
            <svg viewBox="0 0 24 24" fill="#16a34a">
                <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c1.1 0 2 .9 2 2s-.9 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46A4 4 0 0 0 18 12a4 4 0 0 0 2 3.46V18H4v-2.54A4 4 0 0 0 6 12a4 4 0 0 0-2-3.46V6h16v2.54z"/>
            </svg>
        </div>
        <h2>Tiquetes Vendidos</h2>
        <p>Revisa las ventas realizadas en un rango de fechas con el total recaudado.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

    <div class="reporte-card" style="opacity:0.5; cursor:default;">
        <div class="reporte-icon" style="background:#f3f4f6;">
            <svg viewBox="0 0 24 24" fill="#9ca3af">
                <path d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9 1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z"/>
            </svg>
        </div>
        <h2>Manifiesto de Pasajeros</h2>
        <p>Accede desde el detalle de un viaje en la lista de Viajes del Día.</p>
        <span class="reporte-arrow" style="color:#9ca3af;">Desde viajes del día</span>
    </div>

</div>

<p style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9ca3af; margin-bottom:12px; margin-top:8px;">Financieros</p>
<div class="reportes-grid">

    <a href="{{ route('reportes.ingresos-ruta') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#fef9c3;">
            <svg viewBox="0 0 24 24" fill="#ca8a04">
                <path d="M17 8C8 10 5.9 16.17 3.82 21L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2-8 2s0-1 3-3zm-2.71 8.15-1.29-1.29A3.93 3.93 0 0 1 15 12a4 4 0 0 1-8 0 4 4 0 0 1 4-4 3.93 3.93 0 0 1 2.86 1.29L12.57 10.6A2 2 0 0 0 11 10a2 2 0 0 0 0 4 2 2 0 0 0 1.57-.69z"/>
            </svg>
        </div>
        <h2>Ingresos por Ruta</h2>
        <p>Compara los ingresos generados por cada ruta y su participación en el total.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

    <a href="{{ route('reportes.ingresos-periodo') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#f3e8ff;">
            <svg viewBox="0 0 24 24" fill="#9333ea">
                <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/>
            </svg>
        </div>
        <h2>Ingresos por Período</h2>
        <p>Analiza los ingresos diarios en un rango de fechas con gráfico de barras.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

    <a href="{{ route('reportes.viajes-cancelados') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="#dc2626">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>
        <h2>Viajes Cancelados</h2>
        <p>Revisa los viajes cancelados y el impacto en tiquetes e ingresos afectados.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

    <a href="{{ route('reportes.utilidad-viaje') }}" class="reporte-card">
        <div class="reporte-icon" style="background:#dcfce7;">
            <svg viewBox="0 0 24 24" fill="#16a34a">
                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
            </svg>
        </div>
        <h2>Utilidad por Viaje</h2>
        <p>Compara ingresos y costos por viaje para conocer la utilidad real de cada operación.</p>
        <span class="reporte-arrow">Ver reporte →</span>
    </a>

</div>

@endsection
