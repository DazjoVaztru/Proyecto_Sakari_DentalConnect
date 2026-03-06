<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    // 1. Nombre de la tabla
    protected $table = 'catalogo_servicios';

    // 2. Llave primaria (Crucial para que el delete funcione)
    protected $primaryKey = 'id_servicio';

    // 3. DESACTIVAR TIMESTAMPS 
    // Si tu tabla no tiene las columnas created_at y updated_at, DEBES poner esto en false.
    public $timestamps = false; 

    // 4. Columnas asignables
    protected $fillable = [
        'id_clinica',
        'nombre_servicio',
        'precio_base',
        'categoria'
    ];
}