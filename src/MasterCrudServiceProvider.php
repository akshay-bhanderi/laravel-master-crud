<?php

namespace AkshayBhanderi\LaravelMasterCrud;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use AkshayBhanderi\LaravelMasterCrud\Http\Controllers\PackageAssetController;
use AkshayBhanderi\LaravelMasterCrud\Console\Commands\MakeMasterCrudCommand;

class MasterCrudServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/master-crud.php', 'master-crud');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'master-crud');

        // Auto-discovered, no publish step needed. Each migration guards
        // itself with Schema::hasTable() so it's a safe no-op in apps that
        // already have users/user_roles (own tables/data untouched).
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Fallback search path for plain (non-namespaced) view names such as
        // 'portal.master.banner.add'. The app's own resources/views is always
        // checked first, so dropping a same-path file there overrides the
        // package's copy without any other change.
        $this->app['view']->addLocation(__DIR__.'/../resources/views');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components');

        // Fallback for static assets (DataTables, Select2, RichTextEditor, indrop,
        // iziToast, jquery.form.js). The web server always serves a real file in
        // the app's public/ directly, without ever reaching Laravel — so this
        // route only fires for paths that don't physically exist there, and an
        // app-local file at the same path overrides the package's copy for free.
        Route::get('assets/inlancer_portal/{path}', [PackageAssetController::class, 'serve'])
            ->where('path', '.*')
            ->name('master-crud.assets');

        $this->publishes([
            __DIR__.'/../config/master-crud.php' => config_path('master-crud.php'),
        ], 'master-crud-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/master-crud'),
        ], 'master-crud-views');

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/master-crud'),
        ], 'master-crud-stubs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMasterCrudCommand::class,
            ]);
        }
    }
}
