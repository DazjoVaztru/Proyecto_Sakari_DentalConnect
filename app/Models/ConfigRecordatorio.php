<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la configuración de recordatorios automáticos.
 *
 * Define las reglas para el envío de recordatorios de citas (tiempo de anticipación, mensaje, etc.).
 *
 * @property int $id_regla
 * @property int $id_clinica
 * @property int $tiempo_anticipacion
 * @property string $unidad_tiempo
 * @property string|null $plantilla_mensaje
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ConfigRecordatorio extends Model
{
    use HasFactory;
    protected $table = 'config_recordatorios';
    protected $primaryKey = 'id_regla';

    protected $fillable = [
        'id_clinica',
        'tiempo_anticipacion',
        'unidad_tiempo',
        'plantilla_mensaje',
    ];
}