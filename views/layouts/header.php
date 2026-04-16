<?php
// views/layouts/header.php
if (!defined('APP_URL')) {
    $configPath = __DIR__ . '/../../config/config.php';
    if (file_exists($configPath)) {
        require_once $configPath;
    } else {
        define('APP_URL', 'http://localhost/api_educativa');
    }
}

// Obtener ruta base correcta (con o sin trailing slash)
$ruta_base = rtrim(APP_URL, '/');

// Verificar sesión para mostrar datos de usuario
$user = null;
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user_id'])) {
    $user = [
        'nombre' => $_SESSION['nombre'] ?? 'Usuario',
        'rol' => $_SESSION['rol'] ?? 'estudiante'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Centro Educativo ISW-306'); ?></title>
    <!-- Estilos generales de la aplicación -->
    <!-- <link rel="stylesheet" href="<?php echo $ruta_base; ?>/assets/css/styles.css"> -->
    <link href="/api_educativa/assets/css/styles.css" rel="stylesheet">

    <link href="/api_educativa/assets/css/styles.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="<?php echo $ruta_base; ?>/?page=home" class="logo-link">
                        <img src="<?php echo $ruta_base; ?>/assets/img/logo.png" alt="Logo" class="logo-img" onerror="this.style.display='none'">
                        <span class="logo-nombre">Centro Educativo</span>
                    </a>
                </div>

                <ul class="nav-links">
                    <li><a href="<?php echo $ruta_base; ?>/?page=home">Inicio</a></li>
                    <li><a href="<?php echo $ruta_base; ?>/?page=nosotros">Nosotros</a></li>
                    <li><a href="<?php echo $ruta_base; ?>/?page=admisiones">Admisiones</a></li>
                    <li><a href="<?php echo $ruta_base; ?>/?page=vida-estudiantil">Vida Estudiantil</a></li>

                    <?php if ($user): ?>
                        <li>
                            <a href="<?php echo $ruta_base; ?>/?page=<?php echo htmlspecialchars($user['rol']); ?>/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Panel
                            </a>
                        </li>
                        <li>
                            <span class="user-welcome">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($user['nombre']); ?>
                                <small>(<?php echo htmlspecialchars(ucfirst($user['rol'])); ?>)</small>
                            </span>
                        </li>
                        <li>
                            <a href="<?php echo $ruta_base; ?>/?page=auth/logout" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo $ruta_base; ?>/?page=login" class="btn-login">Ingresar</a></li>
                        <!-- <li><a href="<?php echo $ruta_base; ?>/?page=register" class="btn-register">Registrarse</a></li> -->
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Mensajes de sesión -->
    <?php if (session_status() === PHP_SESSION_ACTIVE): ?>
        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    <?php endif; ?>

    <main class="container">