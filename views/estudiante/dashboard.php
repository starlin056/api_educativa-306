<?php // views/estudiante/dashboard.php ?>
<section class="dashboard">
    <h1><i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($title); ?></h1>
    
    <div class="welcome-card">
        <p>Bienvenido, <strong><?php echo htmlspecialchars($user['nombre_completo'] ?? $user['nombre'] ?? 'Estudiante'); ?></strong></p>
    </div>
    
    <h2>Mis Inscripciones</h2>
    <?php if (!empty($enrollments)): ?>
        <div class="card-grid">
            <?php foreach ($enrollments as $enrollment): ?>
                <article class="card">
                    <h3><?php echo htmlspecialchars($enrollment['servicio']); ?></h3>
                    <span class="badge badge-<?php echo $enrollment['estado']; ?>">
                        <?php echo ucfirst($enrollment['estado']); ?>
                    </span>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">Aún no estás inscrito en ningún servicio.</p>
        <a href="<?php echo APP_URL; ?>/?page=home#servicios" class="btn btn-primary">
            Ver servicios disponibles
        </a>
    <?php endif; ?>
</section>