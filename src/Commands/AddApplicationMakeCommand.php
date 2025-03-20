<?php

namespace Hanafalah\ModuleVersion\Commands;

use Hanafalah\ModuleVersion\{
    Concerns\HasModuleService
};
use Hanafalah\ModuleVersion\Concerns\Commands\HasGeneratorAction;

class AddApplicationMakeCommand extends EnvironmentCommand
{
    use HasModuleService;
    use HasGeneratorAction;

    protected array $__local_paths = [], $__config_libs = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moduleversion:add-application {--namespace= : Application namespace} {--description= : Schema description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to add new application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->notReady()) $this->init();
        $this->setNeedSource();

        $this->askAppVersion();
        if (isset($this->__ask_app)) $this->installing($this->__ask_app->name);
    }

    // private function getNamespace($app_name){
    //     return "App".$app_name;
    // }

    // protected function addInstallationSchema(): self{
    //     $app             = $this->getAskAppResult();

    //     $app_name        = \class_name_builder($app->name);
    //     // $schema_path     = app_path('Schemas/Installation/'.$app_name);
    //     $version_pattern = static::$__moduleversion_config['application']['version_pattern'];

    //     $version         = $app->modelHasVersion()->create([
    //         'name' => $version_pattern,
    //     ]);
    //     $namespace = $this->getNamespace($app_name);

    //     $this->callInstallationSchema($namespace, $app_name);

    //     $schema = ModuleVersion::useSchema(SchemaManagement::class)->add([
    //         'name'   => $app->name,
    //         'app_id' => $app->getKey(),
    //     ])->getModel();

    //     $version->installationSchema()->firstOrCreate([
    //         'schema_id' => $schema->getKey()
    //     ]);
    //     // if (!$this->isFile("$schema_path/$namespace.php")) {
    //     // }
    //     return $this;
    // }
}
