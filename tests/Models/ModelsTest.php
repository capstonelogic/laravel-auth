<?php namespace CapstoneLogic\Auth\Tests\Models;

use CapstoneLogic\Auth\Tests\TestCase;

abstract class ModelsTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->migrate();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
