<?php

namespace Hanafalah\ModuleVersion\Schemas;

use Hanafalah\LaravelSupport\Concerns;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class EnvironmentSchema extends PackageManagement
{
    use Concerns\Support\HasArray;
    use Concerns\DatabaseConfiguration\HasModelConfiguration;

    public function callCustomMethod()
    {
        return ['Model'];
    }
}
