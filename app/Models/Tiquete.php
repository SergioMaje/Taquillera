<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiquete extends Model
{
    protected $table = 'tiquetes';
    protected $primaryKey = 'id_tiquete';
    public $timestamps = false;

    protected $fillable = [
        'id_orden',
        'id_asiento_viaje',
        'nombre_pasajero',
        'documento_pasajero',
        'fecha_compra',
        'estado',
        'precio_base',
        'precio_final',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }

    public function asientoViaje()
    {
        return $this->belongsTo(AsientoViaje::class, 'id_asiento_viaje', 'id_asiento_viaje');
    }

}
