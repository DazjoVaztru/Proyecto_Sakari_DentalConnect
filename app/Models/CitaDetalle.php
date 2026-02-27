<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los detalles adicionales de una cita.
 *
 * Permite agregar múltiples servicios o tratamientos a una sola cita.
 *
 * @property int $id
 * @property int $cita_id
 * @property int $servicio_id
 * @property float $precio_cobrado
 * @property int $cantidad
 * @property string|null $observaciones
 */
class CitaDetalle extends Model
{
    use HasFactory;

    protected $table = 'cita_detalles';
    public $timestamps = false;

    protected $fillable = [
        'cita_id',
        'servicio_id',
        'precio_cobrado',
        'cantidad',
        'observaciones',
    ];

    // Relación inversa con la Cita
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    // Relación con el Servicio original
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}