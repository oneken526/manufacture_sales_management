<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('name_kana', 255)->nullable()->after('name')->comment('社名フリガナ');
            $table->string('postal_code', 10)->nullable()->after('name_kana')->comment('郵便番号');
            $table->string('phone', 20)->nullable()->after('address')->comment('電話番号');
            $table->text('notes')->nullable()->after('email')->comment('備考');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['name_kana', 'postal_code', 'phone', 'notes']);
        });
    }
};
