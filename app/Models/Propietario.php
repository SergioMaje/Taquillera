<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    protected $table = 'propietarios';
    protected $primaryKey = 'id_propietario';
    public $timestamps = false;

    protected $fillable = ['tipo', 'nombre', 'cedula_nit', 'telefono', 'email'];

    public function buses()
    {
        return $this->hasMany(Bus::class, 'id_propietario', 'id_propietario');
    }
}
