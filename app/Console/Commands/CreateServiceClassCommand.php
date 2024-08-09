<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Console\Commands\traits\CreateStubHelper;

class CreateServiceClassCommand extends Command
{
    use CreateStubHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-service-class {model} {prefix} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create as custom service class.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setStubPath(__DIR__ . '/stubs/service.stub');

        $this->setStubVariables([
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
        is_null($this->nameSpace) ? $this->nameSpace = config('modules.namespace') . '\\' . $this->argument('module') . '\\Services\\' . $this->argument('prefix') : $this->nameSpace;

        return $this->nameSpace;
    }

    /**
     * Get service class name
     */
    public function getClassName(): string
    {
        is_null($this->className) ? $this->className = $this->argument('model') . 'Service' : $this->className;

        return $this->className;
    }

    public function getModelImport(): string
    {
        return 'use ' . config('modules.namespace') . '\\' . $this->argument('module') . '\\Models\\' . $this->argument('model') . ';';
    }
}
