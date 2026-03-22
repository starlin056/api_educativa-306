<section class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Gestión de Aula: <span class="text-primary"><?= htmlspecialchars($aula['nombre']) ?></span></h1>
            <p class="text-muted">ID: <?= $aula['id'] ?> | Creada: <?= date('d/m/Y', strtotime($aula['created_at'])) ?></p>
        </div>
        <a href="<?= APP_URL ?>/index.php?page=docente/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
                                            unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Estadísticas del Aula -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <h3><?= $estadisticas['total_estudiantes'] ?? 0 ?></h3>
            <p>Estudiantes Inscritos</p>
        </div>
        <div class="stat-card">
            <h3><?= $estadisticas['total_servicios'] ?? 0 ?></h3>
            <p>Servicios Asignados</p>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Estudiantes -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Estudiantes</h3>
                    <span class="badge badge-light"><?= count($estudiantes) ?></span>
                </div>
                <div class="card-body">
                    <!-- Formulario Agregar Estudiante -->
                    <?php if (!empty($estudiantes_disponibles)): ?>
                        <form method="POST" action="<?= APP_URL ?>/index.php?page=docente/aulas&action=addStudent" class="mb-3">
                            <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">
                            <input type="hidden" name="aula_id" value="<?= $aula['id'] ?>">

                            <div class="input-group">
                                <select name="estudiante_id" class="form-control" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <?php foreach ($estudiantes_disponibles as $e): ?>
                                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre_completo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="text-muted small">No hay más estudiantes disponibles para agregar.</p>
                    <?php endif; ?>

                    <!-- Lista de Estudiantes -->
                    <ul class="list-group">
                        <?php if (empty($estudiantes)): ?>
                            <li class="list-group-item text-muted text-center">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <br>No hay estudiantes en esta aula.
                            </li>
                        <?php else: ?>
                            <?php foreach ($estudiantes as $e): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($e['nombre_completo']) ?></strong>
                                        <br><small class="text-muted"><?= htmlspecialchars($e['email']) ?></small>
                                    </div>
                                    <form method="POST" action="<?= APP_URL ?>/index.php?page=docente/aulas&action=removeStudent" class="d-inline">
                                        <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">
                                        <input type="hidden" name="aula_id" value="<?= $aula['id'] ?>">
                                        <input type="hidden" name="estudiante_id" value="<?= $e['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar estudiante?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Servicios -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Servicios Disponibles</h3>
                    <span class="badge badge-light"><?= count($servicios) ?></span>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        <i class="fas fa-info-circle"></i>
                        Inscribir el aula asignará este servicio a <strong>todos</strong> los estudiantes del aula.
                    </p>

                    <div class="services-list">
                        <?php foreach ($servicios as $s): ?>
                            <?php
                            // Verificar si ya está inscrito
                            $inscrito = false;
                            foreach ($servicios_inscritos as $si) {
                                if ($si['id'] == $s['id']) {
                                    $inscrito = true;
                                    break;
                                }
                            }
                            ?>
                            <div class="service-item border rounded p-3 mb-3 <?= $inscrito ? 'bg-light' : '' ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas <?= $s['icono'] ?> fa-lg text-primary mr-2"></i>
                                            <strong><?= htmlspecialchars($s['titulo']) ?></strong>
                                            <?php if ($inscrito): ?>
                                                <span class="badge badge-success ml-2">Inscrito</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="small text-muted mb-2"><?= htmlspecialchars($s['descripcion']) ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-users"></i> <?= $s['total_inscripciones'] ?> inscripciones totales
                                        </small>
                                    </div>
                                    <?php if (!$inscrito): ?>
                                        <form method="POST" action="<?= APP_URL ?>/index.php?page=docente/aulas&action=inscribirServicio">
                                            <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">
                                            <input type="hidden" name="aula_id" value="<?= $aula['id'] ?>">
                                            <input type="hidden" name="servicio_id" value="<?= $s['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" <?= empty($estudiantes) ? 'disabled title="Agrega estudiantes primero"' : '' ?>>
                                                <i class="fas fa-check"></i> Inscribir
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-check-circle"></i> Activo
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>