<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipoSangre extends Model
{
    use HasFactory;
    protected $table = 'catalogo_tipo_sangre';
    protected $primaryKey = 'id_tipo_sangre';
    protected $fillable = ['tipo'];
}