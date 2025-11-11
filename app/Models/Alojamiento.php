<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alojamiento extends Model
{
    protected $table = 'alojamientos';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['destino_id','nombre','categoria','precio_noche','descripcion','activo'];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function paquetes()
    {
        return $this->belongsToMany(Paquete::class, 'paquete_alojamientos', 'alojamiento_id', 'paquete_id')
                    ->withPivot('noches_incluidas');
    }
}
