<?php
// views/admin/services/create.php
// Formulario mejorado para crear nuevo servicio educativo
// @phpstan-ignore-file
?>

<section class="form-container full-width">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Crear Nuevo Servicio</h1>
        <a href="<?= APP_URL ?>/?page=admin/services" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>

    <form action="<?= APP_URL ?>/?page=admin/services&action=store" method="POST" class="form-validate" novalidate>


        <div class="form-grid">
            <!-- Título -->
            <div class="form-group">
                <label for="titulo">Título del Servicio *</label>
                <input type="text" id="titulo" name="titulo"
                    value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>"
                    placeholder="Ej: Educación Primaria"
                    required minlength="3" maxlength="100">
                <small class="form-help">Nombre descriptivo del servicio</small>
            </div>

            <!-- Categoría -->
            <div class="form-group">
                <label for="categoria">Categoría *</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccionar categoría</option>
                    <option value="academico" <?= ($_POST['categoria'] ?? '') === 'academico' ? 'selected' : '' ?>>Académico</option>
                    <option value="deportivo" <?= ($_POST['categoria'] ?? '') === 'deportivo' ? 'selected' : '' ?>>Deportivo</option>
                    <option value="cultural" <?= ($_POST['categoria'] ?? '') === 'cultural' ? 'selected' : '' ?>>Cultural</option>
                    <option value="tecnologico" <?= ($_POST['categoria'] ?? '') === 'tecnologico' ? 'selected' : '' ?>>Tecnológico</option>
                </select>
                <small class="form-help">Clasificación del servicio</small>
            </div>

            <!-- Icono (FontAwesome) -->
            <div class="form-group">
                <label for="icono">Icono (FontAwesome)</label>
                <input type="text" id="icono" name="icono"
                    value="<?= htmlspecialchars($_POST['icono'] ?? 'fa-graduation-cap') ?>"
                    placeholder="fa-graduation-cap"
                    pattern="fa-[a-z\-]+">
                <small class="form-help">
                    <a href="https://fontawesome.com/icons" target="_blank">Buscar iconos</a>
                </small>
            </div>

            <!-- Imagen -->
            <div class="form-group">
                <label for="imagen">Imagen representativa</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small class="form-help">Opcional: sube una imagen ilustrativa del servicio</small>
            </div>

            <!-- Orden de visualización -->
            <div class="form-group">
                <label for="orden_mostrar">Orden de visualización</label>
                <input type="number" id="orden_mostrar" name="orden_mostrar"
                    value="<?= htmlspecialchars($_POST['orden_mostrar'] ?? '0') ?>"
                    min="0" max="999">
                <small class="form-help">Menor número = aparece primero</small>
            </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" rows="5"
                required minlength="10" maxlength="500"
                placeholder="Describe el servicio, objetivos, beneficiarios, etc."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            <small class="form-help">Máximo 500 caracteres</small>
        </div>

        <!-- Estado -->
        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" name="disponible" value="1"
                    <?= !isset($_POST['disponible']) || $_POST['disponible'] ? 'checked' : '' ?>>
                <span>Servicio disponible para inscripción</span>
            </label>
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Servicio
            </button>
            <a href="<?= APP_URL ?>/?page=admin/services" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>
</section>