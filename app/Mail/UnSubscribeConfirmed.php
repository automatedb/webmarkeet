<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnSubscribeConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    private $cssList;

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
    public function __construct()
    {
        $this->cssList = [
            file_get_contents(public_path('css/mail.css'))
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.unsubscribe')
            ->subject(sprintf("Confirmation d'annulation d'abonnement à %s", config('app.name')))
            ->with([
            'css' => implode(' ', $this->cssList)
        ]);
    }

    public function preview()
    {
        $this->build();
        
        return $this->buildView();
    }
}
