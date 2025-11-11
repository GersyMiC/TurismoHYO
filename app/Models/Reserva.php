<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id','codigo','estado','contacto_nombre','contacto_email','contacto_telefono',
        'pasajeros_json','subtotal','descuento','total','fecha_inicio','fecha_fin'
    ];

    protected $casts = ['pasajeros_json' => 'array','fecha_inicio'=>'date','fecha_fin'=>'date'];

    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
    public function items() { return $this->hasMany(ReservaItem::class, 'reserva_id'); }
    public function pagos() { return $this->hasMany(Pago::class, 'reserva_id'); }
}
