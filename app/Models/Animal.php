<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = "Animal";
    protected $primaryKey = "idAnimal";
    public $timestamps = false;

    protected $fillable = [
        "Nombre",
        "Raza",
        "Genero",
        "Edad",
        "Tamano",
        "Estado",
        "Descripcion",
        "Nivel_actividad",
        "Vivienda_recomendada",
        "Apto_ninos",
        "Experiencia_requerida",
        "Tiempo_requerido",
        "Imagen",
    ];
}
