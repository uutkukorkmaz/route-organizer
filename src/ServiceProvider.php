<?php


namespace Uutkukorkmaz\RouteOrganizer;




class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register the service provider
     *
     * @return void
    */
    public function register()
    {
    }

    public function boot(){
        $this->publishes([
            __DIR__.'/Console/Commands/MakeRouteGroupCommand.php' => app_path('Console/Commands/MakeRouteGroupCommand.php'),
            __DIR__.'/../stubs/route.empty.stub' => app_path('../stubs/route.empty.stub'),
            __DIR__.'/../stubs/route.group-only.stub' => app_path('../stubs/route.group-only.stub'),
            __DIR__.'/../stubs/route.resource.stub' => app_path('../stubs/route.resource.stub'),
        ],'routeorganizer');
    }

}
