<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $table = 'paquetes';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['destino_id','nombre','slug','resumen','descripcion','dias','noches','precio_desde','destacado','activo'];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'paquete_actividades', 'paquete_id', 'actividad_id')
                    ->withPivot('cantidad');
    }

    public function alojamientos()
    {
        return $this->belongsToMany(Alojamiento::class, 'paquete_alojamientos', 'paquete_id', 'alojamiento_id')
                    ->withPivot('noches_incluidas');
    }

    public function transportes()
    {
        return $this->belongsToMany(Transporte::class, 'paquete_transportes', 'paquete_id', 'transporte_id');
    }
}
