<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToKicksTable extends Migration
{
    public function up()
    {
        Schema::table('kicks', function (Blueprint $table) {
            // Add a nullable text column for description
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('kicks', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
