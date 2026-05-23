<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una donación realizada por un usuario o de forma anónima.
 */
class Donacion extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'Donacion';

    /**
     * Clave primaria.
     */
    protected $primaryKey = 'idDonacion';

    /**
     * Desactivamos timestamps estándar (usamos nuestra propia columna Fecha).
     */
    public $timestamps = false;

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'idUsuario',
        'idMaterial',
        'Cantidad',
        'Observaciones',
        'Tipo_donacion',
        'Fecha',
    ];

    /**
     * Relación con el material donado.
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'idMaterial', 'idMaterial');
    }

    /**
     * Relación con el usuario que realizó la donación.
     * Puede ser nula si la donación es anónima.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
