<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = ['id_orden', 'metodo_pago', 'monto', 'estado', 'fecha_pago'];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }
}
