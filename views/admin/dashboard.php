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

        <!-- Padres 
        <a href="<?= APP_URL ?>/?page=admin/users&role=padre" class="stat-card secondary" title="Gestionar Padres">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Padres/Tutores</h3>
                <p class="stat-number"><?= $stats['padre'] ?? 0 ?></p>
                <small>Tutores registrados</small>
            </div>
            <div class="stat-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a> -->

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
                    <?php
                    // Contar inscripciones pendientes (consulta directa)
                    $db = (new \User())->getConnection();
                    $stmt = $db->prepare("SELECT COUNT(*) FROM inscripciones WHERE estado = 'pendiente'");
                    $stmt->execute();
                    echo $stmt->fetchColumn();
                    ?>
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
            <a href="<?= APP_URL ?>/?page=admin/reports" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Ver Reportes
            </a>
            <a href="<?= APP_URL ?>/?page=admin/settings" class="btn btn-warning">
                <i class="fas fa-cog"></i> Configuración
            </a>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="recent-activity">
        <h3><i class="fas fa-history"></i> Actividad Reciente</h3>
        <div class="activity-list">
            <?php
            // Consultar últimos 5 usuarios registrados
            $db = (new \User())->getConnection();
            $stmt = $db->prepare("
                SELECT u.nombre_completo, u.email, r.nombre as rol, u.created_at 
                FROM usuarios u 
                JOIN roles r ON u.rol_id = r.id 
                ORDER BY u.created_at DESC 
                LIMIT 5
            ");
            $stmt->execute();
            $recentUsers = $stmt->fetchAll();

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