<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destino;
use App\Models\Paquete;
use Illuminate\Support\Str;

class PaquetesSeeder extends Seeder
{
    public function run(): void
    {
        $base = [
            'laguna-de-paca' => [
                ['nombre'=>'Full Day Laguna de Paca', 'resumen'=>'Paseo en bote + mirador + trucha.','dias'=>1,'noches'=>0,'precio_desde'=>119.00],
            ],
            'canon-de-shucto' => [
                ['nombre'=>'Cañón de Shucto Aventura', 'resumen'=>'Trekking y miradores panorámicos.','dias'=>1,'noches'=>0,'precio_desde'=>99.00],
            ],
            'huancaya' => [
                ['nombre'=>'Huancaya Turquesa 2D/1N', 'resumen'=>'Cascadas, pozas y paseo en bote.','dias'=>2,'noches'=>1,'precio_desde'=>329.00],
            ],
            'tarma' => [
                ['nombre'=>'Tarma Clásico 2D/1N', 'resumen'=>'City tour, Muruhuay y Huagapo.','dias'=>2,'noches'=>1,'precio_desde'=>279.00],
            ],
            'selva-central' => [
                ['nombre'=>'Selva Central 3D/2N', 'resumen'=>'Cataratas, cafetales y rafting opcional.','dias'=>3,'noches'=>2,'precio_desde'=>499.00],
            ],
            'huayllay' => [
                ['nombre'=>'Huayllay Bosque de Piedras', 'resumen'=>'Circuito rocoso + termales.','dias'=>1,'noches'=>0,'precio_desde'=>149.00],
            ],
        ];

        foreach ($base as $slug => $packs) {
            $destino = Destino::where('slug', $slug)->first();
            if (!$destino) continue;

            foreach ($packs as $p) {
                $slugP = Str::slug($p['nombre']);
                Paquete::updateOrCreate(
                    ['slug' => $slugP],
                    [
                        'destino_id'   => $destino->id,
                        'nombre'       => $p['nombre'],
                        'resumen'      => $p['resumen'],
                        'descripcion'  => $p['resumen'],
                        'dias'         => $p['dias'],
                        'noches'       => $p['noches'],
                        'precio_desde' => $p['precio_desde'],
                        'destacado'    => false,
                        'activo'       => true,
                        'creado_en'    => now(),
                        'actualizado_en' => now(),
                    ]
                );
            }
        }
    }
}
