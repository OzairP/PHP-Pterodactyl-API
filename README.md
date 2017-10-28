# Pterodactyl API

```PHP
use OzairP\Pterodactyl\Conduit;
use OzairP\Pterodactyl\User\User;
 
include('vendor/autoload.php');
 
$conduit = new Conduit('PUBLIC', 'PRIVATE', 'http://HOSTNAME/api');
 
// Fetch all users
User::get($conduit);
 
// Fetch user with id 1
User::get($conduit, 1);
 
// Create a user
User::create($conduit, 'ozairpatel2@gmail.com', 'opatel', 'Ozair', 'Patel', null, true);
 
// Update a user
User::update($conduit, 2, array(
    'name_first' => 'Ohzair'
));
 
// Delete user
User::delete($conduit, 2);
```
