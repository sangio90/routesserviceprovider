<?php

namespace ConfigServiceProvider;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RoutesServiceProvider implements ServiceProviderInterface
{
    protected $applicationNameSpace = '';

    public function register(Container $pimple)
    {
        foreach ($pimple["app.routes"] as $routeName => $routeDetails) {
            if (!is_array($routeDetails)) {
                throw new \Exception("Routes error");
            }

            $controllerName = $this->applicationNameSpace . $routeName . "Controller";
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

    /**
     * @param mixed $applicationNameSpace
     */
    public function setApplicationNameSpace($applicationNameSpace)
    {
        $this->applicationNameSpace = $applicationNameSpace;
    }


}