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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index()->comment('ID пользователя');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('balance_id')->index()->comment('ID баланса пользователя');
            $table->foreign('balance_id')->references('id')->on('user_balances')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('Сумма операции');
            $table->string('type')->index()->comment('Тип операции');
            $table->text('description')->comment('Описание');
            $table->fullText('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
