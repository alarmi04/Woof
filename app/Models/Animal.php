<?php
/**
 * ==============================================================================
 * FICHERO: app/Models/Animal.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-04-25
 * FUNCIÓN: Modelo Eloquent que representa la entidad Animal (perro en adopción)
 *          y gestiona sus relaciones y accesores en la base de datos.
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa a un Animal (perro) en el sistema de Woof!.
 */
class Animal extends Model
{
    /**
     * La tabla asociada al modelo en la base de datos.
     */
    protected $table = 'Animal';

    /**
     * El nombre de la columna que actúa como clave primaria.
     */
    protected $primaryKey = 'idAnimal';

    /**
     * Desactivamos los timestamps (created_at, updated_at) ya que la tabla no los tiene.
     */
    public $timestamps = false;

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'Nombre',
        'Raza',
        'Genero',
        'Edad',
        'Tamano',
        'Estado',
        'Descripcion',
        'Nivel_actividad',
        'Vivienda_recomendada',
        'Apto_ninos',
        'Experiencia_requerida',
        'Tiempo_requerido',
        'Imagen',
        'idUsuario',
    ];

    /**
     * ACCESOR: getImagenUrlAttribute
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Devuelve la ruta URL completa para acceder a la foto del animal.
     */
    public function getImagenUrlAttribute()
    {
        if ($this->Imagen) {
            return '/storage/' . $this->Imagen;
        }
        return null;
    }

    /**
     * RELACIÓN: usuario
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Define la relación de pertenencia N:1 del perro con su usuario adoptante.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
