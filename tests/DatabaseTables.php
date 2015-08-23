<?php

namespace GridPrinciples\Connectable\Tests;

use GridPrinciples\Connectable\Tests\Cases\DatabaseTestCase;
use Illuminate\Support\Facades\Schema;

class DatabaseTables extends DatabaseTestCase
{
    /**
     * Ensures the migrations ran and tables exist in the database.
     */
    public function test_tables_exist()
    {
        $expectedTables = [
            'connections',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(Schema::hasTable($table));
        }
    }
}
