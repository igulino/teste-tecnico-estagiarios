<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo');
            $table->text('contexto')->nullable();

            /*
             * Registro afetado e origem da ação
             */
            $table->foreignId('funcionario_id')
                ->constrained('funcionarios')
                ->restrictOnDelete();

            $table->foreignId('solicitacao_id')
                ->nullable()
                ->constrained('solicitacoes')
                ->restrictOnDelete();

            $table->foreignId('executado_por_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Dados de transferência
             */
            $table->foreignId('setor_origem_id')
                ->nullable()
                ->constrained('setores')
                ->restrictOnDelete();

            $table->foreignId('setor_destino_id')
                ->nullable()
                ->constrained('setores')
                ->restrictOnDelete();

            /*
             * Dados de alteração salarial
             */
            $table->decimal('salario_anterior', 10, 2)->nullable();
            $table->decimal('salario_novo', 10, 2)->nullable();

            /*
             * Dados de promoção
             */
            $table->foreignId('cargo_anterior_id')
                ->nullable()
                ->constrained('cargos')
                ->restrictOnDelete();

            $table->foreignId('cargo_novo_id')
                ->nullable()
                ->constrained('cargos')
                ->restrictOnDelete();

            /*
             * Snapshot usado principalmente na exclusão
             */
            $table->string('nome_funcionario_snapshot')->nullable();

            $table->timestamps();

            $table->index('tipo');
            $table->index(['funcionario_id', 'tipo']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};

    /**
     * Reverse the migrations.
     */
   

