<?php

namespace App\Console\Commands\CrudHelper;

use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class CrudCommandHelper
{
    public string $moduleName;
    public string $modelName;
    public string $prefix;
    public array $modelFillable;

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

    public function setModelName(string $name): void
    {
        $this->modelName = $name;
    }

    public function setModelFillable(string $fillable): void
    {
        $this->modelFillable = explode(',', $fillable);
    }

    public function setPrefix(string $prefix): void
    {
        $fullPrefix = '';

        if (strlen($prefix) > 0) {
            $fullPrefix .= ucfirst($prefix) . '/';
        }

        $fullPrefix .=  $this->moduleName;

        $this->prefix = $fullPrefix;
    }

    public function createCrudFiles(): void
    {
        $this->projectHaveModule() ?: $this->createModule();

        $this->createRequests()
            ->createController()
            ->createFactory()
            ->createSeeder();
    }

    public function createRequests(): self
    {
        $storeRequestCommand = 'module:make-request ' . $this->prefix . '/Store' . $this->modelName . 'Request ' . $this->moduleName;
        $updateRequestCommand = 'module:make-request ' . $this->prefix . '/Update' . $this->modelName . 'Request ' . $this->moduleName;

        $this->callArtisan($storeRequestCommand);
        $this->callArtisan($updateRequestCommand);

        return $this;
    }

    public function createController(): self
    {
        $createControllerCommand = 'module:make-controller ' . $this->prefix . '/' . $this->modelName . 'Controller ' . $this->moduleName;

        $this->callArtisan($createControllerCommand);

        return $this;
    }

    public function createModel(): self
    {
        $createModelCommand = 'module:make-model ' . $this->modelName . ' -m --fillable=' . implode(',', $this->modelFillable) . ' ' . $this->moduleName;

        $this->callArtisan($createModelCommand);

        return $this;
    }

    public function createSeeder(): self
    {
        $createSeederCommand = 'module:make-seed ' . $this->moduleName . 'Seeder';

        $this->callArtisan($createSeederCommand);

        return $this;
    }

    public function createFactory(): self
    {
        $createSeederCommand = 'module:make-factory ' . $this->moduleName . 'Factory';

        $this->callArtisan($createSeederCommand);

        return $this;
    }

    public function callArtisan(string $command): void
    {
        Artisan::call($command);
    }
}
