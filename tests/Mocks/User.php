<?php

namespace GridPrinciples\Friendly\Tests\Mocks;

use App\User as BaseUser;
use GridPrinciples\Friendly\Traits\Friendly;

class User extends BaseUser
{
    use Friendly;
    protected $morphClass = 'GridPrinciples\Friendly\Tests\Mocks\User';
}
