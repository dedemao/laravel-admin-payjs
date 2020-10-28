<?php
namespace Dedemao\Payjs\Facades;

use Illuminate\Support\Facades\Facade;

class ConfigService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dedemao\Payjs\Services\PayjsConfigService::class;
    }
}
