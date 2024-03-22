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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
			$table->foreignId('ticket_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->boolean('herramientas');
			$table->boolean('espejos');
			$table->boolean('llaves');
			$table->boolean('tapasLaterales');
			$table->boolean('cocas');
			$table->boolean('tapasGas');
			$table->boolean('guardacadenas');
			$table->boolean('lucesDireccional');
			$table->boolean('stop');
			$table->boolean('pito');
			$table->boolean('bateria');
			$table->boolean('luzFarola');
			$table->boolean('luzFreno');
			$table->boolean('lucesPiloto');
			$table->boolean('tanqueGasolina');
			$table->boolean('tapas');
			$table->boolean('guardabarro');
			$table->boolean('sillin');
			$table->boolean('manijas');
			$table->boolean('exosto');
			$table->boolean('farola');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
