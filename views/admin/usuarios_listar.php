<div class="flex-between mb-3">
    <h2>Gestión de Usuarios</h2>
    <div>
        <a href="<?= Helper::url('admin', 'dashboard') ?>" class="btn btn-outline btn-sm">← Dashboard</a>
        <a href="<?= Helper::url('admin', 'crearUsuario') ?>" class="btn">+ Nuevo usuario</a>
    </div>
</div>

<?php if (empty($usuarios)): ?>
    <p>No hay usuarios registrados.</p>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= Helper::e($u['nombre'] . ' ' . $u['apellidos']) ?></td>
                        <td><?= Helper::e($u['email']) ?></td>
                        <td><?= Helper::e($u['telefono']) ?: '—' ?></td>
                        <td>
                            <span class="badge badge-primary"><?= ucfirst(Helper::e($u['rol_nombre'])) ?></span>
                        </td>
                        <td>
                            <?php if ($u['activo']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= Helper::fecha($u['fecha_registro'], 'd/m/Y') ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= Helper::url('admin', 'editarUsuario', ['id' => $u['id']]) ?>" class="btn btn-sm">Editar</a>
                                <?php if ($u['id'] != Auth::user()['id']): ?>
                                    <a href="<?= Helper::url('admin', 'eliminarUsuario', ['id' => $u['id']]) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Desactivar a <?= Helper::e($u['nombre']) ?>?')">Desactivar</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
