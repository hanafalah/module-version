<?php

namespace Zahzah\ModuleVersion\Concerns\Commands;

trait Initialize{
    /** @var bool */
    protected static $__init_status = false;

    /**
     * Check if the tenant is not ready
     *
     * @return boolean
     */
    protected function notReady(){
        return !static::$__init_status;
    }

    /**
     * Set the tenant initialization status to true
     *
     * @return void
     */
    protected function initialized(){
        static::$__init_status = true;
    }
}