<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoEnfermedadCronica extends Model
{
    use HasFactory;
    protected $table = 'catalogo_enfermedades_cronicas';
    protected $primaryKey = 'id_enfermedad_cronica';
    protected $fillable = ['nombre_enfermedad'];
}