<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'estado',
        'asesor_id',

         // Campos esenciales de ManyChat
        'ubicacion', // Ubicacion
        'horario_disponibilidad', // HorarioDisponibilidad
        
        // Campo adicional mínimo
        'notas_asesor'
    ];

    // Estados basados en lo que tienes en tu BD
    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_evaluacion' => 'En Evaluación',
        'califica' => 'Califica',
        'no_califica' => 'No Califica',
        'venta_concretada' => 'Venta Concretada',
        'inactivo' => 'Inactivo'
    ];

    protected $attributes = [
        'estado' => 'pendiente'
    ];

    public static function getColoresEstados()
    {
        return [
            'pendiente' => '#ffc107',           // Amarillo
            'en_evaluacion' => '#0dcaf0',       // Azul claro
            'califica' => '#198754',            // Verde
            'no_califica' => '#dc3545',         // Rojo
            'venta_concretada' => '#6f42c1',    // Morado
            'inactivo' => '#6c757d'             // Gris
        ];
    }

    public function getEstadoLabelAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Sin Estado';
    }

    // Relación con el asesor
    public function asesor()
    {
        return $this->belongsTo(\App\Models\User::class, 'asesor_id');
    }
}