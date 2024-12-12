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
            $table->date('transaction_date');
            $table->string('reference_no')->nullable();
            // $table->string('voucher_no')->unique();
            $table->text('description')->nullable();
            $table->decimal('transaction_amount', 15, 2)->default(0);
            $table->tinyInteger('is_opening_balance_transaction')->default(0);
            $table->foreignId('account_book_id')->constrained('account_books', 'id');
            $table->foreignId('company_id')->constrained('companies', 'id');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
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
