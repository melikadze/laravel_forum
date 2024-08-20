<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Array $data;
    //

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
