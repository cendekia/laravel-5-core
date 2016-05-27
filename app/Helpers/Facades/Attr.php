<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class Attr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'attr';
    }
}
