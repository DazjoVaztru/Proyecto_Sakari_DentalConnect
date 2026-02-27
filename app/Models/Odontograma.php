<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo mapeado a la tabla `odontograma` de la BD real.
 *
 * Columnas reales confirmadas desde el respaldo SQL:
 *  id_odontograma | id_paciente | id_cita | numero_diente | cara_diente |
 *  estado_diente  | observaciones | fecha_registro
 *
 * ⚠ La tabla NO tiene created_at / updated_at.
 */
class Odontograma extends Model
{
    use HasFactory;

    protected $table = 'odontograma';
    protected $primaryKey = 'id_odontograma';

    /** La tabla no tiene timestamps automáticos. */
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',    // FK → pacientes.id_paciente
        'id_cita',        // FK → citas.id_cita (nullable)
        'numero_diente',  // Ej: '11', '22' … '85' (varchar 5)
        'cara_diente',    // Ej: 'vestibular', 'mesial', 'oclusal', etc.
        'estado_diente',  // Ej: 'hallazgo', 'tratamiento', 'caries'
        'observaciones',  // Texto libre descriptivo
        'fecha_registro', // datetime — gestionado manualmente
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    // ────────────────────────────────────────────────────────────────────────
    // RELACIONES
    // ────────────────────────────────────────────────────────────────────────

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }
}