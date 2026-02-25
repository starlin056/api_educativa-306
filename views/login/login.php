<!-- views/login/login.php -->
 <!-- Carga de header -->
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>

    <main>
        <section class="login-container">
            <h2 style="text-align: center; color: var(--color-primary); margin-bottom: 1.5rem;">Acceso al Portal</h2>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="email">Correo Institucional</label>
                    <input type="email" id="email" name="email" placeholder="usuario@centro.edu" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>

                <div class="form-group">
                    <label for="role">Seleccionar Rol </label>
                    <select id="role" name="role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="estudiante">Estudiante</option>
                        <option value="docente">Docente</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Ingresar</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem;">
                ¿No tienes cuenta? <a href="index.php?page=register" style="color: var(--color-secondary);">Regístrate aquí</a>
            </p>
        </section>
    </main>
<!-- Footer  -->
        <?php include __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>