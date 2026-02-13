<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'tokens';
    protected $primaryKey = 'id_token'; // O el nombre de tu llave primaria si es diferente

    // ESTA ES LA LÍNEA MÁGICA QUE SOLUCIONA EL ERROR
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'token',
        'tipo_token',
        'estado',
        'fecha_creacion',
        'fecha_expiracion'
    ];
}