# Friendly [![Build Status](https://travis-ci.org/gridprinciples/friendly.svg?branch=master)](https://travis-ci.org/gridprinciples/friendly)

A [Laravel 5.1](http://laravel.com/docs/5.1) package designed to enhance Eloquent users with connections between users,
including approvals by both users and additional data.

**Friendly** includes many shortcuts to creating one's own Friend system, but the developer is free to implement
the details however they require.

## Installation
1. Run `composer require gridprinciples/friendly` from your project directory.
1. Add the following to the `providers` array in `config/app.php`:  
    ```php
    GridPrinciples\Friendly\Providers\FriendlyServiceProvider::class,
    ```

1. Publish the migrations and config file:  
    ```
    php artisan vendor:publish --provider="GridPrinciples\Friendly\Providers\FriendlyServiceProvider"
    ```
    
1. Run the migrations:  
    ```
    php artisan migrate
    ```  
    This will add a `friends` table to your database to track relationships between models.
    
## Usage

**Add the `Friendly` trait to your User model:**      
    `use Friendly;`

You may now relate users to each other:

```php

// Using the "requesting" user model...
$dad = User::where('name', 'darth')->first();

// ...and the one receiving the friend request...
$kid = User::where('name', 'luke')->first();

// may include "pivot" data.
$dad->befriend($kid, [
    'name'       => 'Father',
    'other_name' => 'Son',
    'start'      => '1980-05-21',
]);

// ...and later, the secondary user approves the request:
$kid->approve($dad);

// At this point, either user is associated with the other via their `friends` attribute (a Collection):
$relatives = $kid->friends->toArray();

// Either user may sever the relationship, resulting in a type of block:
$kid->block($dad);

```

### Pivot Data

The included implementation table includes the following additional information about each friend request:

- `name`: Describes the requesting user's relationship with the secondary user.
- `other_name`: Describes the secondary user's relationship with the requesting user.
- `start`: When the relationship started, or will start.
- `end`: When the relationship 

### Reloading the Connections

When time you access the "friends" property on a user Model, the connections will be loaded and cached.  If you
make changes to a different relationship which would affect a loaded user's friends, you might need to reload them:
```php
$user->resetFriends();
```

