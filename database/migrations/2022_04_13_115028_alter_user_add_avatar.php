<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserAddAvatar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('gender')->default(0)->comment('0为男，1为女');
            $table->tinyInteger('status')->default(0)->comment('0未通过，1已通过');
            $table->string('phone')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('avatar',100)->nullable(true)->default('avatar/default.jpg');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
