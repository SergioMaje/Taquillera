<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'taquilla.ver']);
        Permission::firstOrCreate(['name' => 'taquilla.venta']);
        Permission::firstOrCreate(['name' => 'taquilla.recibo']);
        Permission::firstOrCreate(['name' => 'taquilla.confirmar']);

        Permission::firstOrCreate(['name' => 'viajes.ver']);
        Permission::firstOrCreate(['name' => 'viajes.crear']);
        Permission::firstOrCreate(['name' => 'viajes.editar']);
        Permission::firstOrCreate(['name' => 'viajes.eliminar']);
        Permission::firstOrCreate(['name' => 'viajes.cancelar']);
        Permission::firstOrCreate(['name' => 'viajes.renovar']);
        Permission::firstOrCreate(['name' => 'viajes.completar']);
        Permission::firstOrCreate(['name' => 'viajes.ver_cancelados']);
        Permission::firstOrCreate(['name' => 'viajes.costos.crear']);
        Permission::firstOrCreate(['name' => 'viajes.costos.eliminar']);

        Permission::firstOrCreate(['name' => 'tiquetes.ver']);
        Permission::firstOrCreate(['name' => 'tiquetes.crear']);
        Permission::firstOrCreate(['name' => 'tiquetes.editar']);
        Permission::firstOrCreate(['name' => 'tiquetes.eliminar']);

        Permission::firstOrCreate(['name' => 'buses.ver']);
        Permission::firstOrCreate(['name' => 'buses.crear']);
        Permission::firstOrCreate(['name' => 'buses.editar']);
        Permission::firstOrCreate(['name' => 'buses.eliminar']);
        Permission::firstOrCreate(['name' => 'buses.reactivar']);

        Permission::firstOrCreate(['name' => 'tipos_bus.ver']);
        Permission::firstOrCreate(['name' => 'tipos_bus.crear']);
        Permission::firstOrCreate(['name' => 'tipos_bus.editar']);
        Permission::firstOrCreate(['name' => 'tipos_bus.eliminar']);

        Permission::firstOrCreate(['name' => 'rutas.ver']);
        Permission::firstOrCreate(['name' => 'rutas.crear']);
        Permission::firstOrCreate(['name' => 'rutas.editar']);
        Permission::firstOrCreate(['name' => 'rutas.eliminar']);

        Permission::firstOrCreate(['name' => 'propietarios.ver']);
        Permission::firstOrCreate(['name' => 'propietarios.crear']);
        Permission::firstOrCreate(['name' => 'propietarios.editar']);
        Permission::firstOrCreate(['name' => 'propietarios.eliminar']);

        Permission::firstOrCreate(['name' => 'conductores.ver']);
        Permission::firstOrCreate(['name' => 'conductores.crear']);
        Permission::firstOrCreate(['name' => 'conductores.editar']);
        Permission::firstOrCreate(['name' => 'conductores.eliminar']);

        Permission::firstOrCreate(['name' => 'reportes.ver']);
    }
}
