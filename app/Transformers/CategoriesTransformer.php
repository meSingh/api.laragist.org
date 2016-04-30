<?php

namespace GistApi\Transformers;

use League\Fractal\TransformerAbstract;
use GistApi\Repositories\Category;

class CategoriesTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Category $category)
    {
    	return [
    		'name' 		=> $category->name,
    		'slug' 		=> $category->slug
    	];
    }

}
