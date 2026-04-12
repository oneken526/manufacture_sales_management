<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// unit_name・notes は create_products_table に統合済みのため no-op
return new class extends Migration
{
    public function up(): void
    {
        // columns already included in create_products_table migration
    }

    public function down(): void
    {
        // no-op
    }
};
