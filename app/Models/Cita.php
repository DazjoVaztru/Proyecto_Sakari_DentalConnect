<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'id_clinica',
        'id_paciente',
        'id_doctor',
        'id_servicio',      // Si usas servicios del catálogo
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estado_cita',
        'costo_estimado',
        'motivo',
        'notas'
    ];

    // Relación: Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación: Doctor (Usuario)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor');
    }

    // ESTA ES LA QUE FALTABA: Relación con el Servicio/Tratamiento principal
    public function servicio()
    {
        // Conecta el campo 'id_servicio' de la cita con el modelo Servicio
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }

    // Relación: Detalles (Para tratamientos extra en la misma cita)
    public function detalles()
    {
        return $this->hasMany(CitaDetalle::class, 'id_cita');
    }

    // Relación: Pagos/Abonos
    public function ingresos()
    {
        return $this->hasMany(IngresoCaja::class, 'id_cita');
    }
}