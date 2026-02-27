<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para el historial de signos vitales.
 *
 * @property int $paciente_id
 * @property float|null $peso_kg
 * @property float|null $talla_cm
 * @property string|null $presion_arterial
 * @property float|null $temperatura
 * @property string|null $notas
 * @property string|null $fecha_registro
 */
class SignoVital extends Model
{
    use HasFactory;

    protected $table = 'historial_signos_vitales';
    public $timestamps = false; // Usamos fecha_registro

    protected $fillable = [
        'paciente_id',
        'peso_kg',
        'talla_cm',
        'presion_arterial',
        'temperatura',
        'notas',
        'fecha_registro',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}