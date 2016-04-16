<?php

namespace GistApi\Http\Controllers\v1\Users;

use GistApi\Repositories\User;
use GistApi\Mailers\UserMailer;

use GistApi\Http\Requests\v1\Users\RegistrationStep2Request;
use GistApi\Http\Controllers\v1\ApiController;

class ProfileController extends ApiController
{
 
    /**
     * The User Mailer class instance.
     *
     * @var Mailer
     */
    protected $mailer;

 
    /**
     * Create a new user mailer instance.
     *
     * @param UserMailer $mailer
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Confirm user account
     *
     * @param integer   $id     User id
     * @param string    $code   User confirmation code
     * @return 
     */
    protected function confirm($id, $code)
    {
        $user = User::whereId($id)
                    ->where( 'confirmation_token', $code)
                    ->update([ 'confirmed' => 1 ]);

        if(!$user)
            return $this->response->errorBadRequest("Wrong confirmation code!! ");

        return $this->response->noContent();
    }

}
