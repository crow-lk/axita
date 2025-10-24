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
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('gateway_charge', 12, 4)->default(0)->after('base_sub_total_incl_tax');
            $table->decimal('base_gateway_charge', 12, 4)->default(0)->after('gateway_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('gateway_charge');
            $table->dropColumn('base_gateway_charge');
        });
    }
};
