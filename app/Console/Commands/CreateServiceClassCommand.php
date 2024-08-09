<?php

namespace App\Console\Commands;

use App\Console\Commands\traits\CreateStubHelper;
use Illuminate\Console\Command;

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
            //
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
}
