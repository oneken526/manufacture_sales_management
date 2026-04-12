<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->foreignId('category_id')
                  ->constrained('product_categories')
                  ->restrictOnDelete();
            $table->decimal('unit_price', 15, 2)->default(0)->comment('標準単価');
            $table->string('unit_name', 20)->nullable()->comment('単位（個・本・kg 等）');
            $table->text('notes')->nullable()->comment('備考');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
