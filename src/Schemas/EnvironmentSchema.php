<?php

namespace Zahzah\ModuleVersion\Schemas;

use Zahzah\LaravelSupport\Concerns;
use Zahzah\LaravelSupport\Supports\PackageManagement;

class EnvironmentSchema extends PackageManagement{
    use Concerns\Support\HasArray;
    use Concerns\DatabaseConfiguration\HasModelConfiguration;

    public function callCustomMethod(){
        return ['Model'];
    }    
}