<?php
// views/admin/services/index.php
// Listado y gestión de servicios educativos
// @phpstan-ignore-file
?>


<section class="services-management">
    <div class="page-header">
        <h1><i class="fas fa-cogs"></i> <?= htmlspecialchars($title ?? 'Gestión de Servicios') ?></h1>
        <a href="<?= APP_URL ?>/?page=admin/services&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </a>
    </div>

    <!-- Filtros -->
    <div class="filters-bar">
        <form method="GET" action="<?= APP_URL ?>/index.php" class="filters-form">
            <input type="hidden" name="page" value="admin/services">
            <div class="filter-group">
                <label for="category">Categoría:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="academico" <?= ($category ?? '') === 'academico' ? 'selected' : '' ?>>Académico</option>
                    <option value="deportivo" <?= ($category ?? '') === 'deportivo' ? 'selected' : '' ?>>Deportivo</option>
                    <option value="cultural" <?= ($category ?? '') === 'cultural' ? 'selected' : '' ?>>Cultural</option>
                    <option value="tecnologico" <?= ($category ?? '') === 'tecnologico' ? 'selected' : '' ?>>Tecnológico</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search">Buscar:</label>
                <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Título o descripción...">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
            </div>
            <?php if ($category || $search): ?>
                <a href="<?= APP_URL ?>/?page=admin/services" class="btn-clear"><i class="fas fa-times"></i> Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <?php if (!empty($services)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr class="<?= !$service['disponible'] ? 'row-inactive' : '' ?>">
                            <td>
                                <strong><?= htmlspecialchars($service['titulo']) ?></strong>
                                <?php if (!$service['disponible']): ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars(ucfirst($service['categoria'])) ?>
                                </span>
                            </td>
                            <td title="<?= htmlspecialchars($service['descripcion'] ?? '') ?>">
                                <?= htmlspecialchars(substr($service['descripcion'] ?? '', 0, 60)) ?>
                                <?= strlen($service['descripcion'] ?? '') > 60 ? '...' : '' ?>
                            </td>
                            <td><?= $service['orden_mostrar'] ?? '-' ?></td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-disponible"
                                        data-id="<?= $service['id'] ?>"
                                        <?= $service['disponible'] ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td class="actions">
                                <div class="btn-group">
                                    <a href="<?= APP_URL ?>/?page=admin/services&action=edit&id=<?= $service['id'] ?>"
                                        class="btn-action btn-edit" aria-label="Editar servicio">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST"
                                        action="<?= APP_URL ?>/?page=admin/services&action=delete&id=<?= $service['id'] ?>"
                                        class="inline-form"
                                        onsubmit="return confirm('¿Eliminar este servicio?');">
                                        <button type="submit" class="btn-action btn-delete" aria-label="Eliminar servicio">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-cogs"></i>
                <p>No hay servicios registrados</p>
                <a href="<?= APP_URL ?>/?page=admin/services&action=create" class="btn btn-primary">
                    Crear primer servicio
                </a>
            </div>
        <?php endif; ?>
    </div>


    <!-- Paginación -->
    <?php if (($pagination['total'] ?? 1) > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                <a href="<?= APP_URL ?>/?page=admin/services&category=<?= urlencode($category ?? '') ?>&page=<?= $i ?>" class="page-link <?= $i === $pagination['current'] ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <span class="page-info">Página <?= $pagination['current'] ?> de <?= $pagination['total'] ?></span>
        </div>
    <?php endif; ?>
</section>

<!-- Estilos -->

<!-- JavaScript para toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-disponible').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const serviceId = this.dataset.id;
                const checked = this.checked;
                fetch(`<?= APP_URL ?>/index.php?page=admin/services&action=toggle-disponible&id=${serviceId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            this.checked = !checked;
                            alert(data.message || 'Error al actualizar estado');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !checked;
                        alert('Error de conexión');
                    });
            });
        });
    });
</script>