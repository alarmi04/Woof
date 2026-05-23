<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'Material';

    /**
     * Clave primaria.
     */
    protected $primaryKey = 'idMaterial';

    /**
     * Desactivamos timestamps si no los tiene la tabla.
     */
    public $timestamps = false;
}
