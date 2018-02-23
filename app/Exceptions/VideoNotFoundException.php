<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 23/02/18
 * Time: 10:16
 */

namespace App\Exceptions;


class VideoNotFoundException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}