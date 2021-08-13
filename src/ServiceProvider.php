<?php


namespace Uutkukorkmaz\RouteOrganizer;


use Uutkukorkmaz\RouteOrganizer\Console\Commands\MakeRouteGroupCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register the service provider
     * 
     * @return void
    */
    public function register()
    {
//        $this->app->singleton('command.routeorganizer.make',fn($app) => new MakeRouteGroupCommand);
    }
    
    public function boot(){
        $this->publishes([
            __DIR__.'/Console/Commands/MakeRouteGroupCommand.php' => app_path('Console/Commands/MakeRouteGroupCommand.php')
        ],'routeorganizer');
    }
    
}