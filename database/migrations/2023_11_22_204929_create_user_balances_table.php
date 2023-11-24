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
        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index()->comment('ID пользователя');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('balance', 10, 2)->default(0)->comment('Баланс');
            $table->string('type')->default('base')->index()->comment('Тип баланса');
            $table->unique(['user_id', 'type']); // Насколько я понял задачу, у пользователя может быть один баланс. Добавить тип баланса для потенциального масштабирования проекта в этом направлении
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_balances');
    }
};
