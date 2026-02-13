<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicidad extends Model
{
    use HasFactory;

    protected $table = 'publicidad';
    protected $primaryKey = 'id_publicidad';

    protected $fillable = [
        'id_usuario', // <--- IMPORTANTE: Agregamos esto para poder guardarlo
        'titulo',
        'descripcion',
        'imagen_path',
        'activo'
    ];

    // Relación: Una publicidad pertenece a un Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}