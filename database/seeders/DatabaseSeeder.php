<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tipo de bus
        $tipoBusId = DB::table('tipos_bus')->insertGetId([
            'nombre'      => 'Ejecutivo',
            'descripcion' => 'Bus ejecutivo con aire acondicionado',
            'tiene_bano'  => true,
            'tiene_tv'    => true,
            'doble_piso'  => false,
        ]);

        // 2. Bus con capacidad 10 (para pruebas)
        $busId = DB::table('buses')->insertGetId([
            'placa'       => 'HZN-123',
            'id_tipo_bus' => $tipoBusId,
            'capacidad'   => 10,
        ]);

        // 3. Tipo de asiento
        $tipoAsientoId = DB::table('tipos_asiento')->insertGetId([
            'codigo'      => 'STD',
            'color'       => '#28a745',
            'icono'       => null,
            'descripcion' => 'Asiento estándar',
        ]);

        // 4. Asientos del bus (10 asientos)
        $asientosIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $asientosIds[] = DB::table('asientos')->insertGetId([
                'id_bus'          => $busId,
                'numero'          => $i,
                'pos_x'           => (($i - 1) % 4) + 1,
                'pos_y'           => intdiv($i - 1, 4) + 1,
                'piso'            => 1,
                'id_tipo_asiento' => $tipoAsientoId,
            ]);
        }

        // 5. Departamento: solo Huila (programa regional)
        $depHuilaId = DB::table('departamentos')->insertGetId(['nombre' => 'Huila']);

        // 6. Municipios del Huila (37 municipios)
        $municipiosHuila = [
            'Acevedo', 'Agrado', 'Aipe', 'Algeciras', 'Altamira',
            'Baraya', 'Campoalegre', 'Colombia', 'Elías', 'Garzón',
            'Gigante', 'Guadalupe', 'Hobo', 'Iquira', 'Isnos',
            'La Argentina', 'La Plata', 'Nátaga', 'Neiva', 'Oporapa',
            'Paicol', 'Palermo', 'Palestina', 'Pital', 'Pitalito',
            'Rivera', 'Saladoblanco', 'San Agustín', 'Santa María',
            'Suaza', 'Tarqui', 'Tello', 'Teruel', 'Tesalia',
            'Timaná', 'Villavieja', 'Yaguará',
        ];

        $munIds = [];
        foreach ($municipiosHuila as $nombre) {
            $munIds[$nombre] = DB::table('municipios')->insertGetId([
                'nombre'           => $nombre,
                'id_departamento'  => $depHuilaId,
            ]);
        }

        // 7. Ruta de prueba: Neiva → Pitalito
        $rutaId = DB::table('rutas')->insertGetId([
            'id_departamento_origen'  => $depHuilaId,
            'id_municipio_origen'     => $munIds['Neiva'],
            'id_departamento_destino' => $depHuilaId,
            'id_municipio_destino'    => $munIds['Pitalito'],
            'duracion_estimada'       => 150,
        ]);

        // 8. Viaje de prueba
        $viajeId = DB::table('viajes')->insertGetId([
            'id_bus'          => $busId,
            'id_ruta'         => $rutaId,
            'fecha_salida'    => '2026-03-20',
            'hora_salida'     => '08:00:00',
            'precio_base'     => 35000.00,
            'precio_final'    => 38000.00,
            'estado'          => 'programado',
            'asientos_libres' => 10,
        ]);

        // 9. Asientos del viaje (todos disponibles)
        foreach ($asientosIds as $asientoId) {
            DB::table('asientos_viaje')->insert([
                'id_viaje'   => $viajeId,
                'id_asiento' => $asientoId,
                'estado'     => 'disponible',
            ]);
        }

        // 10. Usuario de prueba
        DB::table('usuarios')->insert([
            'nombre'   => 'Taquillero',
            'email'    => 'taquilla@coomotor.com',
            'password' => Hash::make('taquilla123'),
        ]);
    }
}
