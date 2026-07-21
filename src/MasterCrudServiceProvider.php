<?php

namespace AkshayBhanderi\LaravelMasterCrud;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MasterCrudServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/master-crud.php', 'master-crud');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'master-crud');

        // Fallback search path for plain (non-namespaced) view names such as
        // 'portal.master.banner.add'. The app's own resources/views is always
        // checked first, so dropping a same-path file there overrides the
        // package's copy without any other change.
        $this->app['view']->addLocation(__DIR__.'/../resources/views');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components');

        $this->publishes([
            __DIR__.'/../config/master-crud.php' => config_path('master-crud.php'),
        ], 'master-crud-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/master-crud'),
        ], 'master-crud-views');

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/master-crud'),
        ], 'master-crud-stubs');
    }
}
