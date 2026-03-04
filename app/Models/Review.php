<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para las reseñas de los pacientes.
 *
 * Almacena la calificación y comentarios de los pacientes sobre el servicio recibido.
 *
 * @property int $id_review
 * @property int $id_cita
 * @property int $id_paciente
 * @property int $calificacion
 * @property string|null $comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_cita',
        'id_paciente',
        'calificacion',
        'comentario',
    ];
}