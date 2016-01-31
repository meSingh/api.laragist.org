<?php

namespace GistApi\Http\Requests;

class ApiRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // /**
    //  * Format the errors from the given Validator instance.
    //  *
    //  * @param  \Illuminate\Contracts\Validation\Validator  $validator
    //  * @return \Dingo\Api\Exception\ResourceException
    //  */
    // protected function formatErrors(Validator $validator)
    // {
    //     throw new \Dingo\Api\Exception\ResourceException(
    //         "Could not create new user.", 
    //         $validator->errors()
    //     );
    // }
}
