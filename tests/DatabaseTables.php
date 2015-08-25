<?php

namespace GridPrinciples\Friendly\Tests;

use GridPrinciples\Friendly\Tests\Cases\DatabaseTestCase;
use Illuminate\Support\Facades\Schema;

class DatabaseTables extends DatabaseTestCase
{
    /**
     * Ensures the migrations ran and tables exist in the database.
     */
    public function test_tables_exist()
    {
        $expectedTables = [
            'friends',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(Schema::hasTable($table));
        }
    }
}
