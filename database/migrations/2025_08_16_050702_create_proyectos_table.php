<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla proyectos
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();                            // id automático
            $table->string('nombre');                // nombre del proyecto
            $table->date('fecha_inicio');            // fecha de inicio
            $table->string('estado');                // estado (ej: planificado, en curso, finalizado)
            $table->string('responsable');           // responsable del proyecto
            $table->decimal('monto', 12, 2);         // monto en dinero (máx. 12 dígitos, 2 decimales)
            $table->timestamps();                    // created_at y updated_at
        });
    }

    /**
     * Elimina la tabla si existe (rollback)
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
