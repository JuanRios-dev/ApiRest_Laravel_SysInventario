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
        Schema::create('external_tasks', function (Blueprint $table) {
            $table->id();
			$table->foreignId('ticket_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->string('pieza');
			$table->string('descripcion');
			$table->decimal('valor', 10, 2);
			$table->string('codigoFactura');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_tasks');
    }
};
