<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventario_id')
                ->constrained('inventarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('tipo_movimiento', 20)
                ->index();

            $table->unsignedInteger('cantidad');

            $table->unsignedInteger('stock_anterior');
            $table->unsignedInteger('stock_resultante');

            $table->string('motivo', 255);

            $table->string('referencia_tipo', 50)
                ->nullable()
                ->index();

            $table->unsignedBigInteger('referencia_id')
                ->nullable()
                ->index();

            $table->timestamp('fecha_movimiento')
                ->useCurrent()
                ->index();

            $table->timestamps();

            $table->index(
                ['inventario_id', 'fecha_movimiento'],
                'movimientos_inventario_fecha_index'
            );

            $table->index(
                ['usuario_id', 'fecha_movimiento'],
                'movimientos_usuario_fecha_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};