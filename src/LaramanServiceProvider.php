<?php

namespace RLStudio\Laraman;

use Illuminate\Support\ServiceProvider;

class LaramanServiceProvider extends ServiceProvider
{
    /**
     * Register the command.
     */
    public function register()
    {
        $this->commands(ExportRoutesToPostman::class);
    }
}