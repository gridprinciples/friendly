<?php

namespace GridPrinciples\Connectable\Tests\Mocks;

use App\User as BaseUser;
use GridPrinciples\Connectable\Traits\Connectable;

class User extends BaseUser
{
    use Connectable;
    protected $morphClass = 'GridPrinciples\Connectable\Tests\Mocks\User';
}
