<?php

namespace GistApi\Http\Controllers\v1\Auth;

use GistApi\Repositories\User;
use GistApi\Mailers\UserMailer;

use GistApi\Http\Requests\v1\Auth\RegistrationRequest;
use GistApi\Http\Controllers\v1\ApiController;

class AuthController extends ApiController
{
 
    /**
     * The User Mailer class instance.
     *
     * @var Mailer
     */
    protected $mailer;

 
    /**
     * Create a new User Mailer instance.
     *
     * @param UserMailer $mailer
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  \GistApi\Http\Requests\Auth\RegistrationRequest  $request
     * @return 
     */
    protected function register(RegistrationRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt( $request->password ),
            'terms'      => true
        ]);

        // Create random username for start 
        // in FIRSTNAME_LASTNAME_USERID format.
        $user->username = str_slug( $user->name . ' ' . $user->id );
        $user->confirmation_token = \Crypt::encrypt( $user->username );
        $user->save();

        // Send confirmation email to user
        $this->mailer->confirmation($user);

        return $this->response->created();
    }

}
