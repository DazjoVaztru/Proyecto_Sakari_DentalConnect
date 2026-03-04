<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para el registro de auditoría del sistema.
 *
 * Almacena acciones importantes realizadas por los usuarios para trazabilidad.
 *
 * @property int $id_log
 * @property int $id_usuario
 * @property string $accion
 * @property string|null $tabla_afectada
 * @property int|null $id_registro
 * @property array|null $detalles
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class AuditLog extends Model
{
    use HasFactory;
    protected $table = 'audit_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false; // Solo usa created_at

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'id_registro',
        'detalles', // JSON
    ];

    protected $casts = [
        'detalles' => 'array',
    ];
}