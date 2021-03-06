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

## Requirements
* Pterodactyl 0.6.4
* PHP 5.6+
* PHP ext-curl

## Install
**Unavailable on Packagist at the moment**

`composer require OzairP\Pterodactyl-API`

## Basic Usage

### Initialize
```PHP
// Namespaces here
 
include('vendor/autoload.php');
```

### Fixes
Some methods require fixing the Pterodactyl code itself.
These can be find on the wiki at https://github.com/OzairP/PHP-Pterodactyl-API/wiki/Edits


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
User::create($conduit, [
    'email' => 'ozairpatel3@gmail.com',
    'username' => 'opatel1',
    'name_first' => 'Ozair',
    'name_last' => 'Patel',
    'root_admin' => true
]);
 
// Update a user
User::update($conduit, 4, [
    'name_first' => 'ohzair'
]);
  
// Delete user
User::delete($conduit, 2);
```