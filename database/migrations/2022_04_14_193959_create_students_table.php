<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('son')->unique()->comment('学号');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('age')->nullable()->comment('年齢');
            $table->string('dept')->comment('系别');
            $table->tinyInteger('gender')->nullable()->default(0)->comment('0:女，1：男');
            $table->softDeletes();
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
        Schema::dropIfExists('students');
    }
}
