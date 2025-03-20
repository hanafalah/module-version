<?php

namespace Hanafalah\ModuleVersion\Models\Schema;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;

class Schema extends BaseModel
{
    use SoftDeletes, HasProps;

    protected $fillable   = [
        'id',
        'parent_id',
        'name'
    ];

    //EIGER SECTION
    public function installationSchema()
    {
        return $this->hasOneModel('InstallationSchema');
    }
    public function modelHasApp()
    {
        return $this->morphOneModel('ModelHasApp', 'model');
    }
    //END EIGER SECTION
}
