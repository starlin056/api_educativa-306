<?php
// index.php - Router Principal
// @phpstan-ignore-file

// Iniciar sesión PRIMERO
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cargar configuración ANTES que cualquier otra cosa
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

// Autoloader simple para controllers, models y middleware
spl_autoload_register(function (string $class): void {
    $paths = [
        BASE_PATH . '/controllers/',
        BASE_PATH . '/models/',
        BASE_PATH . '/middleware/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Enrutamiento
$page = $_GET['page'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

// Mapa de rutas
$routes = [
    // Públicas
    'home' => ['HomeController', 'index', null, ['GET']],
    'nosotros' => ['HomeController', 'about', null, ['GET']],
    'admisiones' => ['HomeController', 'admisiones', null, ['GET']],

    'login' => ['AuthController', 'showLogin', 'AuthMiddleware::requireGuest', ['GET']],
    'register' => ['AuthController', 'showRegister', 'AuthMiddleware::requireGuest', ['GET']],

    // Auth actions (POST)
    'auth/login' => ['AuthController', 'login', 'AuthMiddleware::requireGuest', ['POST']],
    'auth/register' => ['AuthController', 'register', 'AuthMiddleware::requireGuest', ['POST']],
    'auth/logout' => ['AuthController', 'logout', null, ['GET', 'POST']],

    // Dashboards protegidos
    'admin/dashboard' => ['HomeController', 'dashboard', fn() => AuthMiddleware::requireRole('admin'), ['GET']],
    'docente/dashboard' => ['HomeController', 'dashboard', fn() => AuthMiddleware::requireRole(['admin', 'docente']), ['GET']],
    'estudiante/dashboard' => ['HomeController', 'dashboard', fn() => AuthMiddleware::requireRole(['admin', 'estudiante']), ['GET']],
    'padre/dashboard' => ['HomeController', 'dashboard', fn() => AuthMiddleware::requireRole(['admin', 'padre']), ['GET']],


    ///

    // Gestión de Usuarios (solo admin)
    'admin/users' => ['UserController', 'index', fn() => AuthMiddleware::requireRole('admin'), ['GET']],
    'admin/users/create' => ['UserController', 'create', fn() => AuthMiddleware::requireRole('admin'), ['GET']],
    'admin/users/store' => ['UserController', 'store', fn() => AuthMiddleware::requireRole('admin'), ['POST']],
    'admin/users/edit' => ['UserController', 'edit', fn() => AuthMiddleware::requireRole('admin'), ['GET']],
    'admin/users/update' => ['UserController', 'update', fn() => AuthMiddleware::requireRole('admin'), ['POST']],
    'admin/users/delete' => ['UserController', 'delete', fn() => AuthMiddleware::requireRole('admin'), ['POST']],
    'admin/users/toggle-status' => ['UserController', 'toggleStatus', fn() => AuthMiddleware::requireRole('admin'), ['POST']],
    // admin servicios CRUD

    'admin/services' => [
        'ServiceController',
        'index',
        fn() => AuthMiddleware::requireRole('admin'),
        ['GET']
    ],

    'admin/create-service' => [
        'ServiceController',
        'create',
        fn() => AuthMiddleware::requireRole('admin'),
        ['GET']
    ],

    'admin/store-service' => [
        'ServiceController',
        'store',
        fn() => AuthMiddleware::requireRole('admin'),
        ['POST']
    ],

    'admin/edit-service' => [
        'ServiceController',
        'edit',
        fn() => AuthMiddleware::requireRole('admin'),
        ['GET']
    ],

    'admin/update-service' => [
        'ServiceController',
        'update',
        fn() => AuthMiddleware::requireRole('admin'),
        ['POST']
    ],

    'admin/toggle-service' => [
        'ServiceController',
        'toggle',
        fn() => AuthMiddleware::requireRole('admin'),
        ['GET']
    ],
];

// Procesar ruta
if (array_key_exists($page, $routes)) {
    $route = $routes[$page];
    [$controllerName, $action, $middleware, $allowedMethods] = $route;

    // Verificar método HTTP
    if (!in_array($method, $allowedMethods)) {
        http_response_code(405);
        include BASE_PATH . '/views/errors/404.php';
        exit;
    }

    // Ejecutar middleware
    if ($middleware) {
        if (is_callable($middleware)) {
            $middleware();
        } elseif (is_string($middleware) && strpos($middleware, '::') !== false) {
            call_user_func($middleware);
        }
    }

    // Ejecutar controller
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $action)) {
            $params = [];
            if ($action === 'dashboard') {
                $role = explode('/', $page)[0] ?? null;
                $params = [$role !== 'admin' ? $role : null];
            }
            call_user_func_array([$controller, $action], $params);
            exit;
        }
    }
}

// 404
http_response_code(404);
include BASE_PATH . '/views/errors/404.php';
