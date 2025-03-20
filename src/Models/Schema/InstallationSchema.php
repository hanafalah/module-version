<?php

namespace Hanafalah\ModuleVersion\Models\Schema;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;

class InstallationSchema extends BaseModel
{
    use SoftDeletes, HasProps;

    /*
     * RELATE THE APP TO THE INSTALLATION SCHEMA AND THEN DEFINE THE FEATURES BASED ON THE APP'S NEEDS
     * AFTER THAT, IF THE TENANT TYPE HAS A SIGNIFICANT FEATURE, WE NEED TO OVERRIDE THE FEATURES THAT WERE DEFINED BEFORE BASED ON THE TENANT TYPE'S NEEDS
     * AFTER THAT, IF THE TENANT HAS A SPECIAL FEATURE, IT WILL OVERRIDE THE FEATURES THAT WERE DEFINED BEFORE
     * ALL THE FEATURES WILL BE MIXED TOGETHER TO BECOME A SINGLE INSTALLATION SCHEMA THAT WILL BE SAVED IN THE SYSTEM CACHE
    */

    public $incrementing  = true;
    public $timestamps    = true;
    protected $table      = 'installation_schemas';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'id',
        'parent_id',
        'reference_type',
        'reference_id',
        'schema_id',
        'description'
    ];

    //EIGER SECTION
    public function schema()
    {
        return $this->belongsToModel('Schema');
    }
    //END EIGER SECTION
}
