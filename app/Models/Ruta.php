<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'rutas';
    protected $primaryKey = 'id_ruta';
    public $timestamps = false;

    protected $fillable = [
        'id_departamento_origen',
        'id_municipio_origen',
        'id_departamento_destino',
        'id_municipio_destino',
        'duracion_estimada',
    ];

    public function departamentoOrigen()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento_origen', 'id_departamento');
    }

    public function municipioOrigen()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio_origen', 'id_municipio');
    }

    public function departamentoDestino()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento_destino', 'id_departamento');
    }

    public function municipioDestino()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio_destino', 'id_municipio');
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_ruta', 'id_ruta');
    }
}
