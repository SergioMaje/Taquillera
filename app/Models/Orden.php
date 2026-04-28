<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';
    protected $primaryKey = 'id_orden';
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'fecha_orden', 'estado', 'total'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function tiquetes()
    {
        return $this->hasMany(Tiquete::class, 'id_orden', 'id_orden');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_orden', 'id_orden');
    }
}
