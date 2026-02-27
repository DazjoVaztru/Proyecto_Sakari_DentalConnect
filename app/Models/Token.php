<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los tokens de acceso o verificación.
 *
 * @property int $id_token
 * @property int $id_usuario
 * @property string $token
 * @property string $tipo_token
 * @property string $estado
 * @property string $fecha_creacion
 * @property string $fecha_expiracion
 */
class Token extends Model
{
    use HasFactory;

    protected $table = 'tokens';
    protected $primaryKey = 'id_token'; // O el nombre de tu llave primaria si es diferente

    // ESTA ES LA LÍNEA MÁGICA QUE SOLUCIONA EL ERROR
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'token',
        'tipo_token',
        'estado',
        'fecha_creacion',
        'fecha_expiracion'
    ];
}