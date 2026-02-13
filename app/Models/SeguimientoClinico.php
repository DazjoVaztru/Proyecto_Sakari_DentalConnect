<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoClinico extends Model
{
    use HasFactory;
    protected $table = 'seguimiento_clinico';
    protected $primaryKey = 'id_seguimiento';

    protected $fillable = [
        'id_cita',
        'postratamiento', // 'si', 'no'
        'id_servicio',
        'observaciones',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita');
    }
}