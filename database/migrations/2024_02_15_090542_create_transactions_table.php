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
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('from_account_id')->nullable()->constrained('accounts')->onUpdate('set null')->onDelete('set null');
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->onUpdate('set null')->onDelete('set null');
            $table->foreignId('transaction_category_id')->nullable()->constrained('transaction_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type');
            $table->double('amount', 10, 2);
            $table->date('date');
            $table->string('notes')->nullable();
            $table->timestamps();
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
