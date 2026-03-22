<?php // views/estudiante/cursos.php ?>

<!-- NAVBAR SUPERIOR -->
<nav class="student-navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <i class="fas fa-graduation-cap navbar-icon"></i>
            <span class="navbar-title">Sistema Educativo</span>
        </div>
        
        <div class="navbar-menu">
            <button class="navbar-item" data-menu="calificaciones" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/calificaciones'; return false;">
                <i class="fas fa-star"></i>
                <span>Calificaciones</span>
            </button>
                <button class="navbar-item" data-menu="examenes" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/examenes'; return false;">
                <i class="fas fa-file-alt"></i>
                <span>Exámenes</span>
            </button>
            <button class="navbar-item" data-menu="horario" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/horario'; return false;">
                <i class="fas fa-calendar"></i>
                <span>Horario</span>
            </button>
            <button class="navbar-item" data-menu="perfil" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/perfil'; return false;">
                <i class="fas fa-user"></i>
                <span>Perfil</span>
            </button>
        </div>

        <button class="navbar-notifications" modal="notificaciones" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/notificaciones'; return false;">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>
    </div>
</nav>

<!-- CONTENIDO PRINCIPAL -->
<main class="student-dashboard">
    <div class="dashboard-container">
        
        <!-- BARRA SUPERIOR CON BIENVENIDA Y RESUMEN -->
        <header class="dashboard-header">
            <div class="welcome-section">
                <h1>
                    <i class="fas fa-user-graduate welcome-icon"></i>
                    Bienvenido, <strong><?php echo htmlspecialchars($user['nombre_completo'] ?? $user['nombre'] ?? 'Estudiante'); ?></strong>
                </h1>
                <p class="welcome-date" id="fecha-actual"></p>
            </div>
        </header>

        <!-- SECCIÓN DE CURSOS -->
        <section class="courses-section" id="seccion-cursos">
            <div class="section-header">
                <h2><i class="fas fa-bookmark"></i> Mis Cursos</h2>
                <p class="section-subtitle">Cursos en los que estás inscrito</p>
            </div>

             <?php if (!empty($enrollments)): ?>
                <div class="courses-grid">
                    <?php foreach ($enrollments as $enrollment): ?>
                        <article class="course-card" data-course-id="<?php echo htmlspecialchars($enrollment['id'] ?? ''); ?>">
                            <div class="course-header">
                                <div class="course-icon" data-category="<?php echo htmlspecialchars($enrollment['categoria'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="course-body">
                                <h3 class="course-name"><?php echo htmlspecialchars($enrollment['titulo'] ?? ''); ?></h3>
                                <div class="course-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-book-open"></i>
                                        <?php echo htmlspecialchars($enrollment['categoria'] ?? ''); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <?php 
                                        $fecha = new DateTime($enrollment['fecha_inscripcion']);
                                        echo $fecha->format('d/m/Y');
                                        ?>
                                    </span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo rand(60, 95); ?>%;"></div>
                                </div>
                            </div>
                            <button class="course-btn" data-menu="cursos" onclick="window.location.href='<?= APP_URL ?>/?page=cursos/ver_curso'; return false;"> Ver curso
                            </button>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state-courses">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>Aún no estás inscrito</h3>
                    <p>No tienes cursos inscritos. Dirígete a la sección de servicios para inscribirte.</p>
                    <a href="<?php echo APP_URL; ?>/?page=home#servicios" class="btn btn-primary">
                        <i class="fas fa-book"></i> Ver servicios disponibles
                    </a>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<script src="<?= APP_URL ?>/assets/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        establecerFechaActual();
    });
</script>
