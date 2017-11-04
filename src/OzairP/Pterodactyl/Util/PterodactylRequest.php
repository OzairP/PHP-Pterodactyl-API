<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/28/17
 * Time: 2:33 AM
 */

namespace OzairP\Pterodactyl\Util;


use InvalidArgumentException;
use OzairP\Pterodactyl\Conduit;

class PterodactylRequest extends Request
{

	protected $conduit;

	public function __construct ($conduit)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}

		$this->conduit = $conduit;

		parent::__construct();
	}

	public function to ($url)
	{
		return parent::to($this->conduit->to($url));
	}

	public function send ()
	{
		$this->headers->add('Authorization', 'Bearer ' . $this->conduit->token($this->payload, $this->url));

		return parent::send();
	}

}