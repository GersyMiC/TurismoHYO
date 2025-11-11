<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destino;

class DestinosSeeder extends Seeder
{
    public function run(): void
    {
        $destinos = [
            [
                'slug' => 'laguna-de-paca',
                'nombre' => 'Laguna de Paca',
                'pais' => 'Peru',
                'region' => 'Junin (Jauja)',
                'descripcion' => 'Laguna altoandina famosa por sus paseos en bote y miradores. Gastronomía local basada en trucha.',
                'calificacion_prom' => 4.7, 'activo' => true,
            ],
            [
                'slug' => 'canon-de-shucto',
                'nombre' => 'Cañón de Shucto',
                'pais' => 'Peru',
                'region' => 'Junin (Jauja)',
                'descripcion' => 'Formación geológica con senderos y miradores. Ideal para trekking ligero y fotografía paisajística.',
                'calificacion_prom' => 4.6, 'activo' => true,
            ],
            [
                'slug' => 'huancaya',
                'nombre' => 'Huancaya',
                'pais' => 'Peru',
                'region' => 'Lima/Junin (Nor Yauyos–Cochas)',
                'descripcion' => 'Cascadas turquesa y lagunas escalonadas. Destino top para trekking, bote y fotografía.',
                'calificacion_prom' => 4.9, 'activo' => true,
            ],
            [
                'slug' => 'tarma',
                'nombre' => 'Tarma',
                'pais' => 'Peru',
                'region' => 'Junin',
                'descripcion' => 'La “Perla de los Andes”: ciudad colonial, flores, Muruhuay y la cueva de Huagapo.',
                'calificacion_prom' => 4.6, 'activo' => true,
            ],
            [
                'slug' => 'selva-central',
                'nombre' => 'Selva Central',
                'pais' => 'Peru',
                'region' => 'Junin/Pasco (Chanchamayo, Satipo, Oxapampa)',
                'descripcion' => 'Cataratas, cafetales, rafting y naturaleza tropical. Cultura asháninka y gastronomía amazónica.',
                'calificacion_prom' => 4.8, 'activo' => true,
            ],
            [
                'slug' => 'huayllay',
                'nombre' => 'Huayllay',
                'pais' => 'Peru',
                'region' => 'Pasco',
                'descripcion' => 'Bosque de piedras de Huayllay: formaciones rocosas únicas y termales cercanas.',
                'calificacion_prom' => 4.7, 'activo' => true,
            ],
            // <<< FALTANTES A CONFIRMAR >>>
            // Dime el nombre exacto de los destinos tapados/ambiguos del afiche
            // y los agrego aquí sin improvisar.
        ];

        foreach ($destinos as $d) {
            Destino::updateOrCreate(['slug' => $d['slug']], array_merge($d, [
                'creado_en' => now(),
                'actualizado_en' => now(),
            ]));
        }
    }
}
