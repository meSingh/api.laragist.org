<?php

namespace GistApi\Http\Requests\v1\Subscriber;

use GistApi\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName'    => 'required',
            'email'         => 'required|email',
        ];
    }

    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return \Dingo\Api\Exception\StoreResourceFailedException
     */
    protected function formatErrors(Validator $validator)
    {
        throw new \Dingo\Api\Exception\StoreResourceFailedException(
            "Could not store the subscriber", 
            $validator->errors()
        );
    }

}
