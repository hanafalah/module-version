<?php

namespace Zahzah\ModuleVersion\Models\Application;

use Zahzah\LaravelSupport\Models;

class ModelHasApp extends Models\BaseModel{

    protected $fillable = [
        'id','reference_type','reference_id','app_id'
    ];

    public function app(){return $this->belongsToModel('App');}
    public function reference(){return $this->morphTo();}
}