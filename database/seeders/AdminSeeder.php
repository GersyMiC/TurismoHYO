<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Rol;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crea/actualiza usuario admin base
        $admin = Usuario::firstOrCreate(
            ['email' => 'admin@turismohyo.pe'],
            [
                'nombre_completo'   => 'Administrador Turismo HYO',
                'contrasena_hash'   => Hash::make('Cambiar123!'),
                'telefono'          => '968214225',
                'estado'            => 'activo',
                'creado_en'         => now(),
                'actualizado_en'    => now(),
            ]
        );

        // Asigna rol admin sin duplicar
        $rolAdmin = Rol::where('nombre', 'admin')->first();
        if ($rolAdmin) {
            $admin->roles()->syncWithoutDetaching([$rolAdmin->id]);
        }
    }
}
