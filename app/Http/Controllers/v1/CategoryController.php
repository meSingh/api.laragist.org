<?php

namespace GistApi\Http\Controllers\v1;

use Illuminate\Http\Request;

use GistApi\Repositories\Category;

use GistApi\Http\Controllers\v1\ApiController;

use GistApi\Transformers\CategoriesTransformer;

class CategoryController extends ApiController
{

    public function index()
    {
        $categories = Category::groupBy('name')->get();

        return $this->response->collection($categories, new CategoriesTransformer);

    }

}
