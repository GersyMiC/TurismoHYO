<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destino;
use App\Models\Actividad;

class ActividadesSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'laguna-de-paca' => [
                ['nombre'=>'Paseo en bote', 'tipo'=>'nautica', 'duracion_horas'=>1.0, 'precio_base'=>25.00, 'descripcion'=>'Recorrido guiado por la laguna.'],
                ['nombre'=>'Mirador de Paca', 'tipo'=>'trek', 'duracion_horas'=>1.5, 'precio_base'=>0.00, 'descripcion'=>'Caminata corta hasta el mirador panorámico.'],
                ['nombre'=>'Degustación de trucha', 'tipo'=>'gastronomia', 'duracion_horas'=>1.0, 'precio_base'=>0.00, 'descripcion'=>'Restaurantes locales a orillas de la laguna.'],
            ],
            'canon-de-shucto' => [
                ['nombre'=>'Sendero al mirador', 'tipo'=>'trek', 'duracion_horas'=>2.0, 'precio_base'=>0.00, 'descripcion'=>'Trekking ligero con vistas al cañón.'],
                ['nombre'=>'Tour fotográfico', 'tipo'=>'fotografia', 'duracion_horas'=>2.5, 'precio_base'=>20.00, 'descripcion'=>'Puntos clave para fotos del cañón.'],
            ],
            'huancaya' => [
                ['nombre'=>'Circuito de cascadas', 'tipo'=>'trek', 'duracion_horas'=>3.0, 'precio_base'=>0.00, 'descripcion'=>'Caminata por pozas turquesa y puentes.'],
                ['nombre'=>'Paseo en bote', 'tipo'=>'nautica', 'duracion_horas'=>1.0, 'precio_base'=>30.00, 'descripcion'=>'Navegación en laguna con chaleco salvavidas.'],
            ],
            'tarma' => [
                ['nombre'=>'City tour Tarma', 'tipo'=>'cultural', 'duracion_horas'=>2.0, 'precio_base'=>20.00, 'descripcion'=>'Plaza, iglesias y miradores.'],
                ['nombre'=>'Santuario de Muruhuay', 'tipo'=>'cultural', 'duracion_horas'=>2.5, 'precio_base'=>25.00, 'descripcion'=>'Visita al santuario y feria local.'],
                ['nombre'=>'Cueva de Huagapo', 'tipo'=>'aventura', 'duracion_horas'=>2.0, 'precio_base'=>25.00, 'descripcion'=>'Ingreso a la gruta con guía local.'],
            ],
            'selva-central' => [
                ['nombre'=>'Cataratas (Bayoz / Velo de la Novia)', 'tipo'=>'trek', 'duracion_horas'=>4.0, 'precio_base'=>30.00, 'descripcion'=>'Caminata y baños en pozas.'],
                ['nombre'=>'Cafetales y cata de café', 'tipo'=>'gastronomia', 'duracion_horas'=>2.0, 'precio_base'=>20.00, 'descripcion'=>'Proceso del café y degustación.'],
                ['nombre'=>'Rafting en río Perené', 'tipo'=>'aventura', 'duracion_horas'=>2.0, 'precio_base'=>90.00, 'descripcion'=>'Actividad con equipo y guía certificado.'],
            ],
            'huayllay' => [
                ['nombre'=>'Bosque de piedras – circuito', 'tipo'=>'trek', 'duracion_horas'=>3.0, 'precio_base'=>20.00, 'descripcion'=>'Recorrido por formaciones rocosas.'],
                ['nombre'=>'Termales (La Calera)', 'tipo'=>'relax', 'duracion_horas'=>1.5, 'precio_base'=>15.00, 'descripcion'=>'Piscinas termales cercanas.'],
            ],
        ];

        foreach ($map as $slug => $actividades) {
            $destino = Destino::where('slug', $slug)->first();
            if (!$destino) continue;

            foreach ($actividades as $a) {
                Actividad::updateOrCreate(
                    ['destino_id' => $destino->id, 'nombre' => $a['nombre']],
                    array_merge($a, ['activo' => true, 'creado_en'=>now(), 'actualizado_en'=>now()])
                );
            }
        }
    }
}