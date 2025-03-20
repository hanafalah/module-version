<?php

namespace Hanafalah\ModuleVersion\Concerns\Commands\Schema;

use Hanafalah\LaravelStub\Facades\Stub;

trait SchemaPrompt
{
    protected $__ask_schema_description, $__ask_app_name;
    protected $__ask_namespace;

    //INSTALLATION SCHEMA    
    protected function runModelSchema()
    {
        $location       = $this->askLocation();
        $namespace      = $this->argument('namespace');
        $schemaName     = $this->getClassName($namespace);
        $path           = $this->option('path') ?? app_path("Schemas");
        Stub::init($this->getModelSchemaStubPath(), [
            'NAMESPACE'          => $namespace,
            'CLASS_NAME'         => $schemaName,
            'LOWER_CLASS_NAME'   => strtolower($schemaName)
        ])->saveTo($path, "$schemaName.php");
    }

    protected function askAppName(): self
    {
        $this->__ask_app_name = $this->option('app-name') ?? $this->ask('Enter application name ?');
        return $this;
    }

    protected function askSchemaDescription(): self
    {
        $this->__ask_schema_description = $this->option('description');
        return $this;
    }
    //END INSTALLATION SCHEMA

    //MODEL SCHEMA
    /**
     * Ask the user for the namespace to be used for the schema.
     *
     * @return self The current instance of the class.
     */
    protected function askNamespace(): self
    {
        $this->__ask_namespace = $this->ask('Enter namespace ?');
        return $this;
    }

    protected function askLocation(): self
    {
        $location = $this->option('location-type') ?? $this->choice('Choose location type ?', ['Repository', 'Tenant']);
        switch ($location) {
            case 'Repository':
                $scans = \scandir(repository_path('/*/*'));
                $scans = array_diff($scans, ['.', '..']);
                $this->__ask_location = $this->choice('Choose repository ?', $scans);
                break;
        }
        // if ($location == '')
        // $this->__ask_location = $this->option()
        return $this;
    }
    //END MODEL SCHEMA

    /**
     * Get the class name from the given namespace.
     *
     * @param string $class_namespace The namespace of the class.
     *
     * @return string The class name.
     */
    protected function getClassName(string $class_namespace): string
    {
        $classname = explode('/', $class_namespace);
        $classname = end($classname);
        return str_replace("/" . $classname, '', $class_namespace);
    }

    public function callInstallationSchema(string $namespace): void
    {
        $this->call('moduleversion:add-installation-schema', [
            'namespace'            => $namespace,
            '--app-name'           => $namespace,
            '--description'        => "Installation schema for app " . $this->getAskAppResult()->name,
        ]);
    }

    protected function runInstallationSchema()
    {
        $this->askAppName()->askSchemaDescription();
        $namespace      = $this->argument('namespace');
        $schemaName     = $this->getClassName($namespace);
        $appName        = $this->getAskAppNameResult();
        $path           = app_path("Schemas");
        Stub::init($this->getInstallationSchemaStubPath(), [
            'NAMESPACE'          => $schemaName,
            'CLASS_NAME'         => $schemaName,
            'SCHEMA_DESCRIPTION' => $this->getAskSchemaDescriptionResult()
        ])->saveTo($path, "$schemaName.php");
    }
}
