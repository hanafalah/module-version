<?php

declare(strict_types=1);

namespace Zahzah\ModuleVersion\Providers;

use Illuminate\Support\ServiceProvider;
use Zahzah\ModuleVersion\Commands as Commands;

class CommandServiceProvider extends ServiceProvider
{
    private $commands = [
        Commands\AddInstallationSchemaMakeCommand::class,
        Commands\AddModelSchemaMakeCommand::class,
        Commands\AddApplicationMakeCommand::class,
        // Commands\RunSchemaMakeCommand::class,
        Commands\InstallMakeCommand::class,
        Commands\ProviderMakeCommand::class,
        Commands\InterfaceMakeCommand::class
    ];

    /**
     * Registers the commands.
     *
     * @return void
     */
    public function register(){
        $this->commands(config('module-version.commands',$this->commands));
    }

    public function provides()
    {
        return $this->commands;
    }
}
