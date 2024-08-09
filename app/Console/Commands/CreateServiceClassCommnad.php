<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateServiceClassCommnad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-service-class {model} {prefix} {module}'; // Todo: take the model as arguments for crud

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create as custom service class.';

    /**
     * Namespace.
     */
    private ?string $nameSpace = null;

    /**
     * Service class name.
     */
    private ?string $serviceClassName = null;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(private Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath()
    {
        return __DIR__ . '/stubs/service.stub';
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables()
    {
        return [
            'NAMESPACE'         => $this->getNamespace(),
            'CLASS'        => $this->getServiceClassName(),
        ];
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return base_path($this->getNamespace()) . '\\' . $this->getServiceClassName() . '.php';
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): string
    {
        is_null($this->nameSpace) ? $this->nameSpace = config('modules.namespace') . '\\' . $this->argument('module') . '\\Services' . '\\' . $this->argument('prefix') : $this->nameSpace;

        return $this->nameSpace;
    }

    /**
     * Get service class name
     */

    public function getServiceClassName(): string
    {
        is_null($this->serviceClassName) ? $this->serviceClassName = $this->argument('model') . 'Service' : $this->serviceClassName;

        return $this->serviceClassName;
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
