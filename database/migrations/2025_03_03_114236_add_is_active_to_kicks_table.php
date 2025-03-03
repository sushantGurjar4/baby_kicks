<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToKicksTable extends Migration
{
    public function up()
    {
        Schema::table('kicks', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down()
    {
        Schema::table('kicks', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
