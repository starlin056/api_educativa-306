<?php $title = 'Iniciar Sesión'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="login-container">
    <h2><i class="fas fa-sign-in-alt"></i> Acceso al Portal</h2>

    <!--  Agregado: id, class form-validate, novalidate para JS -->
    <form id="login-form" action="<?php echo APP_URL; ?>/?page=auth/login" method="POST" class="form-validate" novalidate>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Correo Institucional
            </label>

            <input type="email" id="email" name="email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                placeholder="usuario@centro.edu"
                required autocomplete="email"
                data-validation="required,email">
            <small class="form-help">Usa tu correo institucional registrado</small>
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <!--  Agregado: minlength y data-validation -->
            <input type="password" id="password" name="password"
                placeholder="••••••••" required autocomplete="current-password"
                minlength="6" data-validation="required,minLength:6">
            <small class="form-help"><a href="#" id="forgot-password">¿Olvidaste tu contraseña?</a></small>
        </div>

        <!-- Checkbox "Recordarme" para persistencia -->
        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" id="remember" name="remember" value="1">
                <span>Recordar mi sesión en este dispositivo</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
            <a href="<?php echo APP_URL; ?>/?page=register" class="btn btn-secondary">
                Crear cuenta
            </a>
        </div>
    </form>

    <div class="login-info">
        <p><strong>Roles disponibles:</strong></p>
        <ul>
            <li><i class="fas fa-user-shield"></i> <strong>Admin:</strong> Gestión completa del sistema</li>
            <li><i class="fas fa-chalkboard-teacher"></i> <strong>Docente:</strong> Calificaciones y cursos</li>
            <li><i class="fas fa-user-graduate"></i> <strong>Estudiante:</strong> Inscripciones y notas</li>
            <li><i class="fas fa-users"></i> <strong>Padre:</strong> Seguimiento de hijos</li>
        </ul>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>