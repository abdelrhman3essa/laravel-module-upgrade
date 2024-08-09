<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\traits\CreateStubHelper;

class CustomRequestCommand extends Command
{
    use CreateStubHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request-class {request} {fillable} {prefix} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create as custom request class.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setStubPath(__DIR__ . '/stubs/request.stub');

        $this->setStubVariables([
            'RULES' => $this->getFillableRules(),
        ]);

        $this->createStub();
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): string
    {
        is_null($this->nameSpace) ? $this->nameSpace = config('modules.namespace') . '\\' . $this->argument('module') . '\\app\\Http\\Requests\\' . $this->argument('prefix') : $this->nameSpace;

        return $this->nameSpace;
    }

    /**
     * Get service class name
     */
    public function getClassName(): string
    {
        is_null($this->className) ? $this->className = $this->argument('request') : $this->className;

        return $this->className;
    }

    private function getFillableRules(): string
    {
        $fillable = explode(',', $this->argument('fillable'));

        $fillableWithRule = '';

        foreach ($fillable as $item) {
            $fillableWithRule .= "\t\t\t'{$item}' => ['required', 'string'], \n";
        }

        return $fillableWithRule;
    }
}
