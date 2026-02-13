<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;
    protected $table = 'audit_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false; // Solo usa created_at

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'id_registro',
        'detalles', // JSON
    ];

    protected $casts = [
        'detalles' => 'array',
    ];
}