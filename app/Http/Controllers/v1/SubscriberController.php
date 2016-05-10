<?php

namespace GistApi\Http\Controllers\v1;

use Illuminate\Http\Request;
use Newsletter;
use GistApi\Http\Requests\v1\Subscriber\StoreRequest;
use GistApi\Http\Controllers\v1\ApiController;

class SubscriberController extends ApiController
{
    public function store(StoreRequest $request){

        // $data = [
        //     'firstName' => $request->firstName
        // ];

        Newsletter::subscribe($request->email);

        if(Newsletter::lastActionSucceeded())
            return $this->response->noContent();
        
        $result = Newsletter::getLastError();
        $code = substr($result, 0, 3);

        if($code == 400)
            return $this->response->errorBadRequest('You have already subscribed!');

    }
}
