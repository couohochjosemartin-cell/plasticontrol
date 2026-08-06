<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_negocio', 150);

            $table->string('propietario', 150);

            $table->string('rfc', 20)
                ->nullable();

            $table->string('telefono', 20)
                ->nullable();

            $table->string('direccion', 255)
                ->nullable();

            $table->string('logo', 255)
                ->nullable();

            $table->string('moneda', 10)
                ->default('MXN');

            $table->decimal('iva', 5, 2)
                ->default(16.00);

            $table->string('zona_horaria', 100)
                ->default('America/Mexico_City');

            $table->string('version', 20)
                ->default('1.0');

            $table->string('desarrollador', 150);

            $table->date('ultima_actualizacion')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};