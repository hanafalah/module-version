<?php

namespace Hanafalah\ModuleVersion\Models\Version;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Hanafalah\LaravelHasProps\Concerns\HasCurrent;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class ModelHasVersion extends BaseModel
{
    use HasUlids, HasProps, HasCurrent;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';

    //I SUGGEST TO USE DEPLOYMENT VERSION
    protected $fillable = [
        'id',
        'parent_id',
        'model_type',
        'model_id',
        'name',
        'current'
    ];

    public function getConditions(): array
    {
        return ['model_type', 'model_id'];
    }


    protected static function booted(): void
    {
        parent::booted();
    }

    public function model()
    {
        return $this->morphTo();
    }
    public function installationSchema()
    {
        return $this->morphOneModel('InstallationSchema', 'reference');
    }
}
