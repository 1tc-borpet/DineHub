<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->nullable()->after('category_id')->constrained('restaurants')->onDelete('cascade');
        });

        // Töltse ki a meglévő MenuItem-eket a kategória restaurant_id-jával
        DB::table('menu_items as mi')
            ->join('menu_categories as mc', 'mi.category_id', '=', 'mc.id')
            ->update(['mi.restaurant_id' => DB::raw('mc.restaurant_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeignIdFor('Restaurant');
            $table->dropColumn('restaurant_id');
        });
    }
};
