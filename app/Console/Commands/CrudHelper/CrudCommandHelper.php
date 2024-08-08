<?php

namespace App\Console\Commands\CrudHelper;

use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class CrudCommandHelper
{
    public string $moduleName;

    public function projectHaveModulePackage(): bool
    {
        return strlen(config('modules.namespace')) > 0;
    }

    public function projectHaveModule(): bool
    {
        return self::projectHaveModulePackage() && Module::has($this->moduleName);
    }

    public function createModule(): void
    {
        $createModuleCommand = 'module:make ' . $this->moduleName;

        $this->callArtisan($createModuleCommand);
    }

    public function setModuleName(string $name): void
    {
        $this->moduleName = $name;
    }

    public function callArtisan(string $command): void
    {
        Artisan::call($command);
    }
}
