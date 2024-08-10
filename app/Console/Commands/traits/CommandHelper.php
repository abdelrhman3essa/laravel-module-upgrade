<?php

namespace App\Console\Commands\traits;

trait CommandHelper
{
    private function removeExtraLine(string $item): string
    {
        return rtrim($item, "\n");
    }

    private function modelNamespace(string $model = ''): string
    {
        return $this->namespacePrefix() . '\\Models' . $model;
    }

    private function getModelImport(): string
    {
        return 'use ' . $this->modelNamespace('\\' . $this->argument('model')) . ';';
    }

    private function serviceNamespace(string $service = ''): string
    {
        return $this->namespacePrefix() . '\\Services' . $service;
    }

    private function getServiceImport(): string
    {
        return 'use ' . $this->serviceNamespace('\\' . $this->argument('prefix') .'\\'. $this->service()) . ';';
    }

    private function requestNamespace(string $request = ''): string
    {
        return $this->namespacePrefix() . '\\Http\\Requests\\' . $this->argument('prefix') . $request;
    }

    private function getStoreRequestImport(): string
    {
        return 'use ' . $this->requestNamespace('\\' . $this->storeRequest()) . ';';
    }

    private function getUpdateRequestImport(): string
    {
        return 'use ' . $this->requestNamespace('\\' . $this->updateRequest()) . ';';
    }

    private function namespacePrefix(): string
    {
        return config('modules.namespace') . '\\' . $this->argument('module');
    }

    private function storeRequest(): string
    {
        return 'Store' . $this->argument('model') . 'Request';
    }

    private function updateRequest(): string
    {
        return 'Update' . $this->argument('model') . 'Request';
    }

    private function service(): string
    {
        return $this->argument('model') . 'Service';
    }
}
