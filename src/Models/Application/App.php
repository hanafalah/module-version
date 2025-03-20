<?php

namespace Hanafalah\ModuleVersion\Models\Application;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models;

class App extends Models\BaseModel
{
    use SoftDeletes, HasProps;

    public $incrementing   = true;

    protected $fillable = [
        'id',
        'uuid',
        'parent_id',
        'name'
    ];

    //EIGER SECTION
    public function installationSchema()
    {
        return $this->morphOneModel('InstallationSchema', 'reference');
    }
    public function installationSchemas()
    {
        return $this->morphManyModel('InstallationSchema', 'reference');
    }
    public function modelHasVersion()
    {
        return $this->morphOneModel('ModelHasVersion', 'model');
    }
    public function modelHasApp()
    {
        return $this->hasOneModel('ModelHasApp');
    }
    public function modelHasApps()
    {
        return $this->hasManyModel('ModelHasApp');
    }
    //END EIGER SECTION
}
