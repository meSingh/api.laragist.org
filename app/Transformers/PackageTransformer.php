<?php

namespace GistApi\Transformers;

use League\Fractal\TransformerAbstract;
use GistApi\Repositories\Package;

class PackageTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Package $package)
    {
    	return [
    		'package' => [
    			'name' 				=> $package->name,
    			'description'		=> $package->description,
    			'keywords'			=> $package->keywords,
    			'license'			=> $package->license,
    			'version'			=> $package->version,
    			'maintainers'		=> $package->maintainers,
    			'support'			=> $package->support,
    			'type'				=> $package->type,
    			'repository' 		=> $package->repository,
				'homepage' 			=> $package->homepage,
				'downloads_total'	=> $package->downloads_total,
				'downloads_monthly'	=> $package->downloads_monthly,
				'downloads_daily' 	=> $package->downloads_daily,
				'favorites' 		=> $package->favorites,
				'object_id' 		=> $package->object_id,
				'user_id' 			=> $package->user_id,
				'created'	 		=> $package->created,
				'last_updated' 		=> $package->last_updated,
				'supported' 		=> $package->supported,
				'status' 			=> $package->status,
				'versions'			=> $package->versions,
				'latest'			=> $package->latest,
				'categories'		=> $package->categories
    		]
    	];
    }

}
