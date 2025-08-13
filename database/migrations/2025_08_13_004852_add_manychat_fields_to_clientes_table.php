<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añadir solo los 4 campos esenciales que necesitas
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // === CAMPOS ESENCIALES DE MANYCHAT ===
            // 'nombre' ya existe en tu tabla actual
            $table->text('ubicacion')->nullable()->comment('Ubicación del cliente');
            $table->string('horario_disponibilidad')->nullable()->comment('Horario y disponibilidad');
            
            // === CAMPO MÍNIMO PARA TU GESTIÓN ===
            $table->text('notas_asesor')->nullable()->comment('Notas del asesor');
            
          
        });
    }

    /**
     * Revertir los cambios
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            
            $table->dropColumn([
                'ubicacion',
                'horario_disponibilidad',
                'notas_asesor'
            ]);
        });
    }
};