<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/28/17
 * Time: 4:57 PM
 */

namespace OzairP\Pterodactyl\Util;


use InvalidArgumentException;

class Util
{

    public static function expect ($arguments, $expectation, $exception = InvalidArgumentException::class)
    {
        foreach ($expectation as $key => $value) {
            $allowedTypes = explode("|", $value);
            if ((!in_array(@gettype($arguments[$key]), $allowedTypes))) throw new $exception("Expected {$key} to be type ${value} but got " . gettype($arguments[$key]));
        }
    }

}