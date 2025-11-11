<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['destino_id','nombre','tipo','duracion_horas','precio_base','descripcion','activo'];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function paquetes()
    {
        return $this->belongsToMany(Paquete::class, 'paquete_actividades', 'actividad_id', 'paquete_id')
                    ->withPivot('cantidad');
    }
}
