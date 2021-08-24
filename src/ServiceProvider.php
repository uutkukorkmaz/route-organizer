<?php


namespace Uutkukorkmaz\RouteOrganizer;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Console/Commands/MakeRouteGroupCommand.php' => app_path('Console/Commands/MakeRouteGroupCommand.php'),
            __DIR__ . '/../stubs/route.empty.stub' => base_path('stubs/route.empty.stub'),
            __DIR__ . '/../stubs/route.group-only.stub' => base_path('stubs/route.group-only.stub'),
            __DIR__ . '/../stubs/route.resource.stub' => base_path('stubs/route.resource.stub'),
        ], 'route-organizer');
    }

}
