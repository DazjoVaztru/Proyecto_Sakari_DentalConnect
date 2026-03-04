<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para gestionar los horarios bloqueados de los doctores.
 *
 * Permite definir periodos en los que un doctor no está disponible.
 *
 * @property int $id_bloqueo
 * @property int $id_doctor
 * @property \Illuminate\Support\Carbon $fecha_inicio
 * @property \Illuminate\Support\Carbon $fecha_fin
 * @property string|null $motivo
 * @property string $estatus_horario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class HorarioBloqueado extends Model
{
    use HasFactory;
    protected $table = 'horarios_bloqueados';
    protected $primaryKey = 'id_bloqueo';

    protected $fillable = [
        'id_doctor',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estatus_horario',
    ];
}