<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoBus extends Model
{
    protected $table = 'tipos_bus';
    protected $primaryKey = 'id_tipo_bus';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'tiene_bano', 'tiene_tv', 'doble_piso',
        'columnas_izquierda', 'columnas_derecha', 'capacidad_default',
    ];

    protected $casts = [
        'tiene_bano'         => 'boolean',
        'tiene_tv'           => 'boolean',
        'doble_piso'         => 'boolean',
        'columnas_izquierda' => 'integer',
        'columnas_derecha'   => 'integer',
        'capacidad_default'  => 'integer',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class, 'id_tipo_bus', 'id_tipo_bus');
    }

}
