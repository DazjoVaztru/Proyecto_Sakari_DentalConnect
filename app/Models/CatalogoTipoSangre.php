<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de catálogo para los tipos de sangre.
 *
 * @property int $id_tipo_sangre
 * @property string $tipo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CatalogoTipoSangre extends Model
{
    use HasFactory;
    protected $table = 'catalogo_tipo_sangre';
    protected $primaryKey = 'id_tipo_sangre';
    protected $fillable = ['tipo'];
}