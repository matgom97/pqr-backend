<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pqr extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // ID del usuario que creó la PQR
        'fecha_incidencia', // Fecha de la incidencia
        'identificacion',   // Identificación del usuario
        'primer_nombre',     // Primer nombre del usuario
        'primer_apellido',   // Primer apellido del usuario
        'correo',           // Correo del usuario
        'medio_notificacion', // Medio de notificación
        'tipo',             // Tipo de PQR (Queja, Petición, Reclamo)
        'causas',           // Causas relacionadas con la PQR
        'observacion',      // Observaciones adicionales
        'evidencias',       // Evidencias (puede ser un string o un JSON dependiendo de cómo lo manejes)
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}