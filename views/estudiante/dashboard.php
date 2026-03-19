<section class="dashboard container">
    <h1>Panel del Estudiante</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
                                            unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <h3><?= count($aulas) ?></h3>
            <p>Aulas Asignadas</p>
        </div>
        <div class="stat-card">
            <h3><?= $aprobadas ?></h3>
            <p>Servicios Aprobados</p>
        </div>
        <div class="stat-card">
            <h3><?= $pendientes ?></h3>
            <p>Pendientes de Aprobación</p>
        </div>
    </div>

    <div class="row">
        <!-- Servicios Disponibles -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Servicios Disponibles</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($servicios)): ?>
                        <div class="services-list">
                            <?php foreach ($servicios as $s): ?>
                                <?php $yaInscrito = false;
                                foreach ($inscripciones as $i) {
                                    if ($i['id'] == $s['id']) $yaInscrito = true;
                                } ?>
                                <div class="service-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas <?= $s['icono'] ?> fa-lg text-primary mr-2"></i>
                                                <strong><?= htmlspecialchars($s['titulo']) ?></strong>
                                            </div>
                                            <p class="small text-muted mb-2"><?= htmlspecialchars($s['descripcion']) ?></p>
                                        </div>
                                        <?php if (!$yaInscrito): ?>
                                            <form method="POST" action="<?= APP_URL ?>/index.php?page=estudiante/inscribir">
                                                <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">
                                                <input type="hidden" name="servicio_id" value="<?= $s['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus"></i> Inscribirme
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge badge-success">Inscrito</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay servicios disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mis Inscripciones -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">Mis Inscripciones</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($inscripciones)): ?>
                        <ul class="list-group">
                            <?php foreach ($inscripciones as $ins): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($ins['titulo']) ?></strong>
                                        <br><small class="text-muted"><?= date('d/m/Y', strtotime($ins['fecha_inscripcion'])) ?></small>
                                    </div>
                                    <?php
                                    $badgeClass = [
                                        'pendiente' => 'badge-warning',
                                        'aprobada' => 'badge-success',
                                        'rechazada' => 'badge-danger',
                                        'cancelada' => 'badge-secondary'
                                    ][$ins['estado']] ?? 'badge-secondary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($ins['estado']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-center">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <br>No tienes inscripciones aún.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Aulas Asignadas -->
    <?php if (!empty($aulas)): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Mis Aulas</h3>
            </div>
            <div class="card-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Docente</th>
                            <th>Fecha Asignación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aulas as $aula): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($aula['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($aula['docente_nombre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($aula['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>