<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// カラム名は create_products_table で最終形に統合済みのため no-op
return new class extends Migration
{
    public function up(): void
    {
        // columns already renamed in create_products_table migration
    }

    public function down(): void
    {
        // no-op
    }
};
