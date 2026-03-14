<?php $title = "Editar Servicio"; ?>

<section class="admin-form">

    <h2><i class="fas fa-edit"></i> Editar Servicio</h2>

    <form action="<?php echo APP_URL; ?>/?page=admin/update-service&id=<?php echo $service['id']; ?>" method="POST">

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo"
                   value="<?php echo htmlspecialchars($service['titulo']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4">
                <?php echo htmlspecialchars($service['descripcion']); ?>
            </textarea>
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="categoria">
                <?php
                $categorias = ['academico','deportivo','cultural','tecnologico'];
                foreach ($categorias as $cat):
                ?>
                    <option value="<?php echo $cat; ?>"
                        <?php echo $service['categoria'] === $cat ? 'selected' : ''; ?>>
                        <?php echo ucfirst($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Ícono</label>
            <input type="text" name="icono"
                   value="<?php echo htmlspecialchars($service['icono']); ?>">
        </div>

        <div class="form-group">
            <label>Orden Mostrar</label>
            <input type="number" name="orden_mostrar"
                   value="<?php echo $service['orden_mostrar']; ?>">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="disponible" value="1"
                    <?php echo $service['disponible'] ? 'checked' : ''; ?>>
                Servicio disponible
            </label>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="<?php echo APP_URL; ?>/?page=admin/dashboard" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>
</section>