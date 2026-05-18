<div class="flex-between mb-3">
    <h2>Todos los Pedidos</h2>
    <?php if (Auth::hasRole('administrador')): ?>
        <a href="<?= Helper::url('admin', 'dashboard') ?>" class="btn btn-outline btn-sm">← Dashboard</a>
    <?php endif; ?>
</div>

<?php if (empty($pedidos)): ?>
    <p class="text-center" style="padding: 40px; color: var(--color-text-light);">No hay pedidos registrados.</p>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p):
                    $info = Helper::estadoPedido($p['estado']);
                ?>
                    <tr>
                        <td><strong>#<?= $p['id'] ?></strong></td>
                        <td><?= Helper::e($p['cliente_nombre'] . ' ' . $p['cliente_apellidos']) ?></td>
                        <td>
                            <?= $p['tipo'] === 'mesa' ? '🪑 Mesa #' . $p['mesa_numero'] : '🚴 Domicilio' ?>
                        </td>
                        <td><?= Helper::fecha($p['fecha_pedido']) ?></td>
                        <td><?= Helper::precio($p['total']) ?></td>
                        <td>
                            <span class="estado-pedido" style="background-color: <?= $info['color'] ?>; font-size: 0.75rem; padding: 4px 10px;">
                                <?= $info['texto'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm">Ver</a>
                                <?php if (Auth::hasRole('administrador')): ?>
                                    <a href="<?= Helper::url('pedido', 'eliminar', ['id' => $p['id']]) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Eliminar el pedido #<?= $p['id'] ?>?')">Eliminar</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
