<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre_completo','email','contrasena_hash','telefono','estado','email_verificado_en','recordar_token'
    ];

    protected $hidden = ['contrasena_hash','recordar_token'];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'usuario_id', 'rol_id');
    }

    public function perfil()
    {
        return $this->hasOne(PerfilCliente::class, 'usuario_id');
    }

    public function personalizaciones()
    {
        return $this->hasMany(Personalizacion::class, 'usuario_id');
    }

    public function carritos()
    {
        return $this->hasMany(Carrito::class, 'usuario_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'usuario_id');
    }

    public function kpiEventos()
    {
        return $this->hasMany(KpiEvento::class, 'usuario_id');
    }
}
