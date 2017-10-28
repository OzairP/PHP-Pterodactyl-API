<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/27/17
 * Time: 11:22 PM
 */

namespace OzairP\Pterodactyl\Util;


use InvalidArgumentException;

class Request
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';

    public $headers;

    protected $curl;

    protected $url = "";

    protected $payload = '';

    protected $returns = TRUE;

    protected $return_mime;

    protected $method = self::GET;

    public function __construct ()
    {
        $this->curl = curl_init();
        $this->headers = new HeaderBag;

        $this->init();
    }

    protected function init ()
    {
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($this->curl, CURLOPT_VERBOSE, FALSE);
    }

    public function to ($url)
    {
        $this->url = $url;

        return $this;
    }

    public function with ($payload)
    {
        if (!is_array($payload)) throw new InvalidArgumentException('Expected $payload to be type array');

        $this->payload = $payload;

        return $this;
    }

    public function returns ($val = TRUE)
    {
        $this->returns = $val;

        return $this;
    }

    public function expect ($mime)
    {
        $this->return_mime = $mime;
    }

    public function gets ()
    {
        $this->method = self::GET;

        return $this;
    }

    public function posts ()
    {
        $this->method = self::POST;

        return $this;
    }

    public function patchs ()
    {
        $this->method = self::PATCH;

        return $this;
    }

    public function puts ()
    {
        $this->method = self::PUT;

        return $this;
    }

    public function deletes ()
    {
        $this->method = self::DELETE;

        return $this;
    }

    public function send ()
    {
        $this->headers->add('Content-Type', (is_array($this->payload)) ? 'application/json' : 'text/plain');

        if ($this->method === self::GET) {
            if (is_array($this->payload)) foreach ($this->payload as $key => $value) {
                $query = parse_url($this->url, PHP_URL_QUERY);
                $this->url .= (($query) ? '&' : '?') . $key . '=' . $value;
            }
        }
        else curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->payload));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->returns);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers->serialize());
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        $response = curl_exec($this->curl);
        curl_close($this->curl);

        return $response;
    }

}

