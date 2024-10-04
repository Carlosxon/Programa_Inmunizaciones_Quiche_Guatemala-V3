<?php

namespace Database\Seeders;

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

    {
        // Crear permisos
        Permission::create(['name' => 'viewWarehousePanel']);
        Permission::create(['name' => 'viewSpecificWarehouse']);

        // Crear rol y asignar permisos
        $role = Role::create(['name' => 'warehouse_manager']);
        $role->givePermissionTo('viewWarehousePanel');
        $role->givePermissionTo('viewSpecificWarehouse');
    }
        // Verificar y crear permisos
        $permissions = [
            // Permisos para ajustes de stock
            'ver ajustes de stock',
            'crear ajustes de stock',
            'actualizar ajustes de stock',
            'eliminar ajustes de stock',
            
            // Permisos para transferencias de stock
            'ver transferencias de stock',
            'crear transferencias de stock',
            'actualizar transferencias de stock',
            'eliminar transferencias de stock',
            
            // Permisos para inventarios
            'ver inventarios',
            'crear inventarios',
            'actualizar inventarios',
            'eliminar inventarios',
            
            // Permisos para bodegas
            'ver bodegas',
            'crear bodegas',
            'actualizar bodegas',
            'eliminar bodegas',
            
            // Permisos para la administración de sucursales
            'administrar todas las sucursales',
            'administrar propia sucursal',
            
            // Permisos para dashboards
            'ver dashboard administrador',
            'ver dashboard encargado de sucursal',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $managerRole = Role::firstOrCreate(['name' => 'Encargado de Sucursal']);

        // Asignar permisos a roles
        $adminRole->givePermissionTo([
            // Permisos para ajustes de stock
            'ver ajustes de stock',
            'crear ajustes de stock',
            'actualizar ajustes de stock',
            'eliminar ajustes de stock',
            
            // Permisos para transferencias de stock
            'ver transferencias de stock',
            'crear transferencias de stock',
            'actualizar transferencias de stock',
            'eliminar transferencias de stock',
            
            // Permisos para inventarios
            'ver inventarios',
            'crear inventarios',
            'actualizar inventarios',
            'eliminar inventarios',
            
            // Permisos para bodegas
            'ver bodegas',
            'crear bodegas',
            'actualizar bodegas',
            'eliminar bodegas',
            
            // Permisos para la administración de sucursales
            'administrar todas las sucursales',
            
            // Permisos para dashboards
            'ver dashboard administrador',
        ]);

        $managerRole->givePermissionTo([
            // Permisos para ajustes de stock
            'ver ajustes de stock',
            'crear ajustes de stock',
            
            // Permisos para transferencias de stock
            'ver transferencias de stock',
            'crear transferencias de stock',
            
            // Permisos para inventarios
            'ver inventarios',
            'crear inventarios',
            
            // Permisos para bodegas
            'ver bodegas',
            
            // Permisos para la administración de sucursales
            'administrar propia sucursal',
            
            // Permisos para dashboards
            'ver dashboard encargado de sucursal',
        ]);
    }
}

