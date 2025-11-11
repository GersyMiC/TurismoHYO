<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personalizacion extends Model
{
    protected $table = 'personalizaciones';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id','paquete_base_id','fecha_inicio','fecha_fin',
        'pax_adultos','pax_ninos','selecciones_json','desglose_precios_json','precio_total'
    ];

    protected $casts = [
        'selecciones_json' => 'array',
        'desglose_precios_json' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
    public function paqueteBase() { return $this->belongsTo(Paquete::class, 'paquete_base_id'); }
}
