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
    			'name' 				=> $package->package->name,
    			'description'		=> $package->package->description,
    			'keywords'			=> $package->package->keywords,
    			'license'			=> $package->package->license,
    			'version'			=> $package->package->version,
    			'maintainers'		=> $package->package->maintainers,
    			'support'			=> $package->package->support,
    			'type'				=> $package->package->type,
    			'repository' 		=> $package->package->repository,
				'homepage' 			=> $package->package->homepage,
				'downloads_total'	=> $package->package->downloads_total,
				'downloads_monthly'	=> $package->package->downloads_monthly,
				'downloads_daily' 	=> $package->package->downloads_daily,
				'favorites' 		=> $package->package->favorites,
				'object_id' 		=> $package->package->object_id,
				'user_id' 			=> $package->package->user_id,
				'created'	 		=> $package->package->created,
				'last_updated' 		=> $package->package->last_updated,
				'supported' 		=> $package->package->supported,
				'status' 			=> $package->package->status,
				'versions'			=> $package->package->versions,
				'latest'			=> $package->package->latest,
				'categories'		=> $package->package->categories
    		]
    	];
    }

}
