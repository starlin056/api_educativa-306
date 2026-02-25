<!-- views/login/register.php -->
 <!-- carga de header -->
<?php include __DIR__ . '/../layouts/header.php'; ?>

<head>
   <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<div class="register-container">
    <h2>Crear cuenta</h2>
    <form action="procesar_registro.php" method="post" class="register-form">
        <div class="form-group">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirmar">Confirmar contraseña</label>
            <input type="password" id="confirmar" name="confirmar" required>
        </div>

        <button type="submit" class="btn">Registrarse</button>
    </form>

    <p class="redirect">
        ¿Ya tienes cuenta? <a href="index.php?page=login">Inicia sesión aquí</a>
    </p>
</div>
<!-- Footer  -->
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
