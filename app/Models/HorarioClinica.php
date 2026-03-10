<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Horario de atención por día de la semana para una clínica.
 *
 * Cada clínica tiene 7 registros (0=Domingo … 6=Sábado).
 * Los días con activo=false indican que la clínica está cerrada.
 */
class HorarioClinica extends Model
{
    use HasFactory;

    protected $table = 'horarios_clinica';
    protected $primaryKey = 'id_horario';

    protected $fillable = [
        'id_clinica',
        'dia_semana',
        'activo',
        'hora_inicio',
        'hora_fin',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Nombres de los días indexados como PHP/Carbon (0=Domingo).
     */
    public const DIAS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        0 => 'Domingo',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'id_clinica');
    }

    /**
     * Devuelve el nombre legible del día.
     */
    public function getNombreDiaAttribute(): string
    {
        return self::DIAS[$this->dia_semana] ?? '?';
    }
}
