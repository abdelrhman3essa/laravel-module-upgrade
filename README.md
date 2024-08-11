# Laravel Module CRUD Command

This is a Laravel project that have custom command to create CRUD file in laravel module.

## Installation

1. Install:

    ```bash
    composer install
    ```
2. Run:

    ```bash
    cp .env.example .env
    php artisan key:generate
    composer require nwidart/laravel-modules
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
    composer dump-autoload
    php artisan migrate
    ```
3. Run command:

    ```bash
    php artisan make:crud
    ```
