<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigRecordatorio extends Model
{
    use HasFactory;
    protected $table = 'config_recordatorios';
    protected $primaryKey = 'id_regla';

    protected $fillable = [
        'id_clinica',
        'tiempo_anticipacion',
        'unidad_tiempo',
        'plantilla_mensaje',
    ];
}