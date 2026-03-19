<section class="dashboard container">
    <h1>Panel del Docente</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
                                            unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="dashboard-grid">
        <!-- Crear Aula -->
        <div class="card">
            <h3>Crear Nueva Aula</h3>
            <form method="POST" action="<?= APP_URL ?>/index.php?page=docente/aulas&action=store">
                <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">

                <div class="form-group">
                    <input type="text" name="nombre" placeholder="Nombre del aula (ej: Matemáticas 5to)" required class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Aula
                </button>
            </form>
        </div>

        <!-- Listado de Aulas -->
        <div class="card full-width">
            <h3>Mis Aulas</h3>
            <?php if (!empty($aulas)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Estudiantes</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aulas as $aula): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($aula['nombre']) ?></strong></td>
                                <td><span class="badge badge-info"><?= $aula['total_estudiantes'] ?? 0 ?></span></td>
                                <td><?= date('d/m/Y', strtotime($aula['created_at'])) ?></td>
                                <td>
                                    <a href="<?= APP_URL ?>/index.php?page=docente/aulas&action=view&id=<?= $aula['id'] ?>" class="btn btn-sm btn-primary">
                                        Administrar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No tienes aulas creadas aún. Comienza creando una.</p>
            <?php endif; ?>
        </div>
    </div>
</section>