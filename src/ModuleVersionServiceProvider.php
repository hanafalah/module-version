<?php

declare(strict_types=1);

namespace Hanafalah\ModuleVersion;

use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleVersionServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->registerMainClass(ModuleVersion::class)
            ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers([
                '*',
                'Services' => function () {
                    $this->binds([
                        Contracts\ModuleVersion::class => new ModuleVersion
                    ]);
                }
            ]);
    }

    /**
     * Get the base directory of the package.
     *
     * @return string
     */
    protected function dir(): string
    {
        return __DIR__ . '/';
    }
}
