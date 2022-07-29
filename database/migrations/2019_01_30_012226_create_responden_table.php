<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespondenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responden', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama',50);
            $table->string('telepon',15);
            $table->integer('umur');
            $table->string('alamat',150);
            $table->string('email',50);
            $table->integer('rec_usr')->nullable();
            $table->softDeletes();
            $table->timeStamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responden');
    }
}
