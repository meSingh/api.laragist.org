<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) 
        {
            $table->increments('id');
            
            $table->string('name')->unique();
            
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('license')->nullable();
            $table->string('version')->nullable();
            
            $table->json('maintainers');
            $table->json('support');
            $table->string('type')->nullable();
            
            $table->string('repository')->nullable();
            $table->string('homepage')->nullable();
            
            $table->integer('downloads_total')->unsigned();
            $table->integer('downloads_monthly')->unsigned();
            $table->integer('downloads_daily')->unsigned();
            $table->integer('favorites')->unsigned();
            
            $table->string('object_id')->nullable();
            $table->integer('user_id')->unsigned()->nullable();

            $table->text('created')->nullable();
            $table->text('last_updated')->nullable();
            
            $table->tinyInteger('supported')->default('0');
            $table->tinyInteger('status')->default('0');

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
        Schema::drop('packages');
    }
}
