<?php

namespace App\Seeds;

class Seeder
{
    public function run()
    {
        var_dump('Ok');
    }
}

if (php_sapi_name() === 'cli') {
    $seeder = new Seeder();
    $seeder->run();
}
