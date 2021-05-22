<?php

namespace Rslanzi\NovaTranslatable;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Rslanzi\NovaTranslatable\Http\Middleware\Authorize;

class NovaTranslatableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::style('nova-translatable', __DIR__.'/../dist/css/field.css');

            Nova::script('nova-translatable', __DIR__.'/../dist/js/field.js');
            Nova::script('ckeditor', config('nova.ckeditor-field.ckeditor_url', 'https://cdn.ckeditor.com/4.11.3/full-all/ckeditor.js'));
        });

        $this->publishes([
            __DIR__.'/../config/ckeditor-field.php' => config_path('nova/ckeditor-field.php'),
        ], ['config', 'nova-translatable-ckeditor']);

        $this->publishes([
            __DIR__.'/../config/translatable.php' => config_path('translatable.php'),
        ], ['config', 'nova-translatable-config']);

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the field's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', 'api', Authorize::class])
            ->prefix('nova-vendor/rslanzi/nova-translatable')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
