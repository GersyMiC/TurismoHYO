<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->upsert([
            ['nombre' => 'admin',  'descripcion' => 'Administrador del sistema', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'agente', 'descripcion' => 'Agente de ventas/operaciones', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'cliente','descripcion' => 'Cliente final', 'creado_en' => now(), 'actualizado_en' => now()],
        ], ['nombre'], ['descripcion','actualizado_en']);
    }
}
