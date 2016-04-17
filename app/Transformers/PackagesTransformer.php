<?php

namespace GistApi\Transformers;

use League\Fractal\TransformerAbstract;
use GistApi\Repositories\Package;

class PackagesTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Package $package)
    {
        return $package;
    }

}
