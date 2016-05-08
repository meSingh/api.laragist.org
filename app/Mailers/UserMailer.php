<?php

namespace GistApi\Mailers;

use GistApi\Repositories\User;

class UserMailer extends AppMailer
{

    /**
     * The user modal instance.
     *
     * @var string
     */
    protected $user;

    /**
     * The base view for email.
     *
     * @var string
     */
    protected $baseView = 'emails.users.';


    /**
     * Create a new app mailer instance.
     *
     * @param Mailer $mailer
     */
    // public function __construct(User $user)
    // {
    //     $this->user = $user;
    // }


    public function confirmation(User $user)
    {
        $this->subject = "Confirm your account at LaraGist";
        $this->view = $this->baseView . 'confirmation';
        $this->to = $user->email;
        $this->toName = $user->name;
        $this->data['user'] = $user;
        $this->data['confirmation_url'] = config('app.url') . '/users/' . $user->id . '/confirm/' . $user->confirmation_token;
        $this->send();
    }

}
