<?php
// middleware/AuthMiddleware.php
// Protección de rutas según autenticación y roles
// @phpstan-ignore-file

class AuthMiddleware
{

    /**
     * Verificar que usuario esté autenticado
     */
    public static function requireAuth(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión para acceder';
            $baseUrl = defined('APP_URL') ? APP_URL : 'http://localhost/api_educativa';
            header('Location: ' . $baseUrl . '/?page=login');
            exit;
        }

        // Verificar timeout de sesión
        $lifetime = defined('SESSION_LIFETIME') ? (int)SESSION_LIFETIME : 120;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > ($lifetime * 60)) {
            session_unset();
            session_destroy();
            $_SESSION['error'] = 'Sesión expirada por inactividad';
            $baseUrl = defined('APP_URL') ? APP_URL : 'http://localhost/api_educativa';
            header('Location: ' . $baseUrl . '/?page=login');
            exit;
        }

        // Actualizar última actividad
        $_SESSION['last_activity'] = time();
    }

    /**
     * Verificar rol específico
     */
    public static function requireRole($allowedRoles): void
    {
        self::requireAuth();

        $userRole = $_SESSION['rol'] ?? null;
        $allowedRoles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];

        if (!in_array($userRole, $allowedRoles)) {
            $_SESSION['error'] = 'Acceso denegado: No tienes permisos suficientes';
            $baseUrl = defined('APP_URL') ? APP_URL : 'http://localhost/api_educativa';

            $redirects = [
                'admin' => '?page=admin/dashboard',
                'docente' => '?page=docente/dashboard',
                'estudiante' => '?page=estudiante/dashboard',
                'padre' => '?page=padre/dashboard'
            ];
            $redirect = $redirects[$userRole] ?? '?page=home';
            header('Location: ' . $baseUrl . '/' . $redirect);
            exit;
        }
    }

    /**
     * Verificar que NO esté autenticado (para login/register)
     */
    public static function requireGuest(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $baseUrl = defined('APP_URL') ? APP_URL : 'http://localhost/api_educativa';
            header('Location: ' . $baseUrl . '/?page=home');
            exit;
        }
    }
}
