<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pendente', 'em_progresso', 'concluida'])->default('pendente');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
