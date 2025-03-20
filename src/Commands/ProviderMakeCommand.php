<?php

namespace Hanafalah\ModuleVersion\Commands;

use Hanafalah\LaravelStub\Facades\Stub;

use Illuminate\Support\Str;

class ProviderMakeCommand extends EnvironmentCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moduleversion:make-provider 
                {package-name : The name of module class}
                {--name : The name of the class module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider class';

    /** @var string */
    protected $__name;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setup();

        $package_name = static::$__package_name;
        $this->__name = !$this->option('name') ? $package_name : $this->option('name');

        $save_path = $this->getGenerateLocation() . '/' . $this->providerGeneratorPath();

        $this->cardLine('Creating Package Provider', function () use ($save_path, $package_name) {
            Stub::init($this->getBaseStub() . '/provider.stub', [
                'WHEN_BOOTED'       => "\t\t\t" . '$this->registers(["*"]);',
                'CLASS_NAMESPACE'   => $this->generateNamespace(),
                'NAMESPACE'         => $this->generateNamespace('provider'),
                'CONTRACT_PATH'     => $this->contractsGeneratorPath(),
                'SUPPORT_PATH'      => $this->supportsGeneratorPath(),
                'FACADES_PATH'      => $this->facadesGeneratorPath(),
                'CLASS_NAME'        => $package_name,
                'LOWER_CLASS_NAME'  => $this->lowerPackageName(),
                'CONFIG_NAME'       => Str::lower(Str::replace(' ', '-', $package_name)),
                'DEFINE_FEATURE'    => ''
            ])->saveTo($save_path, $package_name . 'ServiceProvider.php');
        });
    }

    public function callCustomMethod(): array
    {
        return ['Model', 'GeneratorPath'];
    }
}
