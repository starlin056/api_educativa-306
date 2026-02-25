
<?php
// Enrutador Simple para Etapa 3 (Con Acciones)
$pagina = isset($_GET['page']) ? $_GET['page'] : 'home';
$accion = isset($_GET['action']) ? $_GET['action'] : null;

// Mapeo de rutas vistas
$vistas = [
    'home' => __DIR__ . '/views/home/home.php',
    'ofertaacademica' => __DIR__ . '/views/ofertaacademica/index.php',

    'nosotros' => __DIR__ . '/views/nosotros/index.php',
    'login' => __DIR__ . '/views/login/login.php',
    'register' => __DIR__ . '/views/login/register.php'
];

if (array_key_exists($pagina, $vistas) && file_exists($vistas[$pagina])) {
    include $vistas[$pagina];
} else {
    // Redirigir a pagina de error 
    include __DIR__ . '/views/errors/404.php'; // Vista d error 404 
}


