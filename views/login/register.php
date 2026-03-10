<?php
// views/login/register.php
// Formulario de registro - SIN duplicar estructura HTML

$title = 'Crear Cuenta';
// No incluimos header.php aquí porque lo maneja el Controller
// Pero si lo usas directo, descomenta la línea de abajo:
// include __DIR__ . '/../layouts/header.php'; 
?>

<!-- Si usas esta vista directamente (sin controller), incluye header: -->
<?php 
if (!isset($noLayout) || $noLayout === false) {
    include __DIR__ . '/../layouts/header.php'; 
}
?>

<section class="register-container">
    <h2><i class="fas fa-user-plus"></i> Crear Cuenta de Estudiante</h2>
    
    <form action="<?php echo APP_URL; ?>/?page=auth/register" method="POST" class="form-validate">
        
        <div class="form-group">
            <label for="nombre">
                <i class="fas fa-user"></i> Nombre Completo
            </label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                   placeholder="Ej: Pedro Starlin Ureña Cruz" 
                   required minlength="3" autocomplete="name">
        </div>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Correo Electrónico
            </label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   placeholder="usuario@centro.edu" 
                   required autocomplete="email">
            <small class="form-help">Usa un correo válido para recuperar tu cuenta</small>
        </div>

        <div class="form-group">
            <label for="telefono">
                <i class="fas fa-phone"></i> Teléfono (Opcional)
            </label>
            <input type="tel" id="telefono" name="telefono" 
                   value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
                   placeholder="+555 123 4567" 
                   pattern="[0-9+\-\s]{7,15}" autocomplete="tel">
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
                   required minlength="6" autocomplete="new-password">
            <small class="form-help">Usa mayúsculas, números y símbolos para mayor seguridad</small>
        </div>

        <div class="form-group">
            <label for="password_confirm">
                <i class="fas fa-lock"></i> Confirmar Contraseña
            </label>
            <input type="password" id="password_confirm" name="password_confirm" 
                   placeholder="Repite tu contraseña" 
                   required autocomplete="new-password">
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

<script>
// Validación en tiempo real de coincidencia de contraseñas
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirm');
    const matchMsg = document.getElementById('password-match');
    
    function checkMatch() {
        if (confirm.value.length === 0) {
            matchMsg.textContent = '';
            matchMsg.className = 'form-help';
            return;
        }
        
        if (password.value === confirm.value) {
            matchMsg.textContent = '✓ Las contraseñas coinciden';
            matchMsg.className = 'form-help success';
            confirm.setCustomValidity('');
        } else {
            matchMsg.textContent = '✗ Las contraseñas no coinciden';
            matchMsg.className = 'form-help error';
            confirm.setCustomValidity('Las contraseñas no coinciden');
        }
    }
    
    password.addEventListener('input', checkMatch);
    confirm.addEventListener('input', checkMatch);
    
    // Validación del formulario
    document.querySelector('.form-validate').addEventListener('submit', function(e) {
        if (password.value.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            password.focus();
            return;
        }
        
        if (password.value !== confirm.value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            confirm.focus();
            return;
        }
    });
});
</script>

<?php 
// Incluir footer solo si no se está usando layout completo
if (!isset($noLayout) || $noLayout === false) {
    include __DIR__ . '/../layouts/footer.php'; 
}
?>