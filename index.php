<?php
// index.php - Router Principal Centro Educativo ISW-306
// @phpstan-ignore-file

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

/*
|--------------------------------------------------------------------------
| AUTOLOADER
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| PARÁMETROS DE LA URL
|--------------------------------------------------------------------------
*/

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| CONSTRUIR RUTA REAL
|--------------------------------------------------------------------------
| ejemplo:
| page=admin/services
| action=store
| → admin/services/store
*/

$routeKey = $action ? $page . '/' . $action : $page;

/*
|--------------------------------------------------------------------------
| MAPA DE RUTAS
|--------------------------------------------------------------------------
*/

$routes = [

    // Públicas
    'home' => [
        'controller' => 'HomeController',
        'action' => 'index',
        'middleware' => null,
        'methods' => ['GET']
    ],
    'nosotros' => [
        'controller' => 'HomeController',
        'action' => 'about',
        'middleware' => null,
        'methods' => ['GET']
    ],
    'admisiones' => [
        'controller' => 'HomeController',
        'action' => 'admisiones',
        'middleware' => null,
        'methods' => ['GET']
    ],

  'vida-estudiantil' => [
   'controller' => 'HomeController', 
     'action' => 'vidaEstudiantil',
     'middleware'=> null, 
     'methods' => ['GET']
     
     ],


    // Auth
    'login' => [
        'controller' => 'AuthController',
        'action' => 'showLogin',
        'middleware' => 'AuthMiddleware::requireGuest',
        'methods' => ['GET']
    ],
    'register' => [
        'controller' => 'AuthController',
        'action' => 'showRegister',
        'middleware' => 'AuthMiddleware::requireGuest',
        'methods' => ['GET']
    ],
    'auth/login' => [
        'controller' => 'AuthController',
        'action' => 'login',
        'middleware' => 'AuthMiddleware::requireGuest',
        'methods' => ['POST']
    ],
    'auth/register' => [
        'controller' => 'AuthController',
        'action' => 'register',
        'middleware' => 'AuthMiddleware::requireGuest',
        'methods' => ['POST']
    ],
    'auth/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => null,
        'methods' => ['GET', 'POST']
    ],

    // Dashboards protegidos
    'admin/dashboard' => [
        'controller' => 'HomeController',
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'docente/dashboard' => [
        'controller' => 'HomeController',
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['GET']
    ],
    'estudiante/dashboard' => [
        'controller' => 'HomeController',
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'estudiante']),
        'methods' => ['GET']
    ],
    'padre/dashboard' => [
        'controller' => 'HomeController',
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'padre']),
        'methods' => ['GET']
    ],

    // Gestión de Usuarios (solo admin)
    'admin/users' => [
        'controller' => 'UserController',
        'action' => 'index',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/users/create' => [
        'controller' => 'UserController',
        'action' => 'create',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/users/store' => [
        'controller' => 'UserController',
        'action' => 'store',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/users/edit' => [
        'controller' => 'UserController',
        'action' => 'edit',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/users/update' => [
        'controller' => 'UserController',
        'action' => 'update',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/users/delete' => [
        'controller' => 'UserController',
        'action' => 'delete',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/users/toggle-status' => [
        'controller' => 'UserController',
        'action' => 'toggleStatus',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],

    // Gestión de Servicios (solo admin)
    'admin/services' => [
        'controller' => 'ServiceController',
        'action' => 'index',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/services/create' => [
        'controller' => 'ServiceController',
        'action' => 'create',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/services/store' => [
        'controller' => 'ServiceController',
        'action' => 'store',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/services/edit' => [
        'controller' => 'ServiceController',
        'action' => 'edit',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['GET']
    ],
    'admin/services/update' => [
        'controller' => 'ServiceController',
        'action' => 'update',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/services/delete' => [
        'controller' => 'ServiceController',
        'action' => 'delete',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],
    'admin/services/toggle' => [
        'controller' => 'ServiceController',
        'action' => 'toggle',
        'middleware' => fn() => AuthMiddleware::requireRole('admin'),
        'methods' => ['POST']
    ],

    'nosotros' => [
        'controller' => 'HomeController',
        'action' => 'about',
        'middleware' => null,
        'methods' => ['GET']
    ],

    'admisiones' => [
        'controller' => 'HomeController',
        'action' => 'admisiones',
        'middleware' => null,
        'methods' => ['GET']
    ],
    
    'cursos' => [
        'controller' => 'EstudianteController',
        'action' => 'cursos',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'estudiante']),
        'methods' => ['GET']
    ],


    // Gestión de Aulas Docente

    'docente/dashboard' => [
        'controller' => 'DocenteController',   // ✅ Cambia esto
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['GET']
    ],
    'docente/aulas' => [
        'controller' => 'DocenteController',
        'action' => 'aula',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['GET']
    ],
    'docente/aulas/view' => [
        'controller' => 'DocenteController',
        'action' => 'aula',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['GET']
    ],
    'docente/aulas/store' => [
        'controller' => 'DocenteController',
        'action' => 'store',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['POST']
    ],
    'docente/aulas/addStudent' => [
        'controller' => 'DocenteController',
        'action' => 'addStudent',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['POST']
    ],
    'docente/aulas/removeStudent' => [
        'controller' => 'DocenteController',
        'action' => 'removeStudent',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['POST']
    ],
    'docente/aulas/inscribirServicio' => [
        'controller' => 'DocenteController',
        'action' => 'inscribirServicio',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'docente']),
        'methods' => ['POST']
    ],



    // Estudiante
    'estudiante/dashboard' => [
        'controller' => 'EstudianteController',
        'action' => 'dashboard',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'estudiante']),
        'methods' => ['GET']
    ],
    'estudiante/inscribir' => [
        'controller' => 'EstudianteController',
        'action' => 'inscribir',
        'middleware' => fn() => AuthMiddleware::requireRole(['admin', 'estudiante']),
        'methods' => ['POST']
    ],

];


/*
|--------------------------------------------------------------------------
| EJECUTAR RUTA
|--------------------------------------------------------------------------
*/

if (isset($routes[$routeKey])) {

    $route = $routes[$routeKey];

    $controllerName   = $route['controller'];
    $controllerAction = $route['action'];
    $middleware       = $route['middleware'];
    $allowedMethods   = $route['methods'];

    // Validar método HTTP
    if (!in_array($method, $allowedMethods)) {
        http_response_code(405);
        echo "405 - Method Not Allowed";
        exit;
    }

    // Middleware
    if ($middleware) {
        if (is_callable($middleware)) {
            $middleware();
        } elseif (is_string($middleware) && strpos($middleware, '::') !== false) {
            call_user_func($middleware);
        }
    }

    // Ejecutar Controller
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $controllerAction)) {
            // Si la ruta requiere un parámetro id, pásalo
            if (isset($_GET['id'])) {
                $controller->$controllerAction((int)$_GET['id']);
            } else {
                $controller->$controllerAction();
            }
            exit;
        }

        error_log("Método {$controllerAction} no existe en {$controllerName}");
    }

    error_log("Controller {$controllerName} no encontrado");
}



http_response_code(404);
include BASE_PATH . '/views/errors/404.php';
