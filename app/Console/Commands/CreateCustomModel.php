<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\traits\CreateStubHelper;

class CreateCustomModel extends Command
{
    use CreateStubHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-custom-model {model} {fillable} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create as custom Model class.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setStubPath(__DIR__ . '/stubs/model.stub');

        $this->setStubVariables([
            'FILLABLE' => $this->getFillable(),
        ]);

        $this->createStub();
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): string
    {
        is_null($this->nameSpace) ? $this->nameSpace = config('modules.namespace') . '\\' . $this->argument('module') . '\\Models' : $this->nameSpace;

        return $this->nameSpace;
    }

    /**
     * Get service class name
     */
    public function getClassName(): string
    {
        is_null($this->className) ? $this->className = $this->argument('model') : $this->className;

        return $this->className;
    }

    private function getFillable(): string
    {
        return '\'' . implode("', '", explode(',', $this->argument('fillable'))) . '\'';
    }
}
