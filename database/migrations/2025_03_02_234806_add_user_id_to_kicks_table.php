<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToKicksTable extends Migration
{
    public function up()
    {
        Schema::table('kicks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
            // If you want to enforce that every kick belongs to a user:
            // $table->unsignedBigInteger('user_id')->after('id');

            // If you have a users table and want a foreign key constraint:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('kicks', function (Blueprint $table) {
            // Drop the foreign key first if you used it
            // $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
