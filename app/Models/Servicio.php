<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo del catálogo de servicios o tratamientos dentales.
 *
 * Define los servicios ofrecidos por la clínica y su precio base.
 *
 * @property int $id_servicio
 * @property int $id_clinica
 * @property string $nombre_servicio
 * @property float $precio_base
 * @property string|null $categoria
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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