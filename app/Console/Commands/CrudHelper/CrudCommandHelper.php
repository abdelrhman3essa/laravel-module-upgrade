<?php

namespace App\Console\Commands\CrudHelper;

use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class CrudCommandHelper
{
    private string $moduleName;
    private string $modelName;
    private string $prefix;
    private array $messages = [];
    private array $modelFillable;

    public function projectHaveModulePackage(): bool
    {
        return strlen(config('modules.namespace')) > 0;
    }

    private function projectHaveModule(): bool
    {
        return self::projectHaveModulePackage() && Module::has($this->moduleName);
    }

    private function createModule(): void
    {
        $createModuleCommand = 'module:make ' . $this->moduleName;

        $this->callArtisan($createModuleCommand);

        $this->setMessage('Module: ' . $this->moduleName . ' Created.');
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

        $fullPrefix .=  $this->modelName;

        $this->prefix = $fullPrefix;
    }

    public function createCrudFiles(): void
    {
        $this->projectHaveModule() ?: $this->createModule();

        $this->createRequests()
            ->createModel()
            ->createService()
            ->createController()
            ->createFactory()
            ->createSeeder();
    }

    private function createRequests(): self
    {
        return $this->createStoreRequest()->createUpdateRequest();
    }

    private function createStoreRequest(): self
    {
        $requestName = 'Store' . $this->modelName . 'Request ';

        $storeRequestCommand = $requestName . $this->stringFillable() . ' ' . $this->preparedPrefix() . ' ';

        $this->callArtisan('module:make-request-class ' . $storeRequestCommand . $this->moduleName);

        $this->setMessage('Store Request: ' . $this->namespacePrefix() . '/Http/Requests/' . $this->prefix . '/' . $requestName . '.');

        return $this;
    }

    private function createUpdateRequest(): self
    {
        $requestName = 'Update' . $this->modelName . 'Request ';

        $updateRequestCommand = $requestName . $this->stringFillable() . ' ' . $this->preparedPrefix() . ' ';

        $this->callArtisan('module:make-request-class ' . $updateRequestCommand . $this->moduleName);

        $this->setMessage('Update Request: ' . $this->namespacePrefix() . '/Http/Requests/' . $this->prefix . '/' . $requestName . '.');

        return $this;
    }

    private function createController(): self
    {
        $createControllerCommand = $this->prefix . '/' . $this->modelName . 'Controller ';

        $this->callArtisan('module:make-controller ' . $createControllerCommand . $this->moduleName);

        $this->setMessage('Controller ' . $createControllerCommand . ' Created.');

        return $this;
    }

    private function createModel(): self
    {
        $createModelCommand = $this->modelName . ' ' . $this->stringFillable() . ' ';

        $this->callArtisan('module:make-custom-model ' . $createModelCommand . $this->moduleName);

        $this->setMessage('Model ' . $this->modelName . ' Created.');

        return $this;
    }

    private function createSeeder(): self
    {
        $createSeederCommand = $this->modelName . 'Seeder ';

        $this->callArtisan('module:make-seed ' . $createSeederCommand . $this->moduleName);

        $this->setMessage('Seeder ' . $createSeederCommand . ' Created.');

        return $this;
    }

    private function createFactory(): self
    {
        $createFactoryCommand = $this->modelName . 'Factory ';

        $this->callArtisan('module:make-factory ' . $createFactoryCommand . $this->moduleName);

        $this->setMessage('Factory ' . $createFactoryCommand . ' Created.');

        return $this;
    }

    private function createService(): self
    {
        $this->callArtisan('module:make-service-class ' . $this->modelName . ' ' . $this->preparedPrefix() . ' ' . $this->moduleName);

        $this->setMessage('Service ' . $this->moduleName . '/Services/' . $this->prefix . $this->modelName . 'Service' . ' Created.');

        return $this;
    }

    private function callArtisan(string $command): void
    {
        Artisan::call($command);
    }

    private function setMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    private function stringFillable(): string
    {
        return implode(',', $this->modelFillable);
    }

    private function preparedPrefix(): string
    {
        return str_replace('/', '\\\\', $this->prefix);
    }

    private function namespacePrefix(): string
    {
        return config('modules.namespace') . '/' . $this->moduleName;
    }
}
