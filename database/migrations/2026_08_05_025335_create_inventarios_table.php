<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->unique()
                ->constrained('productos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('stock_actual')
                ->default(0);

            $table->unsignedInteger('stock_minimo')
                ->default(10);

            $table->string('estado', 30)
                ->default('Agotado')
                ->index();

            $table->timestamps();

            $table->index(
                ['estado', 'stock_actual'],
                'inventarios_estado_stock_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
