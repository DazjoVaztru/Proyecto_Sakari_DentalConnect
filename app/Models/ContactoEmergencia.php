<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los contactos de emergencia de los pacientes.
 * 
 * @property int $id_contacto_emergencia
 * @property string $nombre
 * @property string|null $apellido_paterno
 * @property string|null $apellido_materno
 * @property string|null $numero_telefono
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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