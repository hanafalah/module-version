<?php

namespace Zahzah\ModuleVersion\Commands;

use Zahzah\LaravelSupport\Concerns\ServiceProvider\HasMigrationConfiguration;
use Zahzah\LaravelSupport\Contracts\FileRepository;
use Zahzah\ModuleVersion\Concerns\Commands\HasGeneratorAction;
use Zahzah\ModuleVersion\Concerns\HasModuleService;

class EnvironmentCommand extends \Zahzah\LaravelSupport\Commands\BaseCommand{
    use HasMigrationConfiguration;
    use HasModuleService;
    use HasGeneratorAction;

    public static $__moduleversion_config = [];

    public function __construct(){
        parent::__construct();
        $this->init();
    }

    protected function init(): self{
        //INITIALIZE SECTION
        $this->initConfig()->setConfig('module-version',static::$__moduleversion_config)
             ->setRepository(FileRepository::class)
             ->initialized();
        return $this;
    }

    protected function dir(): string{
        return __DIR__.'/../';
    }

    public function callCustomMethod(): array{
        return ['Model','GeneratorPath'];
    }
}
