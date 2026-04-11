<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_code', 20)->unique();
            $table->string('name', 255);
            $table->text('address')->nullable();
            $table->tinyInteger('closing_day')->default(99); // 1-28 or 99=末日
            $table->decimal('credit_limit', 15, 2)->default(0); // 0=制限なし
            $table->string('email', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
