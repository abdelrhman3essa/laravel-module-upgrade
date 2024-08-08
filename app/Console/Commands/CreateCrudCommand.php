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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!CrudCommandHelper::projectHaveModulePackage()) {
            return $this->error('Your project does not have modules package. Run "composer require nwidart/laravel-modules" to install it.');
        }

        $this->getModuleName();
    }

    private function getModuleName()
    {
        $moduleName = $this->ask('Your Module name?');

        CrudCommandHelper::setModuleName($moduleName);

        if (!CrudCommandHelper::projectHaveModule()) {
            CrudCommandHelper::createModule();
            $this->info('Module created successfully.');
        }
    }
}
