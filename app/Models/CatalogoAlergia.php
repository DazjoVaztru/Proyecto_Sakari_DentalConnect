<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoAlergia extends Model
{
    use HasFactory;
    protected $table = 'catalogo_alergias';
    protected $primaryKey = 'id_alergia';
    protected $fillable = ['nombre_alergeno'];
}