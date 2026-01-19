<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receipt_debt', function (Blueprint $table) {
            $table->id();

            $table->foreignId('receipt_id')
                ->constrained('receipts')
                ->cascadeOnDelete();

            $table->foreignId('debt_id')
                ->constrained('debts')
                ->cascadeOnDelete();

            $table->decimal('paid_amount', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_debt');
    }
};
