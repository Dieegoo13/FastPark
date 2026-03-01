<?php

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->initRoutes();
        $this->dispatch();
    }

    /**
     * Define as rotas do projeto
     */
    protected function initRoutes()
    {
        $this->routes = [

            '/' => [
                'HomeController@index',
            ],

             '/fechamento' => [
                'FechamentoController@index',
             ],


        ];
    }

    /**
     * Retorna a rota atual (sem query string)
     */
    private function getUrl()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));

        // Remove o prefixo do caminho base (pra funcionar em InfinityFree)
        $path = str_replace($base, '', $path);

        return rtrim($path, '/') ?: '/';
    }

    /**
     * Executa o roteamento
     */
    private function dispatch()
    {
        $path = $this->getUrl();

        if ($path == '') {
            $path = "/";
        }

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo "<h1>404 - Página não encontrada</h1>";
            return;
        }

        $controllerAction = $this->routes[$path][0];

        $partes = explode('@', $controllerAction);
        $controller = $partes[0];
        $action = $partes[1];

        if (!class_exists($controller)) {
            die("<h1>Erro: Controller '{$controller}' não encontrado.</h1>");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            die("<h1>Erro: Método '{$action}' não encontrado em '{$controller}'.</h1>");
        }

        return call_user_func([$controllerInstance, $action]);
    }
    
}
