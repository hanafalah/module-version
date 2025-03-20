<?php

namespace Hanafalah\ModuleVersion\Commands;

use Hanafalah\LaravelSupport\Concerns\ServiceProvider\HasMigrationConfiguration;
use Hanafalah\LaravelSupport\Contracts\FileRepository;
use Hanafalah\ModuleVersion\Concerns\Commands\HasGeneratorAction;
use Hanafalah\ModuleVersion\Concerns\HasModuleService;

class EnvironmentCommand extends \Hanafalah\LaravelSupport\Commands\BaseCommand
{
    use HasMigrationConfiguration;
    use HasModuleService;
    use HasGeneratorAction;

    public static $__moduleversion_config = [];

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    protected function init(): self
    {
        //INITIALIZE SECTION
        $this->initConfig()->setConfig('module-version', static::$__moduleversion_config)
            ->setRepository(FileRepository::class)
            ->initialized();
        return $this;
    }

    protected function dir(): string
    {
        return __DIR__ . '/../';
    }

    public function callCustomMethod(): array
    {
        return ['Model', 'GeneratorPath'];
    }
}
