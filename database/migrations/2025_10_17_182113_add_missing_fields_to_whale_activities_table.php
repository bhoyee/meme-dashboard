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
        Schema::table('whale_activities', function (Blueprint $table) {
            $table->decimal('value_usd', 20, 2)->nullable()->after('amount');
        });
    }

    public function down()
    {
        Schema::table('whale_activities', function (Blueprint $table) {
            $table->dropColumn([
               
             
             
           
           
                'value_usd',
               
            ]);
        });
    }
};
