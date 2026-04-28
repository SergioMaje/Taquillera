<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    protected $table = 'asientos';
    protected $primaryKey = 'id_asiento';
    public $timestamps = false;

    protected $fillable = ['id_bus', 'numero', 'pos_x', 'pos_y', 'piso', 'id_tipo_asiento'];

    protected $casts = [
        'pos_x' => 'integer',
        'pos_y' => 'integer',
        'piso'  => 'integer',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus', 'id_bus');
    }

    public function tipoAsiento()
    {
        return $this->belongsTo(TipoAsiento::class, 'id_tipo_asiento', 'id_tipo_asiento');
    }

    public function asientosViaje()
    {
        return $this->hasMany(AsientoViaje::class, 'id_asiento', 'id_asiento');
    }
}
