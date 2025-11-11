<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    protected $table = 'transportes';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['desde_destino_id','hasta_destino_id','proveedor','tipo','precio_base','duracion_horas','detalles','activo'];

    public function destinoDesde()
    {
        return $this->belongsTo(Destino::class, 'desde_destino_id');
    }

    public function destinoHasta()
    {
        return $this->belongsTo(Destino::class, 'hasta_destino_id');
    }

    public function paquetes()
    {
        return $this->belongsToMany(Paquete::class, 'paquete_transportes', 'transporte_id', 'paquete_id');
    }
}
