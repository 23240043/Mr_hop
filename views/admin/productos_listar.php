<div class="flex-between mb-3">
    <h2>Gestión de la Carta</h2>
    <div>
        <a href="<?= Helper::url('admin', 'dashboard') ?>" class="btn btn-outline btn-sm">← Dashboard</a>
        <a href="<?= Helper::url('carta', 'crear') ?>" class="btn">+ Nuevo producto</a>
    </div>
</div>

<?php if (empty($productos)): ?>
    <p>No hay productos registrados.</p>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td>
                            <strong><?= Helper::e($p['nombre']) ?></strong><br>
                            <small style="color: var(--color-text-light);"><?= Helper::e(substr($p['descripcion'], 0, 60)) ?>...</small>
                        </td>
                        <td><?= Helper::e($p['categoria_nombre']) ?></td>
                        <td><span class="badge badge-info"><?= ucfirst($p['categoria_tipo']) ?></span></td>
                        <td><strong><?= Helper::precio($p['precio']) ?></strong></td>
                        <td>
                            <?php if ($p['disponible']): ?>
                                <span class="badge badge-success">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-danger">No disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= Helper::url('carta', 'editar', ['id' => $p['id']]) ?>" class="btn btn-sm">Editar</a>
                                <a href="<?= Helper::url('carta', 'eliminar', ['id' => $p['id']]) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Eliminar <?= Helper::e($p['nombre']) ?>?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
