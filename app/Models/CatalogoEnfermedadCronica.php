<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de catálogo para las enfermedades crónicas.
 *
 * @property int $id_enfermedad_cronica
 * @property string $nombre_enfermedad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CatalogoEnfermedadCronica extends Model
{
    use HasFactory;
    protected $table = 'catalogo_enfermedades_cronicas';
    protected $primaryKey = 'id_enfermedad_cronica';
    protected $fillable = ['nombre_enfermedad'];
}