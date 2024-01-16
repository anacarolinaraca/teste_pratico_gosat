<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('instituicaoFinanceira');
            $table->string('modalidadeCredito');
            $table->float('valorAPagar', 10, 2);
            $table->float('valorSolicitado', 10, 2);
            $table->float('taxaJuros', 8, 4);
            $table->float('qntParcelas', 3, 0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};
