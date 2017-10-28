<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FreeSubscriptionConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    private $cssList;

    private $password;

    /**
     * @return array
     */
    public function getCssList(): array
    {
        return $this->cssList;
    }
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->cssList = [
            file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css'),
            file_get_contents(public_path('css/mail.css'))
        ];

        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.freesubscription')
            ->subject(sprintf("Confirmation d'inscription à %s", config('app.name')))
            ->with([
            'css' => implode(' ', $this->cssList),
            'action' => 'MailCtrl@freeSubscription',
            'password' => $this->password
        ]);
    }

    public function preview()
    {
        $this->build();
        
        return $this->buildView();
    }
}
