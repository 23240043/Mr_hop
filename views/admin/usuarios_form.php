<?php $editando = !empty($usuario); ?>

<div class="form-container" style="max-width: 700px;">
    <h2><?= $editando ? 'Editar usuario' : 'Nuevo usuario' ?></h2>

    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control"
                       value="<?= Helper::e($usuario['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control"
                       value="<?= Helper::e($usuario['apellidos'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Correo electrónico *</label>
            <input type="email" name="email" class="form-control"
                   value="<?= Helper::e($usuario['email'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" class="form-control"
                       value="<?= Helper::e($usuario['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Rol *</label>
                <select name="rol_id" class="form-control" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>"
                            <?= ($usuario['rol_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                            <?= ucfirst(Helper::e($r['nombre'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="<?= Helper::e($usuario['direccion'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Contraseña <?= $editando ? '<small>(dejar vacío para no cambiar)</small>' : '*' ?></label>
            <input type="password" name="password" class="form-control"
                   <?= $editando ? '' : 'required minlength="5"' ?>>
        </div>

        <?php if ($editando): ?>
            <div class="form-check">
                <input type="checkbox" id="activo" name="activo" <?= ($usuario['activo'] ?? 1) ? 'checked' : '' ?>>
                <label for="activo">Cuenta activa</label>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-lg" style="flex: 1;">
                <?= $editando ? 'Guardar cambios' : 'Crear usuario' ?>
            </button>
            <a href="<?= Helper::url('admin', 'usuarios') ?>" class="btn btn-lg btn-outline">Cancelar</a>
        </div>
    </form>
</div>
