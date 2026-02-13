<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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