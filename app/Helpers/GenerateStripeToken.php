<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 03/08/17
 * Time: 15:56
 */

namespace App\Helpers;


use App\Exceptions\InvalidCardException;
use Stripe\Error\Card;
use Stripe\Token;

class GenerateStripeToken
{
    public function getToken(string $firstname, string $lastname, string $numberCard, string $expMonth, string $expYear, string $cvc): string {
        try {
            $response = Token::create(array(
                "card" => array(
                    "number"    => $numberCard,
                    "exp_month" => $expMonth,
                    "exp_year"  => $expYear,
                    "cvc"       => $cvc,
                    "name"      => sprintf('%s %s', $firstname, $lastname)
                )));
        } catch (Card $e) {
            throw new InvalidCardException($e->getMessage());
        }

        return $response['id'];
    }
}