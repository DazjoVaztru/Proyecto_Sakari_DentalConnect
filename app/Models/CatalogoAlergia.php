<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de catálogo para los tipos de alergias.
 *
 * @property int $id_alergia
 * @property string $nombre_alergeno
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CatalogoAlergia extends Model
{
    use HasFactory;
    protected $table = 'catalogo_alergias';
    protected $primaryKey = 'id_alergia';
    protected $fillable = ['nombre_alergeno'];
}