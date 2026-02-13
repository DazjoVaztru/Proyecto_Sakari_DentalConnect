<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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