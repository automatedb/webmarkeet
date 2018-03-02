<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 01/03/18
 * Time: 10:29
 */

namespace App\Exceptions;


class MyFXBookException extends \Exception {
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}