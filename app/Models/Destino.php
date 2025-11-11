<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = 'destinos';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['nombre','pais','region','slug','descripcion','calificacion_prom','activo'];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'destino_id');
    }

    public function alojamientos()
    {
        return $this->hasMany(Alojamiento::class, 'destino_id');
    }

    public function paquetes()
    {
        return $this->hasMany(Paquete::class, 'destino_id');
    }
}
