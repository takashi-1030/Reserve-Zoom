<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('9:00')->nullable();
            $table->string('9:30')->nullable();
            $table->string('10:00')->nullable();
            $table->string('10:30')->nullable();
            $table->string('11:00')->nullable();
            $table->string('11:30')->nullable();
            $table->string('12:00')->nullable();
            $table->string('12:30')->nullable();
            $table->string('13:00')->nullable();
            $table->string('13:30')->nullable();
            $table->string('14:00')->nullable();
            $table->string('14:30')->nullable();
            $table->string('15:00')->nullable();
            $table->string('15:30')->nullable();
            $table->string('16:00')->nullable();
            $table->string('16:30')->nullable();
            $table->string('17:00')->nullable();
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
        Schema::dropIfExists('times');
    }
}
