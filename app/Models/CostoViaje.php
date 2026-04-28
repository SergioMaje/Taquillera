<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostoViaje extends Model
{
    protected $table = 'costos_viaje';
    protected $primaryKey = 'id_costo_viaje';
    public $timestamps = false;

    protected $fillable = ['id_viaje', 'concepto', 'descripcion', 'monto'];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }
}
