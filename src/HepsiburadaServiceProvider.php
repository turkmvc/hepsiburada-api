<?php
namespace turkmvc\Hepsiburada;

use Illuminate\Support\ServiceProvider;

class HepsiburadaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('hepsiburada', function(){
          return new Hepsiburada(config('app.hepsiburada'));
        });
    }
}
