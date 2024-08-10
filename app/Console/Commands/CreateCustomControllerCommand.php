<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Console\Commands\traits\CommandHelper;
use App\Console\Commands\traits\CreateStubHelper;

class CreateCustomControllerCommand extends Command
{
    use CreateStubHelper, CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller-class {model} {prefix} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create as custom controller class.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setStubPath(__DIR__ . '/stubs/controller.stub');

        $this->setStubVariables([
            'SERVICE_IMPORT' => $this->getServiceImport(),
            'STORE_REQUEST_IMPORT' => $this->getStoreRequestImport(),
            'UPDATE_REQUEST_IMPORT' => $this->getUpdateRequestImport(),
            'SERVICE' => $this->service(),
            'CAMEL_SERVICE' => Str::camel($this->service()),
            'STORE_REQUEST' => $this->storeRequest(),
            'UPDATE_REQUEST' =>  $this->updateRequest(),
            'MODEL_IMPORT' => $this->getModelImport(),
            'MODEL' => $this->argument('model'),
            'CAMEL_MODEL' => '$' . Str::camel($this->argument('model')),
        ]);

        $this->createStub();
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): string
    {
        is_null($this->nameSpace) ? $this->nameSpace = config('modules.namespace') . '\\' . $this->argument('module') . '\\app\\Http\\Controllers\\' . $this->argument('prefix') : $this->nameSpace;

        return $this->nameSpace;
    }

    /**
     * Get service class name
     */
    public function getClassName(): string
    {
        is_null($this->className) ? $this->className = $this->argument('model') . 'Controller' : $this->className;

        return $this->className;
    }
}
