<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilCliente extends Model
{
    protected $table = 'perfiles_cliente';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['usuario_id','tipo_documento','numero_documento','fecha_nacimiento','preferencias_json'];

    protected $casts = ['preferencias_json' => 'array','fecha_nacimiento'=>'date'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
