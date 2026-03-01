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

            '/fechamento/fechar/:id' => [
                'HomeController@fechar',
            ],

            '/gerartickets' => [
                'HomeController@gerarTicket',
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
        $url = $this->getUrl();

        foreach ($this->routes as $route => $controllerAction) {
            // Converte rota em regex
            $pattern = preg_replace('#:([a-zA-Z0-9_]+)#', '(?P<$1>[0-9]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $url, $matches)) {
                // Extrai parâmetros nomeados
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }

                $parts = explode('@', $controllerAction[0]);
                $controller = $parts[0];
                $action = $parts[1];

                if (!class_exists($controller)) {
                    die("<h1>Erro: Controller '{$controller}' não encontrado.</h1>");
                }

                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $action)) {
                    die("<h1>Erro: Método '{$action}' não encontrado em '{$controller}'.</h1>");
                }

                return call_user_func_array([$controllerInstance, $action], $params);
            }
        }

        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
    }
    
}
