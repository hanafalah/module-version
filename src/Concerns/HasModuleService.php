<?php

namespace Hanafalah\ModuleVersion\Concerns;

use Illuminate\Support\Str;
use Hanafalah\ModuleVersion\Concerns\Commands\Initialize;
use Hanafalah\ModuleVersion\Concerns\Commands\Installing\AppInstallPrompt;

trait HasModuleService
{
    use AppInstallPrompt;
    use Initialize;

    protected $__generator_list;
    protected static array $__choosed_config;

    protected static string $__service_name = 'application',
        $__package_name, $__service_path,
        $__namespace, $__service_file_path,
        $__package_namespace, $__need_source;

    /**
     * Returns the path for the generator based on the method called.
     *
     * For example, if you call `contractsGeneratorPath`, it will return the path
     * for the contracts generator.
     *
     * @return string
     */
    public function __callGeneratorPath()
    {
        $method = $this->getCallMethod();
        if (Str::endsWith($method, 'GeneratorPath')) {
            return static::$__choosed_config['generate'][Str::before($method, 'GeneratorPath')]['path'];
        }
    }

    /**
     * Set the flag to indicate whether the generated code is needed to be stored in the source folder.
     *
     * @param bool $need_source
     * @return $this
     */
    protected function setNeedSource(bool $need_source = true): self
    {
        static::$__need_source = $need_source;
        return $this;
    }

    protected function isNeedSource(): bool
    {
        return static::$__need_source;
    }

    /**
     * If the need source flag is set, return '/src', otherwise return an empty string.
     *
     * @return string
     */
    protected function withSource(): string
    {
        if (static::$__need_source) return '/src';
        return '';
    }

    /**
     * Get the lowercased version of the package name.
     *
     * @return string The lowercased package name.
     */
    protected function lowerPackageName(): string
    {
        return Str::lower(static::$__package_name);
    }

    /**
     * Generate the namespace for a given folder path if it exists. Otherwise the main package namespace is returned.
     *
     * @param string|null $folder_path
     * @return string
     */
    protected function generateNamespace(?string $folder_path = null)
    {
        $package_name = static::$__package_name;
        $first_path   = (isset(static::$__namespace))
            ? static::$__namespace . '\\' . $package_name
            : $package_name;
        return (isset($folder_path))
            ? $first_path . '\\' . static::$__choosed_config['generate'][$folder_path]['path']
            : $first_path;
    }

    /**
     * Get the config name for the given package name.
     *
     * If $name is not given, the package name will be used.
     *
     * The config name is the lowercased version of the package name with dashes instead of spaces.
     *
     * @param string|null $name
     * @return string The config name.
     */
    protected function getConfigName(?string $name = null): string
    {
        return Str::lower(Str::replace('_', '-', Str::snake($name ?? $this->getStaticPackageNameResult())));
    }

    /**
     * Get the path where the service file is located.
     *
     * If the service file path is set, it will be returned. Otherwise, the service path is returned.
     *
     * @return string The path where the service file is located.
     */
    public function getServicePath(): string
    {
        return (static::$__service_file_path ?? static::$__service_path) . $this->withSource();
    }

    /**
     * Get the location where the file will be generated.
     *
     * @param string $path The path to add to the base path.
     * @return string The path where the file will be generated.
     */
    public function getGenerateLocation(string $path = ''): string
    {
        $base_path = base_path($this->getServicePath());
        return $base_path . '/' . $path;
    }

    /**
     * Create a new service with the choosed package name.
     *
     * @return self The current instance of the class.
     */
    protected function createNewService(): self
    {
        if (!isset(static::$__package_name)) $this->setPackageName($this->askName());
        $this->setServiceFilePath(static::$__package_name);
        return $this;
    }

    /**
     * Setups the process by initializing the class and setting the service name
     * and package name from the command arguments.
     *
     * @return self The current instance of the class.
     */
    protected function setup(): self
    {
        if ($this->notReady()) {
            $this->newLine();
            $this->cardLine('Initialize Process', function () {
                $this->init()
                    ->setChoosedService(config('module-version.application'))
                    ->setPackageName($this->argument('package-name'))
                    ->setServiceFilePath($this->getStaticPackageNameResult());
            });
        }
        return $this;
    }

    /**
     * Set the service file path to use for this command.
     *
     * @param string $file_location The file location to use.
     *
     * @return $this
     */
    protected function setServiceFilePath(string $file_location): self
    {
        static::$__service_file_path = static::$__service_path . '/' . $file_location;
        return $this;
    }

    /**
     * Set the package namespace to use for this command.
     *
     * This will set the package namespace to the namespace defined in the
     * config file, followed by the package name. If the package has a version,
     * the version will be appended to the namespace.
     *
     * @return $this
     */
    protected function setPackageNamespace(): self
    {
        static::$__package_namespace = static::$__namespace . '\\' . static::$__package_name;
        return $this;
    }

    /**
     * Set the package name to use for this command.
     *
     * @param string $package_name The package name to use.
     * @param array $module_list The list of modules in the package.
     *
     * @return $this
     */
    protected function setPackageName(string $package_name): self
    {
        static::$__package_name = class_name_builder($package_name);
        $this->info('Package ' . static::$__package_name . ' is being used');
        return $this;
    }

    /**
     * Set the service path to use for this command.
     *
     * @param string|null $path The service path to use.
     *
     * @return $this
     */
    protected function setServicePath(string $path): self
    {
        static::$__service_path = $path;
        return $this;
    }

    /**
     * Get the choosed service config to use for this command.
     *
     * @return array The choosed service config to use.
     */
    public function getChoosedConfig(): array
    {
        return static::$__choosed_config;
    }

    /**
     * Set the list of generators that will be used to generate the tenant.
     *
     * @return self
     */
    protected function setGeneratorList(): self
    {
        $this->cardLine('Reading Generator', function () {
            $this->__generator_list = $this->getStaticChoosedConfigResult()['generate'];
            $this->info('<fg=yellow>' . count($this->__generator_list) . '</> Generator Listed');
        });
        return $this;
    }

    /**
     * Set the namespace to use for this command.
     *
     * @param string|null $namespace The namespace to use.
     *
     * @return $this
     */
    protected function setNamespace(?string $namespace = null): self
    {
        $namespaces = $this->repo()->getClassReinforcement($namespace ?? static::$__choosed_config['namespace']);
        $namespaces = \explode('/', $namespaces);
        foreach ($namespaces as $key => $namespace) $namespaces[$key] = \class_name_builder($namespace);
        static::$__namespace = implode('\\', $namespaces);
        return $this;
    }

    /**
     * Set the choosed service config to use for this command.
     *
     * @param array|null $choosed_service The choosed service config to use.
     *
     * @return $this
     */
    protected function setChoosedService($choosed_service = null): self
    {
        static::$__choosed_config = $choosed_service ?? $this->getStaticModuleversionConfigResult()[static::$__service_name];
        $this->setServicePath(static::$__choosed_config['path']);
        return $this;
    }

    /**
     * Generate the class name for the service to use based on the current package name or app name.
     *
     * If the current service name is the app version service, the app name is used. Otherwise the package name is used.
     *
     * @return string The generated class name.
     */
    protected function generateClassName(): string
    {
        return \class_name_builder(static::$__package_name);
    }

    /**
     * Check if the service name has been choosed.
     *
     * @return bool
     */
    protected function isServiceChoosed()
    {
        return isset(static::$__service_name);
    }

    /**
     * Set the service name to use and create the new service path.
     *
     * @return self The current instance of the class.
     */
    protected function choosedService()
    {
        if (!isset(static::$__choosed_config)) {
            $this->setChoosedService(config('module-version.' . static::$__service_name));
        }
        $this->setNamespace(static::$__namespace ?? null)
            ->createNewService();
        return $this;
    }

    /**
     * Choose the application that will be used as the central tenant.
     *
     * It will ask the user to choose from the list of available applications,
     * or create a new one if the user chooses 'new'.
     *
     * @return self The current instance of the class.
     */
    protected function chooseApplication(): self
    {
        $apps  = $this->AppModel()->pluck('name')->toArray();
        $apps  = array_merge(['new'], $apps);
        $answer = $this->choice('Choose the type of application ?', $apps, 'new');
        if ($answer == 'new') {
            $this->askAppVersion();
        } else {
            $this->__ask_app = $this->AppModel()->where('name', $answer)->first();
        }
        return $this;
    }
}
