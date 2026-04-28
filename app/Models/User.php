<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'cedula',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'id_usuario', 'id_usuario');
    }

    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'usuario_id', 'id_usuario');
    }
}
