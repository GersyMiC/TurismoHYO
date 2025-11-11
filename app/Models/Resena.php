<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['usuario_id','paquete_id','calificacion','comentario','moderado'];

    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id'); }
    public function paquete() { return $this->belongsTo(Paquete::class, 'paquete_id'); }
}
