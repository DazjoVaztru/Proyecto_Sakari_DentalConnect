<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;
    protected $table = 'archivos';
    protected $primaryKey = 'id_archivo';

    protected $fillable = [
        'id_paciente',
        'id_cita',
        'url_archivo',
        'tipo',
        'descripcion',
    ];
}