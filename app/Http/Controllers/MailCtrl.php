<?php

namespace App\Http\Controllers;

use App\Mail\RenewalSubscriptionConfirmed;
use App\Mail\SubscriptionConfirmed;
use App\Mail\UnRegisterConfirmed;
use App\Mail\UnSubscribeConfirmed;

class MailCtrl extends Controller
{
    /**
     * @Get("/mail/subscription")
     */
    public function subscription() {
        $mail = new SubscriptionConfirmed();

        return view($mail->preview(), [
            'css' => implode(' ', $mail->getCssList()),
            'action' => 'MailCtrl@subscription'
        ]);
    }

    /**
     * @Get("/mail/unregister")
     */
    public function unregister() {
        $mail = new UnRegisterConfirmed();

        return view($mail->preview(), [
            'css' => implode(' ', $mail->getCssList()),
            'action' => 'MailCtrl@unregister'
        ]);
    }

    /**
     * @Get("/mail/unsubscribe")
     */
    public function unsubscribe() {
        $mail = new UnSubscribeConfirmed();

        return view($mail->preview(), [
            'css' => implode(' ', $mail->getCssList()),
            'action' => 'MailCtrl@unsubscribe'
        ]);
    }

    /**
     * @Get("/mail/renewal")
     */
    public function renewal() {
        $mail = new RenewalSubscriptionConfirmed();

        return view($mail->preview(), [
            'css' => implode(' ', $mail->getCssList()),
            'action' => 'MailCtrl@renewal'
        ]);
    }
}
