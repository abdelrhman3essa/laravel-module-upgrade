<?php

namespace App\Console\Commands\CrudHelper;

use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class CrudCommandHelper
{
    public static string $moduleName;

    public static function projectHaveModulePackage(): bool
    {
        return strlen(config('modules.namespace')) > 0;
    }

    public static function projectHaveModule(): bool
    {
        return self::projectHaveModulePackage() && Module::has(static::$moduleName);
    }

    public static function createModule(): void
    {
        $createModuleCommand = 'module:make ' . static::$moduleName;

        static::callArtisan($createModuleCommand);
    }

    public static function setModuleName(string $name): void
    {
        static::$moduleName = $name;
    }

    public static function callArtisan(string $command): void
    {
        Artisan::call($command);
    }
}
