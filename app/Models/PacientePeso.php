<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para el historial de peso del paciente.
 *
 * @property int $id_paciente_peso
 * @property int $id_paciente
 * @property float $peso_kg
 * @property string $fecha_registro
 */
class PacientePeso extends Model
{
    use HasFactory;
    protected $table = 'paciente_peso';
    protected $primaryKey = 'id_paciente_peso';
    public $timestamps = false; // Usamos fecha_registro

    protected $fillable = [
        'id_paciente',
        'peso_kg',
        'fecha_registro',
    ];
}