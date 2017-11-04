<?php
/**
 * Created by PhpStorm.
 * User: ozairpatel
 * Date: 10/27/17
 * Time: 11:04 PM
 */

namespace OzairP\Pterodactyl\User;


use Exception;
use InvalidArgumentException;
use OzairP\Pterodactyl\Conduit;
use OzairP\Pterodactyl\Util\PterodactylRequest;
use OzairP\Pterodactyl\Util\Util;

class User
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
	 * Get a user, if UID is null, all users will be fetched
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $uid
	 *
	 * @return object
	 */
	public static function get ($conduit, $uid = NULL)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(['uid' => $uid], [
			'uid' => 'NULL|integer',
		]);

		$uri = 'admin/users' . (($uid !== NULL) ? '/' . $uid : '');
		$request = new PterodactylRequest($conduit);
		$response = $request->to($uri)
							->send();

		return json_decode($response);
	}

	/**
	 * Create a user, fields should be what is followed
	 * in expectation
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
			'email'      => 'string',
			'username'   => 'string',
			'name_first' => 'string',
			'name_last'  => 'string',
			'root_admin' => 'boolean',
			'password'   => 'NULL|string',
			'custom_id'  => 'NULL|integer',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to('admin/users')
							->posts()
							->with($fields)
							->send();

		return json_decode($response);
	}

	/**
	 * Update a user, UID is the user's ID and fields
	 * are the same as create
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param integer                     $uid
	 * @param array                       $fields
	 *
	 * @return mixed
	 */
	public static function update ($conduit, $uid, $fields)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(array_merge($fields, ['uid' => $uid]), [
			'uid'        => 'integer',
			'email'      => 'NULL|string',
			'username'   => 'NULL|string',
			'name_first' => 'NULL|string',
			'name_last'  => 'NULL|string',
			'root_admin' => 'NULL|boolean',
			'password'   => 'NULL|string',
			'custom_id'  => 'NULL|integer',
		]);

		$request = new PterodactylRequest($conduit);
		$response = $request->to('admin/users/' . $uid)
							->puts()
							->with($fields)
							->send();

		return json_decode($response);
	}

	/**
	 * Delete a user
	 *
	 * @param \OzairP\Pterodactyl\Conduit $conduit
	 * @param int                         $uid
	 *
	 * @return mixed
	 */
	public static function delete ($conduit, $uid)
	{
		if (!($conduit instanceof Conduit)) {
			throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
		}
		Util::expect(['uid' => $uid], [
			'uid' => 'integer',
		]);

		// Ptero expects ID to be included in HMAC
		// in DELETE but not GET ¯\_(ツ)_/¯
		$request = new PterodactylRequest($conduit);
		$response = $request->to('admin/users/' . $uid)
							->with([
								'id' => $uid,
							])
							->deletes()
							->send();

		return json_decode($response);
	}

}