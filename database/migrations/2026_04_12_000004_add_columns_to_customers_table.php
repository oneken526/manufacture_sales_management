<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// name_kana・postal_code・phone・notes は create_customers_table に統合済みのため no-op
return new class extends Migration
{
    public function up(): void
    {
        // columns already included in create_customers_table migration
    }

    public function down(): void
    {
        // no-op
    }
};
