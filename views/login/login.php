<?php $title = 'Iniciar Sesión'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="login-container">
    <h2><i class="fas fa-sign-in-alt"></i> Acceso al Portal</h2>
    
    <form action="<?php echo APP_URL; ?>/?page=auth/login" method="POST" class="form-validate">
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Correo Institucional
            </label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   placeholder="usuario@centro.edu" 
                   required autocomplete="email">
            <small class="form-help">Usa tu correo institucional registrado</small>
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <input type="password" id="password" name="password" 
                   placeholder="••••••••" required autocomplete="current-password">
            <small class="form-help"><a href="#">¿Olvidaste tu contraseña?</a></small>
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

<script>
// Validación básica en cliente (Etapa 2)
document.querySelector('.form-validate').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (password.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres');
        document.getElementById('password').focus();
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>