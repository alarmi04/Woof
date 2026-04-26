<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'Usuario';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Correo',
        'Contrasena',
        'Telefono',
        'Direccion',
        'Tipo_vivienda',
        'Numero_hijos',
        'Nivel_actividad',
        'Experiencia_mascotas',
        'Tiempo_disponible',
    ];

    protected $hidden = [
        'Contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->Contrasena;
    }
}
