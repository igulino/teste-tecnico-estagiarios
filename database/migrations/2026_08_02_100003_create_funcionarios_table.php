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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('salary', 10, 2);

            $table->foreignId('cargo_id')->constrained('cargos')->restrictOnDelete();
            $table->foreignId('setor_id')->constrained('setores')->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
