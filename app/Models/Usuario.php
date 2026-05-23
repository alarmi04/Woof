<?php
/**
 * ==============================================================================
 * FICHERO: app/Models/Usuario.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-04-25
 * FUNCIÓN: Modelo de autenticación que representa a la entidad Usuario,
 *          gestionando los campos de perfil del adoptante y roles de acceso.
 * ==============================================================================
 */

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
     * MUTADOR: setContrasenaAttribute
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Setter para la columna Contrasena.
     *          Asegura que el valor sea hasheado automáticamente con Bcrypt.
     */
    public function setContrasenaAttribute($valor)
    {
        if (!empty($valor)) {
            $this->attributes['Contrasena'] = Hash::needsRehash($valor) ? Hash::make($valor) : $valor;
        }
    }

    /**
     * RELACIÓN: animales
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Define la relación 1:N con los animales adoptados por el usuario.
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
