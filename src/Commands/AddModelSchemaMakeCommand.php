<?php

namespace Zahzah\ModuleVersion\Commands;

use Zahzah\LaravelStub\Facades\Stub;
use Zahzah\ModuleVersion\Concerns\Commands\Schema\SchemaPrompt;

class AddModelSchemaMakeCommand extends EnvironmentCommand{
    use SchemaPrompt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moduleversion:add-model-schema {namespace} {--location-type= : Type of location betweeen repository or tenant} {--path= : Path of model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command ini digunakan untuk menambahkan schema jenis model';

    /**
     * Execute the console command.
     */
    public function handle(){
        $this->runModelSchema();
    }
}