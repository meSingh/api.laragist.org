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
        $categories = Category::with('packageCategories')
                        ->groupBy('categories.name');

        $categories = $categories->map(function($item){

            if(! is_null($item->package_categories) )
                $item->package_categories = $item->package_categories->count();
            else
                $item->package_categories = 0;
            
            return $item;
        });

        return $this->response->collection($categories, new CategoriesTransformer);

    }

}
