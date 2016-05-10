<?php

namespace GistApi\Http\Controllers\v1;

use Illuminate\Http\Request;
use Newsletter;
use GistApi\Http\Requests\v1\Subscriber\StoreRequest;
use GistApi\Http\Controllers\v1\ApiController;

class SubscriberController extends ApiController
{
    public function store(Request $request){

        Newsletter::subscribe($request->email);

        if(Newsletter::lastActionSucceeded())
            return $this->response->noContent();
        else
            return $this->response->errorBadRequest(Newsletter::getLastError());

    }
}
