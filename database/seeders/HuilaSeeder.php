<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HuilaSeeder extends Seeder
{
    public function run(): void
    {
        $huila = DB::table('departamentos')->insertGetId(['nombre' => 'Huila']);

        $municipios = [
            'Acevedo', 'Agrado', 'Aipe', 'Algeciras', 'Altamira',
            'Baraya', 'Campoalegre', 'Colombia', 'Elías', 'Garzón',
            'Gigante', 'Guadalupe', 'Hobo', 'Iquira', 'Isnos',
            'La Argentina', 'La Plata', 'Nátaga', 'Neiva', 'Oporapa',
            'Paicol', 'Palermo', 'Palestina', 'Pital', 'Pitalito',
            'Rivera', 'Saladoblanco', 'San Agustín', 'Santa María',
            'Suaza', 'Tarqui', 'Tello', 'Teruel', 'Tesalia',
            'Timaná', 'Villavieja', 'Yaguará',
        ];

        $rows = array_map(fn($nombre) => [
            'nombre'          => $nombre,
            'id_departamento' => $huila,
        ], $municipios);

        DB::table('municipios')->insert($rows);
    }
}
