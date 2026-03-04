<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para el registro de la evolución de un tratamiento.
 *
 * Almacena las notas de evolución (SOAP) y el progreso del paciente en un tratamiento.
 *
 * @property int $id_evolucion
 * @property int $id_servicio
 * @property int $id_paciente
 * @property string $fecha_evolucion
 * @property string|null $descripcion_avance
 * @property string|null $subjetivo_soap
 * @property string|null $objetivo_soap
 * @property string|null $plan_tratamiento
 * @property string|null $estado_paciente
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class EvolucionTratamiento extends Model
{
    use HasFactory;
    protected $table = 'evolucion_tratamiento';
    protected $primaryKey = 'id_evolucion';

    /**
     * La tabla evolucion_tratamiento NO tiene created_at / updated_at
     * según el respaldo SQL confirmado.
     */
    public $timestamps = false;

    protected $fillable = [
        'id_servicio',
        'id_paciente',
        'fecha_evolucion',
        'descripcion_avance',
        'subjetivo_soap',
        'objetivo_soap',
        'plan_tratamiento',
        'estado_paciente',
    ];

    /**
     * Relación con las imágenes anexas a la evolución.
     */
    public function imagenes()
    {
        return $this->hasMany(EvolucionClinicaImagen::class, 'id_evolucion', 'id_evolucion');
    }
}