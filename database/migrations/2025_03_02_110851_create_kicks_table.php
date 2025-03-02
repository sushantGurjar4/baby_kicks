<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKicksTable extends Migration
{
    public function up()
    {
        Schema::create('kicks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('kick_time')->useCurrent(); // Stores the time of the kick
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kicks');
    }
}
