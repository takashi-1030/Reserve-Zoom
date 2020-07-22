<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('corporate')->nullable();
            $table->string('name');
            $table->string('tel');
            $table->string('email');
            $table->date('date');
            $table->time('start');
            $table->text('meeting_url');
            $table->string('join_url');
            $table->string('meeting_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zooms');
    }
}
