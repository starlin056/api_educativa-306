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

<style>
    /* Estilos específicos para dashboard dinámico */
    .admin-dashboard {
        padding: 1rem 0;
    }

    .dashboard-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--color-secondary);
    }

    .dashboard-header .subtitle {
        color: #666;
        margin: 0.5rem 0 0 0;
    }

    /* Grid de estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        border-left: 4px solid var(--color-primary);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-left-width: 6px;
    }

    .stat-card.primary {
        border-left-color: #0b1838;
    }

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
    }

    .stat-card.secondary {
        border-left-color: #6c757d;
    }

    .stat-card.success {
        border-left-color: #28a745;
    }

    .stat-card.danger {
        border-left-color: #dc3545;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: rgba(11, 24, 56, 0.1);
        color: var(--color-primary);
    }

    .stat-card.warning .stat-icon {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .stat-card.info .stat-icon {
        background: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }

    .stat-card.success .stat-icon {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .stat-card.danger .stat-icon {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .stat-content {
        flex: 1;
    }

    .stat-content h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--color-primary);
        margin: 0;
    }

    .stat-content small {
        color: #666;
        font-size: 0.85rem;
    }

    .stat-arrow {
        color: #ccc;
        font-size: 1.2rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover .stat-arrow {
        opacity: 1;
    }

    /* Acciones rápidas */
    .quick-actions {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .quick-actions h3 {
        margin: 0 0 1rem 0;
        color: var(--color-primary);
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .action-buttons .btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }

    /* Actividad reciente */
    .recent-activity {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .recent-activity h3 {
        margin: 0 0 1rem 0;
        color: var(--color-primary);
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .activity-item:hover {
        background: #e9ecef;
    }

    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--color-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .activity-info {
        flex: 1;
        min-width: 0;
    }

    .activity-info strong {
        display: block;
        font-size: 0.95rem;
    }

    .activity-info small {
        color: #666;
        font-size: 0.8rem;
    }

    .activity-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-admin {
        background: #0b1838;
        color: white;
    }

    .badge-docente {
        background: #17a2b8;
        color: white;
    }

    .badge-estudiante {
        background: #28a745;
        color: white;
    }

    .badge-padre {
        background: #6c757d;
        color: white;
    }

    .empty-state {
        text-align: center;
        color: #666;
        padding: 2rem;
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
            text-align: center;
        }

        .activity-item {
            flex-wrap: wrap;
        }

        .activity-meta {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
        }
    }
</style>