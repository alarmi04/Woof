<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * Modelo que representa a un Usuario en el sistema.
 * Implementa la interfaz de autenticación de Laravel.
 */
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'Usuario';

    /**
     * Clave primaria de la tabla.
     */
    protected $primaryKey = 'idUsuario';

    /**
     * Desactivamos los timestamps automáticos.
     */
    public $timestamps = false;

    /**
     * Atributos asignables de forma masiva.
     */
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
        'Foto',
        'is_admin',
    ];

    /**
     * Atributos que deben ocultarse en las serializaciones (como JSON).
     */
    protected $hidden = [
        'Contrasena',
    ];

    /**
     * Indica a Laravel qué columna usar como identificador de autenticación.
     */
    public function getAuthIdentifierName()
    {
        return 'idUsuario';
    }

    /**
     * Indica a Laravel qué columna usar para la contraseña en la autenticación.
     */
    public function getAuthPassword()
    {
        return $this->Contrasena;
    }

    /**
     * Mutador (Setter) para la columna Contrasena.
     * Asegura que cualquier valor asignado sea hasheado automáticamente con Bcrypt.
     */
    public function setContrasenaAttribute($valor)
    {
        if (!empty($valor)) {
            $this->attributes['Contrasena'] = Hash::needsRehash($valor) ? Hash::make($valor) : $valor;
        }
    }

    /**
     * Relación con los animales registrados por el usuario.
     */
    public function animales()
    {
        return $this->hasMany(Animal::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Obtiene el correo electrónico para el restablecimiento de contraseña.
     */
    public function getEmailForPasswordReset()
    {
        return $this->Correo;
    }

    /**
     * Enruta las notificaciones por correo al campo correcto.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->Correo;
    }
}
