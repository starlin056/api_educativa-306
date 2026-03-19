<?php
// controllers/Controller.php

class Controller
{
    protected array $data = [];
    protected $db;

    public function __construct()
    {
        require_once BASE_PATH . '/config/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Pasar datos a la vista
     */
    public function with($key, $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Renderizar vista con o sin layout
     */
    public function view(string $viewPath, bool $layout = true): void
    {
        extract($this->data, EXTR_SKIP);

        if ($layout) {
            include BASE_PATH . '/views/layouts/header.php';
        }

        include BASE_PATH . "/views/{$viewPath}.php";

        if ($layout) {
            include BASE_PATH . '/views/layouts/footer.php';
        }
    }

    /**
     * Redirigir a otra URL
     */
    public function redirect(string $url): void
    {
        if (strpos($url, 'http') !== 0) {
            $url = Config::env('APP_URL') . '/' . ltrim($url, '/');
        }

        header("Location: {$url}");
        exit;
    }

    /**
     * Guardar mensaje de éxito en sesión
     */
    protected function success(string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['success'] = $message;
    }

    /**
     * Respuesta JSON
     */
    public function json(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }


    protected function error(string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['error'] = $message;
    }

    protected function warning(string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['warning'] = $message;
    }
}
