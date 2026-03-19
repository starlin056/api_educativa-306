<?php
// views/admin/users/index.php
// Listado de usuarios con filtros y acciones
// @phpstan-ignore-file
?>
<section class="user-management">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> <?= htmlspecialchars($title) ?></h1>
        <a href="<?= APP_URL ?>/?page=admin/users/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-bar">
        <form method="GET" action="<?= APP_URL ?>/index.php" class="filters-form">
            <input type="hidden" name="page" value="admin/users">

            <div class="filter-group">
                <label for="role">Filtrar por rol:</label>
                <select name="role" id="role" onchange="this.form.submit()">
                    <option value="">Todos los roles</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['nombre']) ?>"
                            <?= ($role ?? '') === $rol['nombre'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="search">Buscar:</label>
                <input type="text" name="search" id="search"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="Nombre o email...">
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <?php if ($role || $search): ?>
                <a href="<?= APP_URL ?>/?page=admin/users" class="btn-clear">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="table-container">
        <?php if (!empty($users)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="<?= !$user['activo'] ? 'row-inactive' : '' ?>">
                            <td><?= $user['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($user['nombre_completo']) ?></strong>
                                <?php if (!$user['activo']): ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($user['rol_nombre'] ?? 'estudiante') ?>">
                                    <?= htmlspecialchars(ucfirst($user['rol_nombre'] ?? 'estudiante')) ?>
                                </span>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                        class="toggle-status"
                                        data-id="<?= $user['id'] ?>"
                                        <?= $user['activo'] ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td class="actions">
                                <a href="<?= APP_URL ?>/?page=admin/users/edit&id=<?= $user['id'] ?>"
                                    class="btn-action btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?= APP_URL ?>/?page=admin/users&action=delete&id=<?= $user['id'] ?>"
                                    class="inline-form"
                                    onsubmit="return confirm('¿Estás seguro de desactivar este usuario?');">
                                    <button type="submit" class="btn-action btn-delete" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <p>No se encontraron usuarios con los filtros aplicados</p>
                <a href="<?= APP_URL ?>/?page=admin/users" class="btn btn-secondary">Ver todos</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <?php if (($pagination['total'] ?? 1) > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                <a href="<?= APP_URL ?>/?page=admin/users&role=<?= urlencode($role ?? '') ?>&search=<?= urlencode($search ?? '') ?>&p=<?= $i ?>"
                    class="page-link <?= $i === $pagination['current'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <span class="page-info">
                Página <?= $pagination['current'] ?> de <?= $pagination['total'] ?>
                (<?= $pagination['total_items'] ?> total)
            </span>
        </div>
    <?php endif; ?>

</section>



<!-- JavaScript para toggle de estado -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle de estado de usuario (AJAX)
        document.querySelectorAll('.toggle-status').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const userId = this.dataset.id;
                const checked = this.checked;

                fetch(`<?= APP_URL ?>/?page=admin/users/toggle-status&id=${userId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Revertir el toggle si falló
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