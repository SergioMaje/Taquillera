<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    public $timestamps = false;

    protected $fillable = ['tabla_afectada', 'id_registro', 'usuario_id', 'accion', 'detalle', 'fecha'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id_usuario');
    }
}
