<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rol_id')
                ->constrained('roles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('creado_por_id')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('nombre_completo', 150);

            $table->string('usuario', 50)->unique();

            $table->string('password');

            $table->string('estado', 20)
                ->default('Activo')
                ->index();

            $table->timestamp('ultimo_acceso_at')->nullable();

            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['rol_id', 'estado'],
                'usuarios_rol_estado_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};