<?php
// views/admin/users/form.php
// Formulario para crear y editar usuarios
// @phpstan-ignore-file

// Determinar si es un formulario de edición o creación
$isEdit = isset($user) && $user && isset($user['id']);
$formAction = $isEdit
    ? APP_URL . '/?page=admin/users/update&id=' . $user['id']
    : APP_URL . '/?page=admin/users/store';
$submitButtonText = $isEdit ? 'Actualizar Usuario' : 'Crear Usuario';

?>

<section class="content-header">
    <h1>
        <i class="fas fa-user-plus"></i> <?= htmlspecialchars($title) ?>
    </h1>
    <a href="<?= APP_URL ?>/?page=admin/users" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</section>

<section class="content-body">
    <div class="card">
        <div class="card-header">
            <h3><?= $isEdit ? 'Editando a ' . htmlspecialchars($user['nombre_completo']) : 'Nuevo Usuario' ?></h3>
        </div>
        <div class="card-body">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= $formAction ?>" method="POST" class="user-form">

                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control"
                        value="<?= htmlspecialchars($user['nombre_completo'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control"
                        value="<?= htmlspecialchars($user['telefono'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="rol_id">Rol del Usuario</label>
                    <select id="rol_id" name="rol_id" class="form-control" required>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>"
                                <?= isset($user['rol_id']) && $user['rol_id'] == $rol['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" <?= !$isEdit ? 'required' : '' ?>>
                    <?php if ($isEdit): ?>
                        <small class="form-text text-muted">Dejar en blanco para no cambiar la contraseña.</small>
                    <?php endif; ?>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" id="activo" name="activo" class="form-check-input" value="1"
                        <?= (isset($user['activo']) && $user['activo']) || !$isEdit ? 'checked' : '' ?>>
                    <label for="activo" class="form-check-label">Usuario Activo</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $submitButtonText ?>
                    </button>
                    <a href="<?= APP_URL ?>/?page=admin/users" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>