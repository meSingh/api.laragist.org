<?php

use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTableOnMongo extends Migration
{

    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('packages', function ($collection) {
            $collection->index('name');

            $collection->unique('name');
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
