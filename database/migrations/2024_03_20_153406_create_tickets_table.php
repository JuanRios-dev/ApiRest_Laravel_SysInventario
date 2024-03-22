<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
			$table->foreignId('customer_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('motorcycle_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->date('fecha');
			$table->time('hora');
			$table->string('tipoTrabajo');
			$table->string('gasolina');
			$table->integer('kilometraje');
			$table->text('observaciones');
			$table->string('estado', 50);
			$table->text('firma_cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
