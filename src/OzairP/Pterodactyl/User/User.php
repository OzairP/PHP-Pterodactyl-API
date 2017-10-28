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

    public static function get ($conduit, $uid = NULL)
    {
        if (!($conduit instanceof Conduit)) throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
        if ($uid !== NULL && !is_numeric($uid)) throw new InvalidArgumentException('Expected $uid to be type int or null');

        $uri = 'admin/users' . (($uid !== NULL) ? '/' . $uid : '');
        $request = new PterodactylRequest($conduit);
        $response = $request->to($uri)
                            ->send();

        return json_decode($response);
    }

    public static function create ($conduit, $email, $username, $name_first, $name_last, $password = NULL, $root_admin = FALSE, $custom_id = NULL)
    {
        if (!($conduit instanceof Conduit)) throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
        if (!is_string($email)) throw new InvalidArgumentException('Expected $email to be type string');
        if (!is_string($username)) throw new InvalidArgumentException('Expected $username to be type string');
        if (!is_string($name_first)) throw new InvalidArgumentException('Expected $name_first to be type string');
        if (!is_string($name_last)) throw new InvalidArgumentException('Expected $name_last to be type string');
        if ($password !== NULL && !is_string($password)) throw new InvalidArgumentException('Expected $password to be type string or null');
        if (!is_bool($root_admin)) throw new InvalidArgumentException('Expected $root_admin to be type string');
        if ($custom_id !== NULL && !is_numeric($password)) throw new InvalidArgumentException('Expected $custom_id to be type int or null');

        $payload = array(
            'email'      => $email,
            'username'   => $username,
            'name_first' => $name_first,
            'name_last'  => $name_last,
            'root_admin' => $root_admin,
        );

        if ($password !== NULL) $payload['password'] = $password;
        if ($custom_id !== NULL) $password['custom_id'] = $custom_id;

        $request = new PterodactylRequest($conduit);
        $response = $request->to('admin/users')
                            ->posts()
                            ->with($payload)
                            ->send();

        return json_decode($response);
    }

    public static function update ($conduit, $uid, $fields)
    {
        if (!($conduit instanceof Conduit)) throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
        if (!is_numeric($uid)) throw new InvalidArgumentException('Expected $uid to be type int');
        if (!is_array($fields)) throw new InvalidArgumentException('Expected $fields to be type array');

        $request = new PterodactylRequest($conduit);
        $response = $request->to('admin/users/' . $uid)
                            ->puts()
                            ->with($fields)
                            ->send();

        return json_decode($response);
    }

    public static function delete ($conduit, $uid)
    {
        if (!($conduit instanceof Conduit)) throw new InvalidArgumentException('Expected $conduit to be instanceof Conduit');
        if (!is_numeric($uid)) throw new InvalidArgumentException('Expected $uid to be type int');

        $request = new PterodactylRequest($conduit);
        $response = $request->to('admin/users/' . $uid)
                            ->with(array(
                                'id' => $uid,
                            ))
                            ->deletes()
                            ->send();

        return json_decode($response);
    }

}