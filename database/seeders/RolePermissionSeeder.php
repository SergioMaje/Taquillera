<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::findByName('admin');
        $vendedor = Role::findByName('vendedor');

        $admin->givePermissionTo(Permission::all());

        $vendedor->givePermissionTo([
            'taquilla.ver',
            'taquilla.venta',
            'taquilla.recibo',
            'viajes.ver',
            'reportes.ver',
        ]);
    }
}
