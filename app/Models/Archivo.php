<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un archivo adjunto en el sistema.
 *
 * Puede estar asociado a un paciente o a una cita específica.
 *
 * @property int $id_archivo
 * @property int $id_paciente
 * @property int|null $id_cita
 * @property string $url_archivo
 * @property string $tipo
 * @property string|null $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Archivo extends Model
{
    use HasFactory;
    protected $table = 'archivos';
    protected $primaryKey = 'id_archivo';

    public $timestamps = false; // La tabla no tiene created_at ni updated_at

    protected $fillable = [
        'id_paciente',
        'id_cita',
        'url_archivo',
        'tipo',
        'descripcion',
    ];
}