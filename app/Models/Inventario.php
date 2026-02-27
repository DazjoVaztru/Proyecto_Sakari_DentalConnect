<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la gestión del inventario de la clínica.
 *
 * @property int $id_item
 * @property int $id_clinica
 * @property string $nombre_item
 * @property int $stock
 * @property float $precio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_clinica',
        'nombre_item',
        'stock',
        'precio',
    ];
}