<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_departamento', 'id_departamento');
    }
}
