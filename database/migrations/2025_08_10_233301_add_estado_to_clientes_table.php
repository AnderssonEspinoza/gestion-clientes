<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapeo de estados antiguos a nuevos
        $mapeoEstados = [
            'Pendiente' => 'pendiente',
            'En Evaluacion' => 'en_evaluacion',
            'En Evaluación' => 'en_evaluacion',
            'Califica' => 'califica',
            'No califica' => 'no_califica',
            'No Califica' => 'no_califica',
            'Venta Concretada' => 'venta_concretada',
        ];

        foreach ($mapeoEstados as $estadoViejo => $estadoNuevo) {
            DB::table('clientes')
                ->where('estado', $estadoViejo)
                ->update(['estado' => $estadoNuevo]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios
        $mapeoReverso = [
            'pendiente' => 'Pendiente',
            'en_evaluacion' => 'En Evaluación',
            'califica' => 'Califica',
            'no_califica' => 'No Califica',
            'venta_concretada' => 'Venta Concretada',
        ];

        foreach ($mapeoReverso as $estadoNuevo => $estadoViejo) {
            DB::table('clientes')
                ->where('estado', $estadoNuevo)
                ->update(['estado' => $estadoViejo]);
        }
    }
};