<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    use HasFactory;

    protected $table = 'odontograma';

    protected $fillable = [
        'paciente_id',
        'cita_id',
        'diente_nro',  // 11 al 85
        'cara',        // vestibular, lingual...
        'estado',      // caries, sano...
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}