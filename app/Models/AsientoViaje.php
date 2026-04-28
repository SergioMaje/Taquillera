<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientoViaje extends Model
{
    protected $table = 'asientos_viaje';
    protected $primaryKey = 'id_asiento_viaje';
    public $timestamps = false;

    protected $fillable = ['id_viaje', 'id_asiento', 'estado'];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class, 'id_asiento', 'id_asiento');
    }

    public function tiquetes()
    {
        return $this->hasMany(Tiquete::class, 'id_asiento_viaje', 'id_asiento_viaje');
    }
}
