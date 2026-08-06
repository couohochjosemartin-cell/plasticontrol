<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_venta', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_id')
                ->constrained('ventas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('cantidad');

            $table->decimal('precio_unitario', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            $table->index(
                ['producto_id', 'venta_id'],
                'detalles_producto_venta_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_venta');
    }
};