<?php

namespace Sumup\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class SumUp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sumup';
    }
}
