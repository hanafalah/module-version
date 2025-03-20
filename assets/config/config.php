<?php

use Hanafalah\ModuleVersion\Models as ModuleVersionModel;
use Hanafalah\ModuleVersion\Commands as Commands;

return [
    'application'         => [
        /**
         * pattern for versioning, you can use 1.^, 1.0.^, 1.0.0, 
         * but avoid using 1.0.0 because it will make schema installation become not optimal
         */
        'version_pattern' => '1.^',
        'namespace'       => env('APPLICATION_NAMESPACE', 'App/MicroTenant'),
        'path'            => env('APPLICATION_PATH', 'app/MicroTenant'),
        'generate'        => [
            'migration'       => ['path' => 'Database/Migrations', 'generate' => true],
            'model'           => ['path' => 'Models', 'generate' => true],
            'controller'      => ['path' => 'Controllers', 'generate' => true],
            'provider'        => ['path' => 'Providers', 'generate' => true],
            'config'          => ['path' => 'Config', 'generate' => true],
            'contracts'       => ['path' => 'Contracts', 'generate' => true],
            'concerns'        => ['path' => 'Concerns', 'generate' => true],
            'command'         => ['path' => 'Commands', 'generate' => true],
            'routes'          => ['path' => 'Routes', 'generate' => true],
            'event'           => ['path' => 'Events', 'generate' => false],
            'observer'        => ['path' => 'Observers', 'generate' => true],
            'policies'        => ['path' => 'Policies', 'generate' => true],
            'jobs'            => ['path' => 'Jobs', 'generate' => false],
            'resource'        => ['path' => 'Transformers', 'generate' => false],
            'seeder'          => ['path' => 'Database/Seeders', 'generate' => true],
            'middleware'      => ['path' => 'Middleware', 'generate' => true],
            'request'         => ['path' => 'Requests', 'generate' => true],
            'assets'          => ['path' => 'Resources/assets', 'generate' => true],
            'supports'        => ['path' => 'Supports', 'generate' => true],
            'views'           => ['path' => 'Resources/views', 'generate' => true],
            'schemas'         => ['path' => 'Schemas', 'generate' => true],
            'facades'         => ['path' => 'Facades', 'generate' => true],
            'ignore'          => ['path' => '', 'generate' => true]
        ]
    ],
    'database' => [
        'models'  => [
            'App'                         => ModuleVersionModel\Application\App::class,
            'Schema'                      => ModuleVersionModel\Schema\Schema::class,
            'InstallationSchema'          => ModuleVersionModel\Schema\InstallationSchema::class,
            'ModelHasApp'                 => ModuleVersionModel\Application\ModelHasApp::class,
            'ModelHasVersion'             => ModuleVersionModel\Version\ModelHasVersion::class,
        ]

    ],
    'commands' => [
        Commands\AddInstallationSchemaMakeCommand::class,
        Commands\AddModelSchemaMakeCommand::class,
        Commands\AddApplicationMakeCommand::class,
        // Commands\RunSchemaMakeCommand::class,
        Commands\InstallMakeCommand::class,
        Commands\ProviderMakeCommand::class,
        Commands\InterfaceMakeCommand::class
    ]
];
