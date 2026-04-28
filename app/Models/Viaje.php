<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'viajes';
    protected $primaryKey = 'id_viaje';
    public $timestamps = false;

    protected $fillable = [
        'id_bus',
        'id_conductor',
        'id_ruta',
        'fecha_salida',
        'hora_salida',
        'hora_llegada_real',
        'precio_base',
        'precio_final',
        'estado',
        'estado_real',
        'asientos_libres',
    ];

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus', 'id_bus');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta', 'id_ruta');
    }

    public function asientosViaje()
    {
        return $this->hasMany(AsientoViaje::class, 'id_viaje', 'id_viaje');
    }

    public function costos()
    {
        return $this->hasMany(CostoViaje::class, 'id_viaje', 'id_viaje');
    }
}
