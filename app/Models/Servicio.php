<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    // 1. EL NOMBRE EXACTO DE TU TABLA EN HEIDISQL
    protected $table = 'catalogo_servicios';

    // 2. TU LLAVE PRIMARIA
    protected $primaryKey = 'id_servicio';

    // 3. LAS COLUMNAS EXACTAS QUE TIENES (Según tu imagen)
    protected $fillable = [
        'id_clinica',
        'nombre_servicio',
        'precio_base',
        'categoria' // En tu imagen tienes 'categoria', no 'descripcion'
    ];
}