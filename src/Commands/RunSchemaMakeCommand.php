<?php

namespace Zahzah\ModuleVersion\Commands;

use Zahzah\ModuleVersion\Concerns\Commands\Schema\SchemaPrompt;

class RunSchemaMakeCommand extends EnvironmentCommand{
    use SchemaPrompt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'micro:run-schema {schema-path : Schema Path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command ini digunakan untuk running schema';

    /**
     * Execute the console command.
     */
    public function handle(){
        $schema = $this->argument('schema-path');
        $schema = app(str_replace('/', '\\', "App\\Schemas\\{$schema}"));
        $schema->boot();
    }
}