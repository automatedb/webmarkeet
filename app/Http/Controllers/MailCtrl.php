<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmed;

class MailCtrl extends Controller
{
    /**
     * @Get("/mail/subscription")
     */
    public function subscription() {
        $mail = new SubscriptionConfirmed();

        return view($mail->preview(), [
            'css' => implode(' ', $mail->getCssList())
        ]);
    }
}
