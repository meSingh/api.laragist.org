<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);

            $table->string('gender', 20)->nullable();

            $table->date('birthdate')->nullable();
            
            $table->decimal('height', 4, 2)->nullable();
            $table->string('height_type', 10)->nullable();
            $table->decimal('weight', 4, 2)->nullable();
            $table->string('weight_type', 10)->nullable();


            $table->decimal('height_goal', 4, 2)->nullable();
            $table->string('height_goal_type', 10)->nullable();
            $table->decimal('weight_goal', 4, 2)->nullable();
            $table->string('weight_goal_type', 10)->nullable();

            $table->string('location')->nullable();


            $table->integer('activity_level_id')->unsigned();


            $table->boolean('terms');
            $table->tinyInteger('status')->default('1');
            $table->rememberToken();
            $table->timestamps();
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
        Schema::drop('users');
    }
}
