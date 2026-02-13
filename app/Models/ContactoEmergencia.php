<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoEmergencia extends Model
{
    use HasFactory;

    protected $table = 'contacto_emergencia';
    protected $primaryKey = 'id_contacto_emergencia';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'numero_telefono',
    ];
}