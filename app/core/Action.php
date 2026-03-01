<?php

class Action
{
    protected $layout = 'default';
    protected $db;
    protected $ticketModel;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->ticketModel = new Tickets();
    }

    protected function getLayoutDataInfos()
    {
        return [
            'total' => $this->ticketModel->getTotalArrecadado(),
            'veiculosHoje' => $this->ticketModel->getTotalVeiculosHoje()
        ];
    }

    protected function view($view, $layout = true, $data = [])
    {
        if ($layout === false) {
            $viewPath = APP_ROOT . '/app/views/' . $view . '.phtml';
        } else {
            $viewPath = APP_ROOT . '/app/views/' . $view . '/' . $view . '.phtml';
        }

        if (!file_exists($viewPath)) {
            die("View não encontrada: {$viewPath}");
        }

        ob_start();
        extract($data);
        require $viewPath;
        $conteudo = ob_get_clean();

        if ($layout === false) {
            echo $conteudo;
            return;
        }
        
        $layoutData = $this->getLayoutDataInfos();
        extract($layoutData);

        $layoutFile = APP_ROOT . '/app/views/layouts/' . $this->layout . '.phtml';
        require $layoutFile;
    }


    protected function redirect($path)
    {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }

    protected function json($data)
    {
        http_response_code(200);

        header('Content-Type: application/json');
        echo json_decode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }


    protected function isLogged()
    {

        return isset($_SESSION['user_id']);
    }

    protected function requireAuth()
    {

        if (!$this->isLogged()) {
            $this->redirect('/');
        }
    }

    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Formata tempo em minutos e segundos
     */
    protected function formatTime($seconds)
    {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf("%02d:%02d", $minutes, $secs);
    }

    /**
     * Mensagem de sucesso na sessão
     */
    protected function setSuccess($message)
    {
        $_SESSION['flash_success'] = $message;
    }

    /**
     * Mensagem de erro na sessão
     */
    protected function setError($message)
    {
        $_SESSION['flash_error'] = $message;
    }

    /**
     * Pega e limpa mensagem de sucesso
     */
    protected function getSuccess()
    {
        $message = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        return $message;
    }

    /**
     * Pega e limpa mensagem de erro
     */
    protected function getError()
    {
        $message = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        return $message;
    }
}
