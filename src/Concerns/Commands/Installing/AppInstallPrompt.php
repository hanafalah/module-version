<?php

namespace Zahzah\ModuleVersion\Concerns\Commands\Installing;

use Aibnuhibban\BitbucketLaravel\Traits\BitbucketTrait;
use Zahzah\LaravelSupport\Concerns\DatabaseConfiguration\HasModelConfiguration;
use Zahzah\LaravelStub\Facades\Stub;

trait AppInstallPrompt{
    use HasModelConfiguration;
    use BitbucketTrait;
    public $__ask_app, $__ask_app_version;

    /**
     * Menanyakan apakah user ingin menambahkan versi aplikasi atau tidak.
     *
     * Jika user memilih 'y', maka akan dibuatkan versi aplikasi terbaru.
     *
     * @return static
     */
    protected function askAppVersion(): self{        
        $answer = $this->ask('Please enter the application name to be used ?');
        $this->__ask_app_version = isset($answer);
        if ($this->__ask_app_version){
            $answer_repo = $this->confirm('Do you want create a repository ?', true);
            if ($answer_repo) {
                $slug = $this->ask('Repository slug');
                $workspace = $this->ask('Workspace', 'multi-variat-indonesia');
                $repo = $this->createRepository($workspace, $slug);
                $this->line('✔️ Repository created successfully');
            }
            $this->__ask_app = $this->AppModel()->firstOrCreate(['name' => $answer]);
            $module_version_app = config('module-version.application');
            $namespace = \class_name_builder($answer);
            $this->__ask_app->path        = $module_version_app['path'].'/'.$namespace;
            $this->__ask_app->provider    = $module_version_app['namespace'].'\\'.$namespace.'\\'.$namespace.'ServiceProvider';
            $this->__ask_app->with_source = $this->isNeedSource();
            $this->__ask_app->config_path = $this->withSource().'/'.$module_version_app['generate']['config']['path'].'/config.php';
            if (isset($repo)) $this->__ask_app->vcs_remote = $repo['links']['clone'][0]['href'];
            $this->__ask_app->save();
        }
        return $this;
    }

    protected function installing(string $package_name): void{
        $this->setPackageName($package_name);
        $this->choosedService()->setPackageNamespace();
        $this->setGeneratorList();
        $this->generateClass();
        $this->generateComposer();
        
        $this->newLine();
        
        $generatorLists = $this->getGeneratorListResult();

        foreach ($generatorLists as $key => $value) {
            if ($value['generate']){
                $this->makeDir($this->getGenerateLocation($value['path']));
                $this->__local_paths[] = Stub::init($this->getBaseStub().'/local-path.stub',[
                    'SERVICE'    => $this->getStaticServiceNameResult(), 
                    'KEY'        => $key,
                    'PATH_NAME'  => \ucfirst($key)
                ])->render();
                
                $this->__config_libs[] = Stub::init($this->getBaseStub().'/local-config-lib.stub',[
                    'KEY' => $key,
                    'PATH' => \addslashes($value['path'])
                ])->render();

                switch ($key) {
                    case 'ignore'    : $this->generateIgnore();break;
                    case 'migration' : $this->generateMigration();break;
                    case 'model'     : $this->generateModel();break;
                    case 'controller': $this->generateController();break;
                    case 'provider'  : $this->generateProvider();break;
                    case 'contracts' : $this->generateContracts();break;
                    case 'concerns'  : $this->generateConcerns();break;
                    case 'command'   : $this->generateCommand();break;
                    case 'routes'    : $this->generateRoutes();break;
                    case 'event'     : $this->generateEvent();break;
                    case 'observer'  : $this->generateObserver();break;
                    case 'policies'  : $this->generatePolicies();break;
                    case 'jobs'      : $this->generateJobs();break;
                    case 'resource'  : $this->generateResource();break;
                    case 'seeder'    : $this->generateSeeder();break;
                    case 'middleware': $this->generateMiddleware();break;
                    case 'request'   : $this->generateRequest();break;
                    case 'assets'    : $this->generateAssets();break;
                    case 'views'     : $this->generateViews();break;
                    case 'supports'  : $this->generateSupports();break;
                    case 'schemas'   : $this->generateSchemas();break;
                    case 'facades'   : $this->generateFacades();break;
                    case 'config'    : $this->generateConfig();break;
                }
                $this->newLine();
            }
        }

    }    
}