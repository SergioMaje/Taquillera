<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAsiento extends Model
{
    protected $table = 'tipos_asiento';
    protected $primaryKey = 'id_tipo_asiento';
    public $timestamps = false;

    protected $fillable = ['codigo', 'color', 'icono', 'descripcion'];

    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'id_tipo_asiento', 'id_tipo_asiento');
    }
}
