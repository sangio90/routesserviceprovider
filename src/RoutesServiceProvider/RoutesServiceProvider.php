<?php

namespace RoutesServiceProvider;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RoutesServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $applicationNamespace = $pimple["app.namespace"] . 'Controller\\';

        foreach ($pimple["app.routes"] as $routeName => $routeDetails) {
            if (!is_array($routeDetails)) {
                throw new \Exception("Routes error");
            }

            $controllerName = $applicationNamespace . $routeName . "Controller";
            $controller = new $controllerName($pimple);

            foreach ($routeDetails["actions"] as $actionName => $actionDetails) {
                $method = $actionDetails["method"];
                $url = $actionDetails["url"];
                $pimple->$method($url, function() use ($actionName, $controller) {
                    $methodName = $actionName . "Action";
                    return $controller->$methodName();
                });
            }
        }
    }
}