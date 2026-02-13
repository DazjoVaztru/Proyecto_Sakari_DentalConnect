<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    // 1. Configuración de Tabla
    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';

    // 2. Campos que se pueden llenar masivamente (Mass Assignment)
    protected $fillable = [
        'id_usuario',            // Relación con el login (App Móvil)
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'correo_electronico',
        'fecha_nacimiento',
        'sexo',                  // 'M', 'F', 'O'
        'ocupacion',
        'tipo_sangre',           // Valor actual (texto)
        'peso',                  // Valor actual (decimal)

        // Dirección
        'calle',
        'num_exterior',
        'num_interior',
        'colonia',
        'municipio',

        // Datos Médicos Rápidos (Texto libre)
        'alergias_criticas',
        'enfermedades_cronicas',
        'is_active',

        // FK
        'id_contacto_emergencia',
    ];

    // 3. Conversión de tipos (Casting)
    protected $casts = [
        'fecha_nacimiento' => 'date', // Importante para usar $paciente->fecha_nacimiento->age
        'peso' => 'decimal:2',
    ];

    // 4. Accessor: Obtener Nombre Completo fácilmente
    // Uso: $paciente->nombre_completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}";
    }

    // ==========================================================
    // RELACIONES (DEFINITIVAS)
    // ==========================================================

    // Relación con Usuario del Sistema (Login)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    // Relación con Contacto de Emergencia
    public function contactoEmergencia()
    {
        return $this->belongsTo(ContactoEmergencia::class, 'id_contacto_emergencia', 'id_contacto_emergencia');
    }

    // Relación: Un paciente tiene muchas Citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_paciente', 'id_paciente');
    }

    // Relación: Odontograma (Historial de dientes)
    public function odontogramas()
    {
        return $this->hasMany(Odontograma::class, 'id_paciente', 'id_paciente');
    }

    // Relación: Archivos (PDFs, RX, Fotos)
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'id_paciente', 'id_paciente');
    }

    // Relación: Reviews (Calificaciones dejadas por el paciente)
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_paciente', 'id_paciente');
    }

    // ==========================================================
    // RELACIONES DE HISTORIAL MÉDICO
    // ==========================================================

    // Historial de Peso (Evolución)
    public function historialPeso()
    {
        return $this->hasMany(PacientePeso::class, 'id_paciente', 'id_paciente')->orderBy('fecha_registro', 'desc');
    }

    // Historial de Tipo de Sangre (Bitácora de cambios)
    public function historialTipoSangre()
    {
        return $this->hasMany(PacienteTipoSangre::class, 'id_paciente', 'id_paciente');
    }

    // Relación Muchos a Muchos: Alergias (Catálogo)
    // Tabla Pivote: pacientes_alergias
    public function alergias()
    {
        return $this->belongsToMany(
            CatalogoAlergia::class,
            'pacientes_alergias',
            'id_paciente',
            'id_alergia'
        )->withTimestamps();
    }

    // Relación Muchos a Muchos: Enfermedades Crónicas (Catálogo)
    // Tabla Pivote: pacientes_enfermedades_cronicas
    public function enfermedades()
    {
        return $this->belongsToMany(
            CatalogoEnfermedadCronica::class,
            'pacientes_enfermedades_cronicas',
            'id_paciente',
            'id_enfermedad_cronica'
        )->withTimestamps();
    }

}
