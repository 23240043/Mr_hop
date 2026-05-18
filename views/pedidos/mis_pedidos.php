<div class="flex-between mb-3">
    <h2>Mis Pedidos</h2>
    <a href="<?= Helper::url('pedido', 'nuevo') ?>" class="btn">+ Nuevo pedido</a>
</div>

<?php if (empty($pedidos)): ?>
    <div class="card">
        <div class="card-body text-center" style="padding: 50px;">
            <h3>Aún no has hecho ningún pedido</h3>
            <p style="color: var(--color-text-light); margin: 14px 0;">¡Anímate a probar nuestra carta!</p>
            <a href="<?= Helper::url('pedido', 'nuevo') ?>" class="btn btn-lg">Hacer mi primer pedido</a>
        </div>
    </div>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p):
                    $info = Helper::estadoPedido($p['estado']);
                ?>
                    <tr>
                        <td><strong>#<?= $p['id'] ?></strong></td>
                        <td><?= Helper::fecha($p['fecha_pedido']) ?></td>
                        <td>
                            <?= $p['tipo'] === 'mesa' ? '🪑 Mesa #' . $p['mesa_numero'] : '🚴 Domicilio' ?>
                        </td>
                        <td><?= Helper::precio($p['total']) ?></td>
                        <td>
                            <span class="estado-pedido" style="background-color: <?= $info['color'] ?>; font-size: 0.78rem; padding: 4px 10px;">
                                <?= $info['texto'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
