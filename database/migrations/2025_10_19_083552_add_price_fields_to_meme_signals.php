<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('meme_signals', function (Blueprint $table) {
        $table->decimal('price_usd', 20, 8)->nullable()->after('volume');
        $table->decimal('market_cap_usd', 20, 2)->nullable()->after('price_usd');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meme_signals', function (Blueprint $table) {
            //
        });
    }
};
