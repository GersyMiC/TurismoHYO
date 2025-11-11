<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['usuario_id','estado'];

    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
    public function items() { return $this->hasMany(CarritoItem::class, 'carrito_id'); }
}
