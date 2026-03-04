<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una clínica dental.
 *
 * Almacena la información institucional de la clínica, como nombre, RFC y dirección.
 *
 * @property int $id_clinica
 * @property string $nombre_comercial
 * @property string|null $rfc_clinica
 * @property string|null $numero_telefono
 * @property string|null $localidad
 * @property string|null $estado
 * @property string|null $codigo_postal
 * @property float|null $config_anticipo_pct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Clinica extends Model
{
    use HasFactory;

    protected $table = 'clinicas';
    protected $primaryKey = 'id_clinica';

    protected $fillable = [
        'nombre_comercial',
        'rfc_clinica',
        'numero_telefono',
        'localidad',
        'estado',
        'codigo_postal',
        'config_anticipo_pct'
    ];
}