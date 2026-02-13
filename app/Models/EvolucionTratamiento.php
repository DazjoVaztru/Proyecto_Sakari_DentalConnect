<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolucionTratamiento extends Model
{
    use HasFactory;
    protected $table = 'evolucion_tratamiento';
    protected $primaryKey = 'id_evolucion';

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
}