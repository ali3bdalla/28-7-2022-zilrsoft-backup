<?php

namespace Tests;

use App\Models\Manager;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function createManager(array $attributes = []): Manager
    {
        return Manager::factory($attributes)->create();
    }
}
