<?php

namespace GridPrinciples\Connectable\Tests\Cases;

use GridPrinciples\Connectable\Tests\Mocks\User;

abstract class UserTestCase extends DatabaseTestCase {

    protected $mockUserClass = User::class;

    protected function createUser($attributes = [])
    {
        $defaultAttributes = [
            'name' => 'Human Being',
            'email' => uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ];
        $attributes = array_merge($defaultAttributes, $attributes);

        // Mock up the assumed workflow: the app's User model `use`s the Connectable trait.
        $user = $this->getMockForAbstractClass($this->mockUserClass);

        $user->fill($attributes);
        $user->save();

        return $user;
    }
}
