<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['codigo','tipo','valor','usos_maximos','usos_actuales','valido_desde','valido_hasta','activo'];
    protected $casts = ['valido_desde'=>'date','valido_hasta'=>'date'];

    public function redenciones() { return $this->hasMany(CuponRedencion::class, 'cupon_id'); }
}
