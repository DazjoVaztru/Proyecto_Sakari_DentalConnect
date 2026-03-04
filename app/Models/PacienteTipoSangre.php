<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para el historial de tipo de sangre del paciente.
 *
 * Permite llevar un registro histórico de cambios o correcciones en el tipo de sangre.
 *
 * @property int $id_paciente_tipo_sangre
 * @property int $id_paciente
 * @property int $id_tipo_sangre
 * @property string $fecha_registro
 */
class PacienteTipoSangre extends Model
{
    use HasFactory;
    protected $table = 'paciente_tipo_sangre';
    protected $primaryKey = 'id_paciente_tipo_sangre';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_tipo_sangre',
        'fecha_registro',
    ];

    public function tipo()
    {
        return $this->belongsTo(CatalogoTipoSangre::class, 'id_tipo_sangre');
    }
}