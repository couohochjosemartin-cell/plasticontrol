<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo', 30)->unique();

            $table->string('nombre', 150)->index();

            $table->text('descripcion')->nullable();

            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);

            $table->unsignedInteger('stock_inicial')
                ->default(0);

            $table->string('imagen', 255)->nullable();

            $table->string('estado', 20)
                ->default('Activo')
                ->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['categoria_id', 'estado', 'deleted_at'],
                'productos_categoria_estado_deleted_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
