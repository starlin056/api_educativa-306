<?php
// views/admin/dashboard.php
// Dashboard administrativo con accesos rápidos dinámicos
// @phpstan-ignore-file
?>

<section class="admin-dashboard">
    <div class="dashboard-header">
        <h1><i class="fas fa-shield-alt"></i> <?= htmlspecialchars($title) ?></h1>
        <p class="subtitle">Panel de Control - Acceso Rápido</p>
    </div>

    <!-- Tarjetas de Estadísticas Clickeables -->
    <div class="stats-grid">

        <!-- Administradores -->
        <a href="<?= APP_URL ?>/?page=admin/users&role=admin" class="stat-card primary" title="Gestionar Administradores">
            <div class="stat-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-content">
                <h3>Administradores</h3>
                <p class="stat-number"><?= $stats['admin'] ?? 0 ?></p>
                <small>Usuarios con acceso total</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <!-- Docentes -->
        <a href="<?= APP_URL ?>/?page=admin/users&role=docente" class="stat-card warning" title="Gestionar Docentes">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <h3>Docentes</h3>
                <p class="stat-number"><?= $stats['docente'] ?? 0 ?></p>
                <small>Profesores activos</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <!-- Estudiantes -->
        <a href="<?= APP_URL ?>/?page=admin/users&role=estudiante" class="stat-card info" title="Gestionar Estudiantes">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-content">
                <h3>Estudiantes</h3>
                <p class="stat-number"><?= $stats['estudiante'] ?? 0 ?></p>
                <small>Alumnos inscritos</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>



        <!-- Servicios -->
        <a href="<?= APP_URL ?>/?page=admin/services" class="stat-card success" title="Gestionar Servicios">
            <div class="stat-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="stat-content">
                <h3>Servicios Activos</h3>
                <p class="stat-number"><?= $stats['servicios'] ?? 0 ?></p>
                <small>Oferta educativa disponible</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <!-- Inscripciones Pendientes -->
        <a href="<?= APP_URL ?>/?page=admin/enrollments&status=pendiente" class="stat-card danger" title="Ver Inscripciones Pendientes">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Pendientes</h3>
                <p class="stat-number">
                    <?= $pendingInscriptions ?? 0 ?>
                </p>
                <small>Esperan aprobación</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

    </div>

    <!-- Acciones Rápidas -->
    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
        <div class="action-buttons">
            <a href="<?= APP_URL ?>/?page=admin/users&action=create" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </a>
            <a href="<?= APP_URL ?>/?page=admin/services&action=create" class="btn btn-secondary">
                <i class="fas fa-plus-circle"></i> Nuevo Servicio
            </a>


        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="recent-activity">
        <h3><i class="fas fa-history"></i> Actividad Reciente</h3>
        <div class="activity-list">
            <?php
            if (!empty($recentUsers)):
                foreach ($recentUsers as $user):
            ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="activity-info">
                            <strong><?= htmlspecialchars($user['nombre_completo']) ?></strong>
                            <small><?= htmlspecialchars($user['email']) ?></small>
                        </div>
                        <div class="activity-meta">
                            <span class="badge badge-<?= htmlspecialchars($user['rol']) ?>">
                                <?= htmlspecialchars(ucfirst($user['rol'])) ?>
                            </span>
                            <small><?= date('d/m H:i', strtotime($user['created_at'])) ?></small>
                        </div>
                    </div>
                <?php
                endforeach;
            else:
                ?>
                <p class="empty-state">No hay actividad reciente</p>
            <?php endif; ?>
        </div>
    </div>

</section>