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
       
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();

            $table->string('tipo');
            $table->string('status')->default('pendente');

            $table->foreignId('funcionario_id')
                ->constrained('funcionarios');

            $table->foreignId('solicitado_por_user_id')
                ->constrained('users');

            $table->foreignId('decidido_por_user_id')
                ->nullable()
                ->constrained('users');

            $table->foreignId('setor_aprovador_id')
                ->constrained('setores');

            // Transferência
            $table->foreignId('setor_origem_id')
                ->nullable()
                ->constrained('setores');

            $table->foreignId('setor_destino_id')
                ->nullable()
                ->constrained('setores');

            // Aumento salarial
            $table->decimal('salario_atual', 10, 2)->nullable();
            $table->decimal('salario_proposto', 10, 2)->nullable();

            // Promoção
            $table->foreignId('cargo_atual_id')
                ->nullable()
                ->constrained('cargos');

            $table->foreignId('cargo_proposto_id')
                ->nullable()
                ->constrained('cargos');

            $table->text('motivo_solicitacao')->nullable();
            $table->text('justificativa_decisao')->nullable();
            $table->timestamp('decidido_em')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
