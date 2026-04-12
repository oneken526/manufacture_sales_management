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
            $table->string('code', 20)->unique()->comment('得意先コード（管理用）');
            $table->string('name', 255)->comment('社名');
            $table->string('name_kana', 255)->nullable()->comment('社名フリガナ');
            $table->string('postal_code', 10)->nullable()->comment('郵便番号');
            $table->text('address')->nullable()->comment('住所');
            $table->string('phone', 20)->nullable()->comment('電話番号');
            $table->string('email', 255)->nullable()->comment('連絡先メールアドレス');
            $table->tinyInteger('closing_day')->default(99)->comment('締日（1-28 または 99=月末）');
            $table->decimal('credit_limit', 15, 2)->default(0)->comment('与信限度額（0=制限なし）');
            $table->text('notes')->nullable()->comment('備考');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
