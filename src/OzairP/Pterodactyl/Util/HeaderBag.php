<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/27/17
 * Time: 11:40 PM
 */

namespace OzairP\Pterodactyl\Util;

use InvalidArgumentException;

/**
 * Class HeaderBag
 * @package OzairP\Pterodactyl\Util
 */
class HeaderBag
{

    /**
     * @var array
     */
    private $headers = array();

    /**
     * Add a header to the headers
     *
     * @param string                         $key
     * @param string|int|float|boolean|array $value
     */
    function add ($key, $value)
    {
        if (!is_string($key)) throw new InvalidArgumentException('Expecting $key to be type string');

        if (is_array($value)) $value = json_encode($value);
        else $value = strval($value);

        if (!is_string($key)) throw new InvalidArgumentException('Attempted to cast $value to string but failed');

        $this->headers[$key] = $value;
    }

    /**
     * Remove a header
     *
     * @param string $key
     */
    function remove ($key)
    {
        unset($this->headers[$key]);
    }

    /**
     * Check if a header exists
     *
     * @param $key
     */
    function has ($key)
    {
        array_key_exists($key, $this->headers);
    }

    /**
     * Get the headers
     * @return array
     */
    function getHeaders ()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    function serialize ()
    {
        $array = array();
        foreach ($this->headers as $key => $value) {
            $array[] = $key . ": " . $value;
        }

        return $array;
    }

}