<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoItem extends Model
{
    protected $table = 'carrito_items';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['carrito_id','paquete_id','personalizacion_id','cantidad','precio_unitario','precio_total'];

    public function carrito() { return $this->belongsTo(Carrito::class, 'carrito_id'); }
    public function paquete() { return $this->belongsTo(Paquete::class, 'paquete_id'); }
    public function personalizacion() { return $this->belongsTo(Personalizacion::class, 'personalizacion_id'); }
}
