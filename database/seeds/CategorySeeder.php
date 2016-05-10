<?php

use Illuminate\Database\Seeder;
use GistApi\Repositories\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = [
            [
            ]
        ];

        Category::insert($categories);
    }
}
