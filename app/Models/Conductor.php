<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductores';
    protected $primaryKey = 'id_conductor';
    public $timestamps = false;

    protected $fillable = ['nombre', 'cedula', 'licencia', 'telefono'];

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_conductor', 'id_conductor');
    }
}
