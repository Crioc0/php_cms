<?php

class  Route
{
    protected static array $routes = [];
    protected static array $route = [];

    /**
     * add route to $routes array
     * @param $regexp
     * @param $route array
     * @return void
     */
    public static function add($regexp, array $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    /**
     * return all routes in $routes array
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * return current route
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Сравнивает текущий URL с регулярными выражениями в списке доступных YRL
     * @param string $url входящий URL
     * @return bool
     */
    public static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                self::$route = $route;
                debug(self::$route);
                return true;
            }
        }
        return false;
    }

    /**
     * Перенаправляет URL по корректному маршруту
     * @param string $url входящий URL
     * @return void
     */
    public static function dispatch($url)
    {
        if (self::matchRoute($url)) {
            $controller = self::upperCamelCase(self::$route['controller']);
            if (class_exists($controller)) {
                $cObj = new $controller;
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($cObj, $action )){
                    $cObj->$action();
                } else {
                    echo "Метод <b>$controller::$action</b> не найден";
                }
            } else {
                echo "Контроллер <b>$controller</b> не найден";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

    protected static function upperCamelCase($name)
    {
        return $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

    }

    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }
}