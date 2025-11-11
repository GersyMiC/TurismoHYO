<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuponRedencion extends Model
{
    protected $table = 'cupon_redenciones';
    public $timestamps = false;

    protected $fillable = ['cupon_id','usuario_id','reserva_id','redimido_en','monto_aplicado'];

    public function cupon() { return $this->belongsTo(Cupon::class, 'cupon_id'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
    public function reserva() { return $this->belongsTo(Reserva::class, 'reserva_id'); }
}

