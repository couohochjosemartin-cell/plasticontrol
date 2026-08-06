<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->string('folio', 20)->unique();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('metodo_pago', 30)
                ->default('Efectivo')
                ->index();

            $table->decimal('subtotal', 10, 2)
                ->default(0);

            $table->decimal('descuento', 10, 2)
                ->default(0);

            $table->decimal('total', 10, 2);

            $table->decimal('pago_recibido', 10, 2)
                ->nullable();

            $table->decimal('cambio', 10, 2)
                ->nullable();

            $table->string('estado', 20)
                ->default('Completada')
                ->index();

            $table->timestamp('fecha_venta')
                ->useCurrent()
                ->index();

            $table->timestamps();

            $table->index(
                ['fecha_venta', 'estado'],
                'ventas_fecha_estado_index'
            );

            $table->index(
                ['usuario_id', 'fecha_venta'],
                'ventas_usuario_fecha_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};