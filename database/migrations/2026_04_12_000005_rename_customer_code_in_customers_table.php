<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// code カラムは create_customers_table で最終形に統合済みのため no-op
return new class extends Migration
{
    public function up(): void
    {
        // column already renamed in create_customers_table migration
    }

    public function down(): void
    {
        // no-op
    }
};
