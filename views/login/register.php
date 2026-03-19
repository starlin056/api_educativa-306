<?php
// views/login/register.php
$title = 'Crear Cuenta';
if (!isset($noLayout) || $noLayout === false) {
    include __DIR__ . '/../layouts/header.php';
}
?>

<section class="register-container">
    <h2><i class="fas fa-user-plus"></i> Crear Cuenta de Estudiante</h2>

    <!--  Agregado: id, form-validate, novalidate -->
    <form id="register-form" action="<?php echo APP_URL; ?>/?page=auth/register" method="POST" class="form-validate" novalidate>

        <div class="form-group">
            <label for="nombre">
                <i class="fas fa-user"></i> Nombre Completo
            </label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                placeholder="Ej: Pedro Starlin Ureña Cruz"
                required minlength="3" autocomplete="name"
                data-validation="required,minLength:3">
        </div>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Correo Electrónico
            </label>
            <input type="email" id="email" name="email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                placeholder="usuario@centro.edu"
                required autocomplete="email"
                data-validation="required,email">
            <small class="form-help">Usa un correo válido para recuperar tu cuenta</small>
        </div>

        <div class="form-group">
            <label for="telefono">
                <i class="fas fa-phone"></i> Teléfono (Opcional)
            </label>
            <input type="tel" id="telefono" name="telefono"
                value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
                placeholder="+555 123 4567"
                pattern="[0-9+\-\s]{7,15}" autocomplete="tel"
                data-validation="phone">
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">
                <i class="fas fa-calendar"></i> Fecha de Nacimiento (Opcional)
            </label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? ''); ?>"
                max="<?php echo date('Y-m-d', strtotime('-10 years')); ?>">
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <input type="password" id="password" name="password"
                placeholder="Mínimo 6 caracteres"
                required minlength="6" autocomplete="new-password"
                data-validation="required,minLength:6">
            <small class="form-help">Usa mayúsculas, números y símbolos para mayor seguridad</small>
        </div>

        <div class="form-group">
            <label for="password_confirm">
                <i class="fas fa-lock"></i> Confirmar Contraseña
            </label>
            <input type="password" id="password_confirm" name="password_confirm"
                placeholder="Repite tu contraseña"
                required autocomplete="new-password"
                data-validation="required,match:password">
            <small id="password-match" class="form-help"></small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrarse
            </button>
            <a href="<?php echo APP_URL; ?>/?page=login" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Login
            </a>
        </div>
    </form>

    <p class="login-redirect">
        ¿Ya tienes cuenta?
        <a href="<?php echo APP_URL; ?>/?page=login"><strong>Inicia sesión aquí</strong></a>
    </p>
</section>

<?php
if (!isset($noLayout) || $noLayout === false) {
    include __DIR__ . '/../layouts/footer.php';
}
?>