<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        putenv('APP_ENV=testing');
        $app['config']->set('database.default','sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        Hash::driver('bcrypt')->setRounds(4);

        return $app;
    }
}
