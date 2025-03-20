<?php

namespace Hanafalah\ModuleVersion\Commands;

class InstallMakeCommand extends EnvironmentCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moduleversion:install';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command ini digunakan untuk installing awal module version';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = 'Hanafalah\ModuleVersion\ModuleVersionServiceProvider';

        $this->comment('Installing ModuleVersion...');
        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'config'
        ]);
        $this->info('✔️  Created config/module-version.php');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'stubs'
        ]);
        $this->info('✔️  Created Stubs/ModuleVersionStubs');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'providers'
        ]);

        $this->info('✔️  Created ModuleVersionServiceProvider.php');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'migrations'
        ]);

        $this->info('✔️  Created migrations');
        $migrations = $this->setMigrationBasePath(database_path('migrations'))->canMigrate();
        $this->callSilent('migrate', [
            '--path' => $migrations
        ]);
        $this->info('✔️  Module Version tables migrated');

        $this->comment('hanafalah/module-version installed successfully.');
    }
}
