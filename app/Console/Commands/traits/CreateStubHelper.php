<?php

namespace App\Console\Commands\traits;

use Illuminate\Filesystem\Filesystem;

trait CreateStubHelper
{
    /**
     * Namespace.
     */
    private ?string $nameSpace = null;

    /**
     * Class name.
     */
    private ?string $className = null;

    /**
     * Stub path.
     */
    private ?string $stubPath = null;

    /**
     * Stub Variables.
     */
    private array $stubVariables = [];

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(private Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Create Stub.
     */
    public function createStub()
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
        return $this->stubPath;
    }

    /**
     * Set stub file path
     * @return string
     *
     */
    public function setStubPath($path)
    {
        $this->stubPath = $path;

        return $this->stubPath;
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
        return $this->stubVariables;
    }

    /**
     **
     * Set Stub Variables
     *
     * @return array
     *
     */
    public function setStubVariables(array $stubVariables)
    {
        $this->stubVariables = $stubVariables + [
            'NAMESPACE' => str_replace('\\app', '', $this->getNamespace()),
            'CLASS'     => $this->getClassName(),
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
        return base_path($this->getNamespace()) . '\\' . $this->getClassName() . '.php';
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
