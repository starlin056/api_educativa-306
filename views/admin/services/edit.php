<?php
// views/admin/services/edit.php
// Formulario para editar servicio educativo
// @phpstan-ignore-file

// Verificar que se pasó un servicio
if (!isset($service) || !$service) {
    echo '<div class="alert alert-error">❌ Servicio no encontrado</div>';
    echo '<a href="' . APP_URL . '/?page=admin/services" class="btn btn-secondary">← Volver al listado</a>';
    return;
}
?>


<section class="form-container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Editar Servicio</h1>
        <a href="<?= APP_URL ?>/?page=admin/services" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
    </div>

    <form action="<?= APP_URL ?>/?page=admin/services&action=update&id=<?= $service['id'] ?>" method="POST" class="form-validate" novalidate>
        <div class="form-grid">
            <div class="form-group">
                <label for="titulo">Título del Servicio *</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($service['titulo']) ?>" required minlength="3" maxlength="100">
            </div>
            <div class="form-group">
                <label for="categoria">Categoría *</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccionar</option>
                    <option value="academico" <?= $service['categoria'] === 'academico' ? 'selected' : '' ?>>Académico</option>
                    <option value="deportivo" <?= $service['categoria'] === 'deportivo' ? 'selected' : '' ?>>Deportivo</option>
                    <option value="cultural" <?= $service['categoria'] === 'cultural' ? 'selected' : '' ?>>Cultural</option>
                    <option value="tecnologico" <?= $service['categoria'] === 'tecnologico' ? 'selected' : '' ?>>Tecnológico</option>
                </select>
            </div>
            <div class="form-group">
                <label for="icono">Icono (FontAwesome)</label>
                <input type="text" id="icono" name="icono" value="<?= htmlspecialchars($service['icono'] ?? 'fa-graduation-cap') ?>" placeholder="fa-graduation-cap">
                <small class="form-help"><a href="https://fontawesome.com/icons" target="_blank">Buscar iconos</a></small>
            </div>
            <div class="form-group">
                <label for="orden_mostrar">Orden de visualización</label>
                <input type="number" id="orden_mostrar" name="orden_mostrar" value="<?= htmlspecialchars($service['orden_mostrar'] ?? '0') ?>" min="0" max="999">
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" rows="4" required minlength="10" maxlength="500"><?= htmlspecialchars($service['descripcion']) ?></textarea>
            <small class="form-help">Máximo 500 caracteres</small>
        </div>
        <div class="form-group checkbox-group">
            <label><input type="checkbox" name="disponible" value="1" <?= $service['disponible'] ? 'checked' : '' ?>> <span>Servicio disponible para inscripción</span></label>
        </div>
        <div class="form-info"><small><i class="fas fa-info-circle"></i> Creado: <?= date('d/m/Y H:i', strtotime($service['created_at'])) ?><?php if ($service['updated_at'] !== $service['created_at']): ?> | Actualizado: <?= date('d/m/Y H:i', strtotime($service['updated_at'])) ?><?php endif; ?></small></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Servicio</button>
            <a href="<?= APP_URL ?>/?page=admin/services" class="btn btn-secondary">Cancelar</a>
            <button type="button" class="btn btn-danger" onclick="if(confirm('¿Eliminar este servicio?')) window.location.href='<?= APP_URL ?>/?page=admin/services&action=delete&id=<?= $service['id'] ?>'"><i class="fas fa-trash"></i> Eliminar</button>
        </div>
    </form>
</section>