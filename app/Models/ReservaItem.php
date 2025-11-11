<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaItem extends Model
{
    protected $table = 'reserva_items';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['reserva_id','paquete_id','personalizacion_snapshot_json','cantidad','precio_unitario','precio_total'];

    protected $casts = ['personalizacion_snapshot_json' => 'array'];

    public function reserva() { return $this->belongsTo(Reserva::class, 'reserva_id'); }
    public function paquete() { return $this->belongsTo(Paquete::class, 'paquete_id'); }
}
