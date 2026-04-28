<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $table = 'buses';
    protected $primaryKey = 'id_bus';
    public $timestamps = false;

    protected $fillable = ['placa', 'id_tipo_bus', 'id_propietario', 'capacidad', 'activo'];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario', 'id_propietario');
    }

    public function tipoBus()
    {
        return $this->belongsTo(TipoBus::class, 'id_tipo_bus', 'id_tipo_bus');
    }

    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'id_bus', 'id_bus');
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_bus', 'id_bus');
    }
}
