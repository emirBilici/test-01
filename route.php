<?php

class Route
{

    /**
     * @return array|mixed|string|string[]
     */
    public static function parse_url()
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname != '/' ? $dirname : null;
        $basename = basename($_SERVER['SCRIPT_NAME']);
        $request_uri = str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']);
        $request_uri = explode('?', $request_uri);
        return $request_uri[0];
    }

    /**
     * @param $url
     * @param $callback
     * @param $method
     * @return void
     */
    public static function run($url, $callback, $method = 'get')
    {
        $method = explode('|', strtoupper($method));

        if (in_array($_SERVER['REQUEST_METHOD'], $method)) {

            $patterns = [
                '{url}' => '([0-9a-zA-Z]+)',
                '{id}' => '([0-9]+)',
                '{user}' => '([0-9a-zA-Z-_]+)',
                '{postId}' => '([a-zA-Z]+)'
            ];

            $url = str_replace(array_keys($patterns), array_values($patterns), $url);

            $request_uri = self::parse_url();
            if (preg_match('@^' . $url . '$@', $request_uri, $parameters)) {
                unset($parameters[0]);

                if (is_callable($callback)) {
                    call_user_func_array($callback, $parameters);
                } else {

                    $controller = explode('@', $callback);
                    $className = explode('/', $controller[0]);
                    $className = end($className);
                    $controllerFile = __DIR__ . '/controller/' . strtolower($controller[0]) . '.php';

                    if (file_exists($controllerFile)) {
                        require $controllerFile;
                        call_user_func_array([new $className, $controller[1]], $parameters);
                    }

                }

            }

        }

    }

}