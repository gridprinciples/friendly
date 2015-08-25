<?php

namespace GridPrinciples\Connectable\Tests\Mocks;

use App\User as BaseUser;
use GridPrinciples\Connectable\Traits\Friendable;

class User extends BaseUser
{
    use Friendable;
    protected $morphClass = 'GridPrinciples\Connectable\Tests\Mocks\User';
}
