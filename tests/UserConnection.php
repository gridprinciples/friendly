<?php

namespace GridPrinciples\Connectable\Tests;

use GridPrinciples\Connectable\Tests\Cases\UserTestCase;

class UserConnection extends UserTestCase
{
    public function test_users_can_be_connected()
    {
        $captain = $this->createUser([
            'name' => 'Jean-Luc Picard',
        ]);

        $first_officer = $this->createUser([
            'name' => 'William Riker',
        ]);

        $captain->connect($first_officer);
        $first_officer->approve($captain);

        $this->assertContains('William Riker', $captain->connections->lists('name'));
        $this->assertContains('Jean-Luc Picard', $first_officer->connections->lists('name'));

        $this->assertEquals('Carbon', class_basename($captain->connections->lists('created_at')->first()));
    }

    public function test_user_connections_can_hold_title()
    {
        $kid = $this->createUser([
            'name' => 'Greg',
        ]);

        $parent = $this->createUser([
            'name' => 'Robert',
        ]);

        $parent->connect($kid, [
            'name'       => 'Dad',
            'other_name' => 'Son',
        ]);

        $kid->approve($parent);

        $this->assertContains('Son', $kid->connections->lists('pivot.name'));
        $this->assertContains('Dad', $kid->connections->lists('pivot.other_name'));

        $this->assertContains('Dad', $parent->connections->lists('pivot.name'));
        $this->assertContains('Son', $parent->connections->lists('pivot.other_name'));
    }

    public function test_active_user_connections()
    {
        $band = $this->createUser([
            'name' => 'Pink Floyd',
        ]);

        $bassist = $this->createUser([
            'name' => 'Roger Waters',
        ]);

        $drummer = $this->createUser([
            'name' => 'Nick Mason',
        ]);

        $guitarist = $this->createUser([
            'name' => 'David Gilmour',
        ]);

        $singer = $this->createUser([
            'name' => 'Syd Barrett',
        ]);

        $band->connect($drummer);
        $band->connect($bassist, [
            'start' => '08-01-1967',
            'end'   => '01-01-2025',
        ]);
        $band->connect($guitarist, [
            'start' => '12-01-1967',
            'end'   => '07-02-2005',
        ]);
        $band->connect($singer, [
            'end' => '01-01-1968',
        ]);

        $drummer->approve($band);
        $bassist->approve($band);
        $guitarist->approve($band);
        $singer->approve($band);

        $activeMembers = $band->active_connections->lists('name');

        $this->assertContains('Nick Mason', $activeMembers);
        $this->assertContains('Roger Waters', $activeMembers);
        $this->assertNotContains('Syd Barrett', $activeMembers);
        $this->assertNotContains('David Gilmour', $activeMembers);
    }

    public function test_connection_soft_deletes()
    {
        $kid = $this->createUser([
            'name' => 'Luke',
        ]);

        $parent = $this->createUser([
            'name' => 'Darth',
        ]);

        $parent->connect($kid, [
            'name'       => 'Father',
            'other_name' => 'Son',
        ]);

        $kid->approve($parent);
        $kid->disconnect($parent);

        $parent->resetConnections();

        $this->seeInDatabase('connections', ['name' => 'Father']);
        $this->assertNotContains('Darth', $kid->connections->lists('name'));
        $this->assertNotContains('Luke', $parent->connections->lists('name'));
    }
}
