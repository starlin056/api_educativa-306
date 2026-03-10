<?php
// controllers/Controller.php
// Clase base para controladores - Manejo de vistas y respuestas
// @phpstan-ignore-file

class Controller {
    protected array $data = [];
    
    /**
     * Pasar datos a la vista
     */
    public function with($key, $value = null): self {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }
    
    /**
     * Renderizar vista con layout
     */
    public function view(string $viewPath, bool $layout = true): void {
        extract($this->data);
        
        if ($layout) {
            include BASE_PATH . '/views/layouts/header.php';
            include BASE_PATH . "/views/{$viewPath}.php";
            include BASE_PATH . '/views/layouts/footer.php';
        } else {
            include BASE_PATH . "/views/{$viewPath}.php";
        }
    }
    
    /**
     * Redireccionar a URL
     */
    public function redirect(string $url): void {
        if (strpos($url, 'http') !== 0) {
            $url = (defined('APP_URL') ? APP_URL : 'http://localhost/api_educativa') . '/' . ltrim($url, '/');
        }
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Respuesta JSON
     */
    public function json(array $data, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Manejar errores
     */
    public function error(string $message, int $code = 400): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['error'] = $message;
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? ((defined('APP_URL') ? APP_URL : '') . '/?page=home');
        header("Location: {$referer}");
        exit;
    }
    
    /**
     * Mensaje de éxito
     */
    public function success(string $message): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['success'] = $message;
        }
    }
}