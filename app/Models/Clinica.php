<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    use HasFactory;

    protected $table = 'clinicas';
    protected $primaryKey = 'id_clinica';

    protected $fillable = [
        'nombre_comercial',
        'rfc_clinica',
        'numero_telefono',
        'localidad',
        'estado',
        'codigo_postal',
        'config_anticipo_pct'
    ];
}