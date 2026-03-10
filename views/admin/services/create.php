<?php $title = "Crear Servicio"; ?>

<section class="admin-form">

    <h2><i class="fas fa-plus-circle"></i> Crear Nuevo Servicio</h2>

    <form action="<?php echo APP_URL; ?>/?page=admin/store-service" method="POST">

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="categoria">
                <option value="academico">Académico</option>
                <option value="deportivo">Deportivo</option>
                <option value="cultural">Cultural</option>
                <option value="tecnologico">Tecnológico</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ícono (FontAwesome)</label>
            <input type="text" name="icono" placeholder="fa-graduation-cap">
        </div>

        <div class="form-group">
            <label>Orden Mostrar</label>
            <input type="number" name="orden_mostrar" value="0">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="disponible" value="1" checked>
                Servicio disponible
            </label>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="<?php echo APP_URL; ?>/?page=admin/dashboard" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>
</section>