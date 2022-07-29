<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('auth_token', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('type');
        //     $table->string('token');
        //     $table->string('user_id');
        //     $table->integer('rec_usr')->nullable();
        //     $table->softDeletes();
        //     $table->timeStamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_token');
    }
}
