<?php
/**
 * ==============================================================================
 * FICHERO: app/Models/Donacion.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-05-07
 * FUNCIÓN: Modelo Eloquent que mapea la entidad Donacion en base de datos,
 *          gestionando relaciones con el donante (Usuario) y material donado.
 * ==============================================================================
 */

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
     * RELACIÓN: material
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Define la relación de pertenencia N:1 de la donación con el Material.
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'idMaterial', 'idMaterial');
    }

    /**
     * RELACIÓN: usuario
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Define la relación N:1 opcional con el Usuario que realiza la donación.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
