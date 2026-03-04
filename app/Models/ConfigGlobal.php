<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para las configuraciones globales.
 */
class ConfigGlobal extends Model
{
    use HasFactory;

    protected $table = 'config_global';
    protected $primaryKey = 'id_config';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];
}
