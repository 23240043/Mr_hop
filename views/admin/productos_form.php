<?php $editando = !empty($producto); ?>

<div class="form-container" style="max-width: 700px;">
    <h2><?= $editando ? 'Editar producto' : 'Nuevo producto' ?></h2>

    <form method="POST">
        <div class="form-group">
            <label>Nombre del producto *</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= Helper::e($producto['nombre'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"><?= Helper::e($producto['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio *</label>
                <input type="number" name="precio" class="form-control" step="0.01" min="0"
                       value="<?= Helper::e($producto['precio'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Categoría *</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">— Selecciona —</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= ($producto['categoria_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= Helper::e($c['nombre']) ?> (<?= $c['tipo'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" id="disponible" name="disponible"
                   <?= ($producto['disponible'] ?? 1) ? 'checked' : '' ?>>
            <label for="disponible">Disponible para venta</label>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-lg" style="flex: 1;">
                <?= $editando ? 'Guardar cambios' : 'Crear producto' ?>
            </button>
            <a href="<?= Helper::url('carta', 'gestionar') ?>" class="btn btn-lg btn-outline">Cancelar</a>
        </div>
    </form>
</div>
