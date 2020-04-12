<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 全局请求ID
        define('TRACE_ID', request_sn());

        \App\Helper\SystemConfig::load();

        $this->registerValidator();
    }

    public function registerValidator()
    {
        Validator::extend('mobile', 'App\Http\Validators\UtilValidator@validateMobile');
    }
}
