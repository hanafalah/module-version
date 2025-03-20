<?php

namespace Hanafalah\ModuleVersion\Concerns\Commands;

use Illuminate\Support\Facades\Artisan;
use Hanafalah\LaravelStub\Facades\Stub;
use Illuminate\Support\Str;
use Hanafalah\LaravelSupport\Concerns\Support\HasCall;

trait HasGeneratorAction
{
    use HasCall;
    use GeneratorPath;
    protected $__base_stub = 'ModuleVersionStubs';

    protected $__ignore_content = "/*\n!/src\n!.gitignore\n!composer.json";

    protected function getBaseStub(): string
    {
        return $this->__base_stub;
    }

    protected function generateIgnore(): void
    {
        $this->cardLine('Generating Ignore', function () {
            $path = $this->getGenerateLocation();

            if ($this->isNeedSource()) $path = \str_replace('/src', '', $path);

            Stub::init($this->getIgnoreStubPath(), [
                'CONTENT' => $this->__ignore_content
            ])->saveTo($path, '.gitignore');
        });
    }

    /**
     * Generate Class
     *
     * This method will generate the class in the package path with the name
     * `{{LOWER_CLASS_NAME}}.php`.
     *
     * The class will be generated with the following properties:
     *
     * - `NAMESPACE`: The namespace of the class.
     * - `CLASS_NAME`: The name of the class.
     * - `LOWER_CLASS_NAME`: The lower case of the package name.
     * - `CONTRACT_PATH`: The path of the contracts.
     * - `SUPPORT_PATH`: The path of the supports.
     * - `SERVICE_NAME`: The name of the service.
     */
    protected function generateClass(): void
    {
        $this->cardLine('Generating Classes', function () {
            Stub::init($this->getClassStubPath(), [
                'NAMESPACE'         => $this->generateNamespace(),
                'CLASS_NAME'        => $this->generateClassName(),
                'LOWER_CLASS_NAME'  => $this->lowerPackageName(),
                'CONTRACT_PATH'     => $this->contractsGeneratorPath(),
                'SUPPORT_PATH'      => $this->supportsGeneratorPath(),
                'SERVICE_NAME'      => $this->getStaticServiceNameResult()
            ])->saveTo($this->getGenerateLocation(), \class_basename($this->getStaticPackageNameResult()) . '.php');
        });
    }

    protected function generateComposer(): void
    {
        $this->cardLine('Generating Composer', function () {
            $path = $this->getGenerateLocation();
            if ($this->isNeedSource()) $path = \str_replace('/src', '', $path);

            Stub::init($this->getComposerStubPath(), [
                'AUTHOR'                  => \strtolower($this->__ask_app->name),
                'PACKAGE_NAME'            => $this->getStaticPackageNameResult(),
                'LOWER_SECOND_NAMESPACE'  => \strtolower($this->getStaticPackageNameResult()),
                'PROVIDER_PATH'           => $this->providerGeneratorPath(),
                'AUTHOR_NAME'             => 'hanafalah',
                'AUTHOR_MAIL'             => 'hamzahnuralfalah@gmail.com',
                'NAMESPACE'               => Str::replace('\\', '\\\\', $this->getStaticPackageNamespaceResult()),
                'VERSION_PATH'            => $this->isNeedSource() ? 'src/' : '/',
                'REPOSITORIES'            => function () {
                    return '';
                },
                'REQUIRE_DEV'             => function () {
                    return '';
                }
            ])->saveTo($path, 'composer.json');
        });
    }

    /**
     * Generate Config
     *
     * This method will generate the config file in the package path with the
     * following keys:
     *
     * - `SERVICE_NAME`: The name of the service that will be used to generate
     *   the config file.
     * - `LIBS`: The list of libraries that will be loaded in the config file.
     * - `BASE_PATH`: The base path of the package.
     *
     * The config file will be saved in the package path with the name
     * `config.php`.
     */
    protected function generateConfig(): void
    {
        $this->cardLine('Generating Config', function () {
            $choosedConfig = $this->getStaticChoosedConfigResult();
            $save_path     = $this->getGenerateLocation() . '/' . $choosedConfig['generate']['config']['path'];
            Stub::init($this->getConfigStubPath(), [
                'WITH_SOURCE'    => $this->isNeedSource(),
                'SERVICE_NAME'   => $this->getStaticPackageNameResult(),
                'LIBS'           => implode(",\n", $this->__config_libs),
                'BASE_PATH'      => $choosedConfig['path']
            ])->saveTo($save_path, 'config.php');
        });
    }

    /**
     * Generate Contracts
     *
     * This method will generate the following contracts:
     *
     * - FileRepositoryInterface.php
     *
     * @return void
     */
    protected function generateContracts(): void
    {
        $this->cardLine('Generating Contracts', function () {
            Stub::init($this->getInterfaceFileRepoStubPath(), [
                'NAMESPACE'         => $this->generateNamespace('contracts'),
                'WITH_DISCOVERING'  => 'public function setupClassDiscover(): mixed;'
            ])->saveTo($this->getGenerateLocation() . '/' . $this->contractsGeneratorPath(), 'FileRepositoryInterface.php');
        });

        $this->callInterface();
    }

    protected function callInterface()
    {
        Artisan::call('moduleversion:make-interface', [
            "package-name" => $this->getStaticPackageNameResult(),
            "--name"       => $this->getStaticPackageNameResult()
        ]);
    }

    protected function generateProvider(): void
    {
        $save_path = $this->getGenerateLocation() . '/' . $this->providerGeneratorPath();

        $this->cardLine('Generating Providers', function () use ($save_path) {
            Stub::init($this->getProviderCommandStubPath(), [
                'NAMESPACE'         => $this->generateNamespace('provider'),
                'CONFIG_NAME'       => $this->getConfigName()
            ])->saveTo($save_path, 'CommandServiceProvider.php');
        });

        $package_name  = $this->getStaticPackageNameResult();
        $this->cardLine('Creating Route Service Provider', function () use ($save_path, $package_name) {
            Stub::init($this->getProviderRouteStubPath(), [
                'NAMESPACE'              => $this->generateNamespace('provider'),
                'CLASS_NAMESPACE'        => $this->generateNamespace(),
                'SERVICE_NAME'           => $this->getStaticServiceNameResult(),
                'ROUTE_PATH'             => $this->routesGeneratorPath(),
                'ROUTE_FULLPATH'         => $package_name,
                'CONTRACT_NAME'          => $this->contractsGeneratorPath(),
                'CLASS_NAME'             => $package_name,
                'LOWER_CLASS_NAME'       => $this->lowerPackageName()
            ])->saveTo($save_path, 'RouteServiceProvider.php');
        });

        $this->cardLine('Creating Env Provider', function () use ($save_path, $package_name) {
            Stub::init($this->getProviderEnvStubPath(), [
                'NAMESPACE'              => $this->generateNamespace('provider'),
                'CLASS_NAMESPACE'        => $this->generateNamespace(),
                'PROVIDER_NAME'          => $this->providerGeneratorPath(),
                'CONTRACT_NAME'          => $this->contractsGeneratorPath(),
                'CLASS_NAME'             => $package_name,
                'LOWER_CLASS_NAME'       => $this->lowerPackageName(),
                'CONFIG_NAME'            => Str::lower(Str::replace('_', '-', Str::snake($package_name))),
                'SUPPORT_PATH'           => $this->supportsGeneratorPath(),
                'CONFIG_PATH'            => $this->configGeneratorPath(),
                'CONFIG_VAR_NAME'        => Str::lower(Str::snake($package_name)),
                'MIGRATION_PATH'         => $this->migrationGeneratorPath(),
                'STUB_REGISTER_VIEWS'    => function () use ($package_name) {
                    return Stub::init($this->getBaseStub() . '/register-views.stub', [
                        'VIEW_PATH'        => $package_name . '/' . $this->viewsGeneratorPath()
                    ])->render();
                },
                'RUN_DISCOVERING'           => ''
            ])->saveTo($save_path, $package_name . 'Environment.php');
        });
        $this->callProvider();
    }

    protected function callProvider()
    {
        Artisan::call("moduleversion:make-provider", [
            "package-name" => $this->getStaticPackageNameResult()
        ]);
    }

    protected function generateMigration(): void
    {
        $this->cardLine('Generating Migration', function () {});
    }

    protected function generateModel(): void
    {
        $this->cardLine('Generating Model', function () {});
    }

    protected function generateController(): void
    {
        $this->cardLine('Generating Controller', function () {});
    }

    protected function generateConcerns(): void
    {
        $this->cardLine('Generating Concerns', function () {});
    }

    protected function generateCommand(): void
    {
        $this->cardLine('Generating Command', function () {});
    }


    protected function generateRoutes(): void
    {
        $this->cardLine('Generating Routes', function () {
            $save_path = $this->getGenerateLocation() . '/' . $this->routesGeneratorPath();

            Stub::init($this->getWebStubPath(), [
                'LOWER_CLASS_NAME' => $this->lowerPackageName(),
                'CONTROLLER_NAME'  => $this->getStaticPackageNameResult() . 'Controller'
            ])->saveTo($save_path, 'web.php');

            Stub::init($this->getApiStubPath(), [
                'LOWER_CLASS_NAME' => $this->lowerPackageName(),
                'CONTROLLER_NAME'  => $this->getStaticPackageNameResult() . 'Controller'
            ])->saveTo($save_path, 'api.php');
        });
    }

    protected function generateEvent(): void
    {
        $this->cardLine('Generating Event', function () {});
    }

    protected function generateObserver(): void
    {
        $this->cardLine('Generating Observer', function () {});
    }

    protected function generatePolicies(): void
    {
        $this->cardLine('Generating Policies', function () {});
    }

    protected function generateJobs(): void
    {
        $this->cardLine('Generating Jobs', function () {});
    }

    protected function generateResource(): void
    {
        $this->cardLine('Generating Resource', function () {});
    }

    protected function generateSeeder(): void
    {
        $this->cardLine('Generating Seeder', function () {});
    }

    protected function generateMiddleware(): void
    {
        $this->cardLine('Generating Middleware', function () {});
    }

    protected function generateRequest(): void
    {
        $this->cardLine('Generating Request', function () {});
    }

    protected function generateAssets(): void
    {
        $this->cardLine('Generating Assets', function () {});
    }

    protected function generateViews(): void
    {
        $this->cardLine('Generating Views', function () {});
    }


    protected function generateSupports(): void
    {
        $config    = $this->getStaticChoosedConfigResult();
        $save_path = $this->getGenerateLocation() . '/' . $config['generate']['supports']['path'];
        $this->cardLine('Generating File Repository', function () use ($save_path, $config) {
            Stub::init($this->getSupportFileRepoStubPath(), [
                'NAMESPACE'            => $this->generateNamespace('supports'),
                'CLASS_NAMESPACE'      => $this->generateNamespace(),
                'FIRST_NAMESPACE'      => $this->getStaticNamespaceResult(),
                'SECOND_NAMESPACE'     => $this->getStaticPackageNameResult(),
                'CONFIG_NAME'          => $this->getConfigName(),
                'CONTRACT_NAME'        => $this->contractsGeneratorPath(),
                'SETUP_FILE_DISCOVERY' => ''
            ])->saveTo($save_path, 'FileRepository.php');
        });

        $this->cardLine('Generating Class Local Path', function () use ($save_path, $config) {
            Stub::init($this->getClassLocalStubPath(), [
                'NAMESPACE'         => $this->generateNamespace('supports'),
                'LOCAL_PATHS'       => \implode($this->__local_paths),
                'CONFIG_PATH'       => $config['generate']['config']['path'],
                'CONFIG_NAME'       => $this->getConfigName(),
            ])->saveTo($save_path, 'LocalPath.php');
        });
    }

    protected function generateSchemas(): void
    {
        $this->cardLine('Generating Schemas', function () {
            $config    = $this->getStaticChoosedConfigResult();
            $save_path = $this->getGenerateLocation() . '/' . $config['generate']['schemas']['path'];
            $className = $this->generateClassName();

            Stub::init($this->getAddInstallationSchemaStubPath(), [
                'NAMESPACE'          => $this->generateNamespace('schemas'),
                'CLASS_NAME'         => $className,
                'SCHEMA_DESCRIPTION' => 'Installation schema for ' . $this->getStaticPackageNameResult()
            ])->saveTo($save_path, $className . ".php");
        });
    }

    protected function generateFacades(): void
    {
        $this->cardLine('Generating Facades', function () {
            $config    = $this->getStaticChoosedConfigResult();
            $save_path = $this->getGenerateLocation() . '/' . $config['generate']['facades']['path'];

            Stub::init($this->getFacadeStubPath(), [
                'NAMESPACE'         => $this->generateNamespace(),
                'CLASS_NAME'        => $this->generateClassName(),
                'FACADES_PATH'      => $this->facadesGeneratorPath(),
                'CONTRACTS_PATH'    => $this->contractsGeneratorPath()
            ])->saveTo($save_path, \class_basename($this->getStaticPackageNameResult()) . '.php');
        });
    }
}
