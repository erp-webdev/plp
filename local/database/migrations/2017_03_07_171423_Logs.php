<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('tbl', 50)->nullable();
            $table->text('Message');
            $table->integer('user_id')->nullable();
            $table->timestamp('created_at');
        });

        echo '"Logs" table created successfully.' . PHP_EOL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Logs');
    }
}
