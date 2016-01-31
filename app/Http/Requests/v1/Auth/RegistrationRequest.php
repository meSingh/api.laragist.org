<?php

namespace GistApi\Http\Requests\v1\Auth;

use GistApi\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;

class RegistrationRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'location'      => 'required',
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
            "Could not create new user.", 
            $validator->errors()
        );
    }

}
