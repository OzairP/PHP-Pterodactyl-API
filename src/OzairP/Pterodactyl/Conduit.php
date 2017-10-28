<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/27/17
 * Time: 11:07 PM
 */

namespace OzairP\Pterodactyl;


use InvalidArgumentException;

/**
 * Class Conduit
 * @package OzairP\Pterodactyl
 */
class Conduit
{

    private $public;

    private $private;

    private $url;

    /**
     * Conduit constructor.
     *
     * @param string $public  - Public key
     * @param string $private - Private key
     * @param string $url     - Root API url
     */
    public function __construct ($public, $private, $url)
    {
        if (!is_string($public)) throw new InvalidArgumentException('Expecting $public to be type string');
        if (!is_string($private)) throw new InvalidArgumentException('Expecting $private to be type string');
        if (!is_string($url)) throw new InvalidArgumentException('Expecting $url to be type string');
        if (!filter_var($url, FILTER_VALIDATE_URL)) throw new InvalidArgumentException('Expecting $url to pass FILTER_VALIDATE_URL filter');

        $this->public = $public;
        $this->private = $private;
        // Add a trailing slash if needed
        $this->url = $url . (substr($url, strlen($url) - 1, strlen($url)) !== '/' ? '/' : '');
    }

    /**
     * Sign and tokenize the payload.
     * It will automatically cast arrays
     * to a json string
     *
     * @param string|array $payload
     * @param null         $url
     *
     * @return string
     */
    public function token ($payload, $url)
    {
        if (is_array($payload)) $payload = json_encode($payload);
        if (!is_string($payload)) throw new InvalidArgumentException('Expecting $payload to be type string');

        return $this->tokenize($this->sign($payload, $url));
    }

    /**
     * Attach the public key to a signature
     *
     * @param string $hash
     *
     * @return string
     */
    public function tokenize ($hash)
    {
        if (!is_string($hash)) throw new InvalidArgumentException('Expecting $hash to be type string');

        return $this->public . "." . $hash;
    }

    /**
     * Sign payload data
     *
     * @param string $payload
     * @param null   $url
     *
     * @return string
     */
    public function sign ($payload, $url)
    {
        if (!is_string($payload)) throw new InvalidArgumentException('Expecting $payload to be type string');
        if (!is_string($url)) throw new InvalidArgumentException('Expecting $url to be type string');

        return base64_encode(hash_hmac('sha256', $url . $payload, $this->private, TRUE));
    }

    /**
     * Build a full URL to a path
     *
     * @param $path
     *
     * @return string
     */
    public function to ($path)
    {
        if (!is_string($path)) throw new InvalidArgumentException('Expecting $path to be type string');

        return $this->url . $path;
    }

    /**
     * @return string
     */
    public function getPublic ()
    {
        return $this->public;
    }

    /**
     * @param string $public
     *
     * @return Conduit
     */
    public function setPublic ($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrivate ()
    {
        return $this->private;
    }

    /**
     * @param string $private
     *
     * @return Conduit
     */
    public function setPrivate ($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl ()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Conduit
     */
    public function setUrl ($url)
    {
        $this->url = $url;

        return $this;
    }
}