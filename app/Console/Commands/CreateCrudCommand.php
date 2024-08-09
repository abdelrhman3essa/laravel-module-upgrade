<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\CrudHelper\CrudCommandHelper;

class CreateCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD.';

    public function __construct(private CrudCommandHelper $crudCommandHelper)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->crudCommandHelper->projectHaveModulePackage()) {
            return $this->error('Your project does not have modules package. Run "composer require nwidart/laravel-modules" to install it.');
        }

        $this->getModuleName()
            ->getModelName()
            ->getModelFillable()
            ->getPrefix();

        $this->info('Creating the crud files ...');

        $this->crudCommandHelper->createCrudFiles();

        $this->showMessages($this->crudCommandHelper->getMessages());

        $this->info('Crud files created successfully ...');
    }

    private function getModuleName(): self
    {
        $moduleName = $this->ask('Your Module name?');

        $this->crudCommandHelper->setModuleName($moduleName);

        return $this;
    }

    private function getModelName(): self
    {
        $this->crudCommandHelper->setModelName($this->ask('Your Model name?'));

        return $this;
    }

    private function getModelFillable(): self
    {
        $this->crudCommandHelper->setModelFillable($this->ask('Your Model fillable?'));

        return $this;
    }

    private function getPrefix(): self
    {
        $this->crudCommandHelper->setPrefix($this->ask('Your Model prefix?'));

        return $this;
    }

    private function showMessages(array $messages): void
    {
        foreach ($messages as $message) {
            $this->info($message);
        }
    }
}
