<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiEvento extends Model
{
    protected $table = 'kpi_eventos';
    public $timestamps = false;

    protected $fillable = ['usuario_id','tipo_evento','metadatos_json','ocurrido_en'];
    protected $casts = ['metadatos_json'=>'array','ocurrido_en'=>'datetime'];

    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
}
