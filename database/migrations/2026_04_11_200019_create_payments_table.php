<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->date('payment_date');
            $table->enum('payment_method', [
                'bank_transfer',
                'check',
                'cash',
                'other',
            ])->default('bank_transfer');
            $table->decimal('amount', 15, 2);
            $table->decimal('unallocated_amount', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payments_amount CHECK (amount >= 1)');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
