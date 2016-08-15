<?php

namespace RLStudio\Laraman;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the command.
     */
    public function register()
    {
        $this->commands(ExportRoutesToPostman::class);
    }
}