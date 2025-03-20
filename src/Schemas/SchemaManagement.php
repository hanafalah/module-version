<?php

namespace Hanafalah\ModuleVersion\Schemas;

use Hanafalah\LaravelSupport\Contracts\DataManagement;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class SchemaManagement extends PackageManagement implements DataManagement
{
    protected array $__add = ['name'];

    /**
     * Add a new API access or update the existing one if found.
     *
     * The given attributes will be merged with the existing API access.
     *
     * @param array $attributes The attributes to be added to the API access.
     *
     * @return \Illuminate\Database\Eloquent\Model The API access model.
     */
    public function addOrChange(?array $attributes = []): self
    {
        static::$__model = $this->SchemaModel()->updateOrCreate(...$this->createInit($this->__add, $attributes));
        if ($this->isRecentlyCreated() && isset($attributes['app_id'])) {
            static::$__model->modelHasApp()->firstOrCreate([
                'app_id' => $attributes['app_id']
            ]);
        }
        return $this;
    }

    public function remove() {}
}
