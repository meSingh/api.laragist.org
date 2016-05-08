<?php

namespace GistApi\Mailers;

use Illuminate\Contracts\Mail\Mailer;

abstract class AppMailer
{

    /**
     * The Mailer class instance.
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * The default view for all emails.
     *
     * @var string
     */
    protected $view = 'emails.default';

    /**
     * The base views folder for email templates.
     *
     * @var string
     */
    protected $baseView = 'emails.';


    /**
     * The default sender of the email.
     *
     * @var string
     */
    protected $from = 'hello@laragist.com';

    /**
     * The default sender's name.
     *
     * @var string
     */
    protected $fromName = 'LaraGist';

    /**
     * The recipient of the email.
     *
     * @var string
     */
    protected $to;


    /**
     * The cc recipient's of the email.
     *
     * @var string
     */
    protected $cc = [];


    /**
     * The bcc recipient's of the email.
     *
     * @var string
     */
    protected $bcc = [];

    /**
     * The recipient's name of the email.
     *
     * @var string
     */
    protected $toName = "User";

    /**
     * The subject of the email.
     *
     * @var string
     */
    protected $subject;

    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];


    /**
     * Create a new app mailer instance.
     *
     * @param Mailer $mailer
     */
    public function __construct()
    {
        // $this->mailer = new Mailer;
    }


    /**
     * Setter for from
     *
     * @param string $from Sender's email
     * @param string $fromName Sender's name
     *
     * @return self
     */
    public function from($from, $fromName = null)
    {
        $this->from = $from;
        if( !is_null($fromName) )
            $this->fromName = $fromName;

        return $this;
    }


    /**
     * Setter for to
     *
     * @param string $to Receiver's email
     * @param string $toName Receiver's name
     *
     * @return self
     */
    public function to($to, $toName = null)
    {
        $this->to = $to;
        if( !is_null($toName) )
            $this->toName = $toName;

        return $this;
    }



    /**
     * Setter for cc
     *
     * @param string $cc CC Receiver's emails
     *
     * @return self
     */
    public function cc($cc)
    {
        $this->cc[] = $cc;
        return $this;
    }


    /**
     * Setter for bcc
     *
     * @param string $bcc CC Receiver's emails
     *
     * @return self
     */
    public function bcc($bcc)
    {
        $this->bcc[] = $bcc;
        return $this;
    }
    

    /**
     * Setter for subject
     *
     * @param mixed $subject Subject of message
     *
     * @return self
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }


    /**
     * Setter for view
     *
     * @param string $view view to be used to send data
     * @param array $data data to present in view
     *
     * @return self
     */
    public function view($view, $data = [])
    {
        $this->view = $this->baseView . $view;
        $this->data = $data;
        return $this;
    }


    /**
     * Send email to given person
     *
     * @data array data sent to views
     **/
    public function send()
    {

        return \Mail::send($this->view, $this->data, function ($message) {
            $message->from($this->from, $this->fromName)
                ->subject($this->subject)
                ->to($this->to, $this->toName);

            foreach ($this->cc as $cc) 
            {
                $message->cc($cc);
            }

            foreach ($this->bcc as $bcc) 
            {
                $message->bcc($bcc);
            }
        });
    }
}
