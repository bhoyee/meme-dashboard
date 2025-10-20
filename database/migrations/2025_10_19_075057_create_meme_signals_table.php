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
    Schema::create('meme_signals', function (Blueprint $table) {
        $table->id();
        $table->string('token')->index();
        $table->string('multiplier')->nullable();
        $table->string('volume')->nullable();
        $table->string('chain')->nullable();
        $table->string('contract')->nullable();
        $table->string('source')->nullable();
        $table->text('raw_message')->nullable();
        $table->timestamp('detected_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meme_signals');
    }
};
