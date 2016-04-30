<?php

namespace GistApi\Http\Controllers\v1;

use Illuminate\Http\Request;

use GistApi\Repositories\Category;

use GistApi\Http\Controllers\v1\ApiController;

class CategoryController extends ApiController
{

    public function index()
    {
        return Category::with('packageCategories')
                        ->select( \DB::raw('categories.name as name, categories.slug as slug, package_id, count(package_id) as total') )
                        ->groupBy('categories.name')
                        ->get();
    }

}
