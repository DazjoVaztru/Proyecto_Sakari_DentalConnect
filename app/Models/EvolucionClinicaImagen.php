<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para las imágenes anexas a la evolución del tratamiento.
 */
class EvolucionClinicaImagen extends Model
{
    use HasFactory;

    protected $table = 'evolucion_clinica_imagenes';
    protected $primaryKey = 'id_imagen';

    protected $fillable = [
        'id_evolucion',
        'ruta_imagen',
    ];

    /**
     * Relación con la Evolución de Tratamiento a la que pertenece esta imagen.
     */
    public function evolucionTratamiento()
    {
        return $this->belongsTo(EvolucionTratamiento::class, 'id_evolucion', 'id_evolucion');
    }
}
