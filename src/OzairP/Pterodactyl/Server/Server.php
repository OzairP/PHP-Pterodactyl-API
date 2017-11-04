<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/27/17
 * Time: 11:04 PM
 */

namespace OzairP\Pterodactyl\Server;


use Exception;
use InvalidArgumentException;
use OzairP\Pterodactyl\Conduit;
use OzairP\Pterodactyl\Util\PterodactylRequest;
use OzairP\Pterodactyl\Util\Util;

/**
 * Class Server
 * @package OzairP\Pterodactyl\Server
 */
class Server
{

	/**
	 * User constructor.
	 * @throws \Exception
	 */
	public function __construct ()
	{
		// This class is a factory to build the ActiveUser class
		// do not instantiate this
		throw new Exception('Instantiated a factory');
	}

	/**
	 * Fetch a server, if $sid is not set
	 * it will fetch all users
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $sid
	 *
	 * @return mixed
	 */
	public static function get ($conduit, $sid = NULL)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(['sid' => $sid], [
			'sid' => 'NULL|integer',
		]);

		$uri = 'admin/servers' . (($sid !== NULL) ? '/' . $sid : '');
		$request = new PterodactylRequest($conduit);
		$response = $request->to($uri)
							->send();

		return json_decode($response);
	}

	/**
	 * Create a user, fields should match expectation
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param array                       $fields
	 *
	 * @return mixed
	 */
	public static function create ($conduit, $fields)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect($fields, [
			'name'                  => 'string',
			'user_id'               => 'integer',
			'description'           => 'string',
			'location_id'           => 'integer',
			'node_id'               => 'NULL|number',
			'allocation_id'         => 'NULL|boolean',
			'allocation_additional' => 'NULL|array',
			'memory'                => 'integer',
			'swap'                  => 'integer',
			'disk'                  => 'integer',
			'cpu'                   => 'integer',
			'io'                    => 'integer',
			'service_id'            => 'integer',
			'option_id'             => 'integer',
			'startup'               => 'string',
			'auto_deploy'           => 'NULL|boolean',
			'pack_id'               => 'NULL|integer',
			'custom_id'             => 'NULL|integer',
			'custom_container'      => 'NULL|string',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to('admin/servers')
							->posts()
							->with($fields)
							->send();

		return json_decode($response);
	}

	/**
	 * **This method requires fix 1 & 2**
	 * Update a servers config/details. The fields
	 * are listed in the expectation
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $sid
	 * @param array                       $fields
	 *
	 * @return mixed
	 */
	public static function updateConfig ($conduit, $sid, $fields)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		$fields = array_merge($fields, ['sid' => $sid]);
		Util::expect($fields, [
			'sid'         => 'integer',
			'owner_id'    => 'NULL|integer',
			'description' => 'NULL|string',
			'name'        => 'NULL|string',
			'reset_token' => 'NULL|boolean',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to("admin/servers/{$sid}/details")
							->puts()
							->with($fields)
							->send();

		return json_decode($response);
	}

	/**
	 * **This method requires fixes 1 & 2**
	 * Update the build details of a server.
	 * The fields are listed in the expectation
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $sid
	 * @param array                       $fields
	 *
	 * @return mixed
	 */
	public static function updateBuild ($conduit, $sid, $fields)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(array_merge($fields, ['sid' => $sid]), [
			'sid'                => 'integer',
			'allocation_id'      => 'NULL|integer',
			'add_allocation'     => 'NULL|array',
			'remove_allocations' => 'NULL|array',
			'memory'             => 'NULL|integer',
			'swap'               => 'NULL|integer',
			'disk'               => 'NULL|integer',
			'cpu'                => 'NULL|integer',
			'io'                 => 'NULL|integer',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to("admin/servers/{$sid}/build")
							->puts()
							->with($fields)
							->send();

		return json_decode($response);
	}

	/**
	 * Unsuspend a server
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param integer                     $sid
	 *
	 * @return mixed
	 */
	public static function unsuspend ($conduit, $sid)
	{
		return self::suspend($conduit, $sid, 'unsuspend');
	}

	/**
	 * Suspend a server.
	 * $action can either be 'suspend' or 'unsuspend'
	 * It is defaulted to `suspend`.
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $sid
	 * @param string                      $action
	 *
	 * @return mixed
	 */
	public static function suspend ($conduit, $sid, $action = 'suspend')
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(['sid' => $sid], [
			'sid' => 'integer',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to("admin/servers/{$sid}/suspend")
							->patchs()
							->with([
								'id'     => $sid,
								'action' => $action,
							])
							->send();

		return json_decode($response);
	}

	/**
	 * Delete a server, force is defaulted to false
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $sid
	 * @param bool                        $force
	 *
	 * @return mixed
	 */
	public static function delete ($conduit, $sid, $force = FALSE)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect([
			'sid'   => $sid,
			'force' => $force,
		], [
			'sid'   => 'integer',
			'force' => 'boolean',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to("admin/servers/{$sid}")
							->with([
								'id'           => $sid,
								'force_delete' => $force,
							])
							->deletes()
							->send();

		return json_decode($response);
	}

}