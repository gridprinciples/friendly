# Friendly [![Build Status](https://travis-ci.org/gridprinciples/friendly.svg?branch=master)](https://travis-ci.org/gridprinciples/friendly)

A [Laravel 5.1](http://laravel.com/docs/5.1) package designed to enhance Eloquent users with connections between users,
including approvals by both users and additional connection data.

**Connectable** includes many shortcuts to creating one's own Friend system, but the developer is free to implement
the details however they require.

## Installation
1. Run `composer require gridprinciples/connectable` from your project directory.
1. Add the following to the `providers` array in `config/app.php`:  
    `GridPrinciples\Friendly\Providers\ConnectableServiceProvider::class,`

1. Publish the migrations and config file:  
    `php artisan vendor:publish --provider="GridPrinciples\Friendly\Providers\ConnectableServiceProvider"`
    
1. Run the migrations:  
    `php artisan migrate`  
    This will add a `connections` table to your database to track relationships between models.
    
## Usage

**Add the `Connectable` trait to your User model:**      
    `use Connectable;`

You may now relate users to each other:

```php

// Using the "requesting" user model...
$dad = User::where('name', 'darth')->first();

// ...and the one receiving the connection request...
$kid = User::where('name', 'luke')->first();

// may include "pivot" data.
$dad->connect($kid, [
    'name'       => 'Father',
    'other_name' => 'Son',
    'start'      => '1980-05-21',
]);

// ...and later, the secondary user approves the request:
$kid->approve($dad);

// At this point, either user is associated with the other via their connections attribute (a Collection):
$relatives = $kid->connections->toArray();

// Either user may sever the relationship, resulting in a type of block:
$kid->disconnect($dad);

```

### Pivot Data

The included implementation table includes the following additional information about each connection:

- `name`: Describes the requesting user's relationship with the secondary user.
- `other_name`: Describes the secondary user's relationship with the requesting user.
- `start`: When the relationship started, or will start.
- `end`: When the relationship 

### Reloading the Connections

When time you access the "connections" property on a user Model, the connections will be loaded and cached.  If you
make changes to a different relationship which would affect a loaded user's connections, you might need to reload them:
```php
$user->resetConnections();
```

