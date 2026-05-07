<?php

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
     * Relación con el usuario (dueño o protector).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
