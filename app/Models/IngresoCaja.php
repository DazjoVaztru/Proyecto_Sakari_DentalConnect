<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoCaja extends Model
{
    use HasFactory;

    protected $table = 'ingresos_caja';
    protected $primaryKey = 'id_ingreso';
    public $timestamps = true; // Created_at and updated_at exist in DB schema

    protected $fillable = [
        'id_clinica',
        'id_cita',
        'monto',
        'metodo',       // DB column is 'metodo' ('efectivo','tarjeta', etc.)
        'descripcion',  // DB column is 'descripcion'
        'fecha_ingreso',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'clinica_id');
    }
}