<?php


namespace Uutkukorkmaz\RouteOrganizer;


class Organizer
{

    public static function register(array $routeGroups){
        foreach($routeGroups as $routeGroup){
            /**
             * @var RegistersRouteGroup $routeGroup
            */
            $routeGroup::register();
        }
    }
    
}