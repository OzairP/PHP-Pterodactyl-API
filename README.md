# Pterodactyl API

## Features
* Agnostic
* Object oriented
* Expandable
* Well documented (soon)
* Easy to use

## Inspiration
No good wrappers for the Pterodactyl API with all the
kinks from bad documentation worked out.

## Install
**Unavailable on Packagist at the moment**

`composer require OzairP\Pterodactyl-API`

## Basic Usage

### Initialize
```PHP
// Namespaces here
 
include('vendor/autoload.php');
```

### Conduit
The Conduit object is your connection details
to your Pterodactyl system.
```PHP
use OzairP\Pterodactyl\Conduit;
 
$conduit = new Conduit('PUBLIC', 'PRIVATE', 'http://HOSTNAME/api');
```

### User Static Members
This wrapper provides Laravel-like Facades
```PHP
use OzairP\Pterodactyl\User\User;
 
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