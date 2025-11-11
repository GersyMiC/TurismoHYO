<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['reserva_id','pasarela','moneda','monto','estado','transaccion_ref','payload_json'];
    protected $casts = ['payload_json'=>'array'];

    public function reserva() { return $this->belongsTo(Reserva::class, 'reserva_id'); }
}
