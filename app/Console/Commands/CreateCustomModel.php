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
            'CASTS' => $this->getCasts(),
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
        $fillable = explode(',', $this->argument('fillable'));

        $preparedFillable = '';

        foreach ($fillable as $item) {
            $preparedFillable .= "\t\t'{$item}', \n";
        }

        return $this->removeExtraLine($preparedFillable);
    }

    private function getCasts(): string
    {
        $preparedCasts = '';

        $fillable = explode(',', $this->argument('fillable'));

        foreach ($fillable as $item) {
            $preparedCast = $this->prepareCast($item);
            is_null($preparedCast) ?: $preparedCasts .= $preparedCast;
        }

        return $this->removeExtraLine($preparedCasts);
    }

    private function prepareCast(string $item): ?string
    {
        return match(true) {
            str_ends_with($item, '_at') => "\t\t\t'{$item}' => 'datetime',\n",
            str_starts_with($item, 'is_') => "\t\t\t'{$item}' => 'boolean',\n",
            str_contains($item, 'password') => "\t\t\t'{$item}' => 'hashed',\n",
            default => null,
        };
    }
}
