<div class="flex-between mb-3">
    <h2>Moderación de Reseñas</h2>
    <a href="<?= Helper::url('admin', 'dashboard') ?>" class="btn btn-outline btn-sm">← Dashboard</a>
</div>

<?php if (empty($resenas)): ?>
    <p>No hay reseñas registradas.</p>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resenas as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= Helper::e($r['usuario_nombre'] . ' ' . $r['usuario_apellidos']) ?></td>
                        <td><span class="stars"><?= str_repeat('★', $r['calificacion']) ?></span></td>
                        <td style="max-width: 300px;"><?= Helper::e($r['comentario']) ?></td>
                        <td><?= Helper::fecha($r['fecha'], 'd/m/Y') ?></td>
                        <td>
                            <?php if ($r['aprobada']): ?>
                                <span class="badge badge-success">Visible</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Oculta</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <?php if ($r['aprobada']): ?>
                                    <a href="<?= Helper::url('resena', 'aprobar', ['id' => $r['id'], 'aprobada' => 0]) ?>"
                                       class="btn btn-sm btn-outline">Ocultar</a>
                                <?php else: ?>
                                    <a href="<?= Helper::url('resena', 'aprobar', ['id' => $r['id'], 'aprobada' => 1]) ?>"
                                       class="btn btn-sm btn-success">Aprobar</a>
                                <?php endif; ?>
                                <a href="<?= Helper::url('resena', 'eliminar', ['id' => $r['id']]) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Eliminar esta reseña?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
