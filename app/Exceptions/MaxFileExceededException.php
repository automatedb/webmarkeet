<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 23/02/18
 * Time: 11:01
 */

namespace App\Exceptions;


class MaxFileExceededException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}