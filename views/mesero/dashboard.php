<div class="section-title">
    <h2>🪑 Panel del Mesero</h2>
    <p>Bienvenido, <?= Helper::e(Auth::user()['nombre']) ?></p>
</div>

<!-- PEDIDOS LISTOS PARA SERVIR -->
<div class="card mb-4" style="border-top: 4px solid var(--color-success);">
    <div class="card-body">
        <h3 style="color: var(--color-success);">🔔 Listos para servir (<?= count($pedidos_listos) ?>)</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem; margin-bottom: 14px;">Estos pedidos están listos para ser llevados al cliente.</p>

        <?php if (empty($pedidos_listos)): ?>
            <p style="color: var(--color-text-light); padding: 20px; text-align: center;">No hay pedidos listos por entregar.</p>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach ($pedidos_listos as $p): ?>
                    <div style="border: 2px solid var(--color-success); border-radius: 6px; padding: 18px; background: #f0faf3;">
                        <div class="flex-between" style="margin-bottom: 10px;">
                            <strong style="font-size: 1.2rem;">Pedido #<?= $p['id'] ?></strong>
                            <span class="badge badge-success">Listo</span>
                        </div>
                        <p><strong>Cliente:</strong> <?= Helper::e($p['cliente_nombre']) ?></p>
                        <p>
                            <strong>Ubicación:</strong>
                            <?= $p['tipo'] === 'mesa' ? '🪑 Mesa #' . $p['mesa_numero'] : '🚴 Domicilio' ?>
                        </p>
                        <p><strong>Total:</strong> <?= Helper::precio($p['total']) ?></p>
                        <p style="font-size: 0.85rem; color: var(--color-text-light);">
                            Hace <?= Helper::fecha($p['fecha_pedido'], 'H:i') ?>
                        </p>
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm btn-outline">Ver detalle</a>
                            <a href="<?= Helper::url('pedido', 'actualizarEstado', ['id' => $p['id'], 'estado' => 'entregado']) ?>"
                               class="btn btn-sm btn-success"
                               onclick="return confirm('¿Marcar pedido #<?= $p['id'] ?> como entregado?')">✓ Marcar entregado</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- PEDIDOS EN PROCESO -->
<div class="card">
    <div class="card-body">
        <h3>⏳ En proceso (<?= count($pedidos_proceso) ?>)</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem; margin-bottom: 14px;">Pedidos que la barra/cocina está preparando.</p>

        <?php if (empty($pedidos_proceso)): ?>
            <p style="color: var(--color-text-light); padding: 20px; text-align: center;">No hay pedidos en preparación.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos_proceso as $p):
                            $info = Helper::estadoPedido($p['estado']);
                        ?>
                            <tr>
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td><?= Helper::e($p['cliente_nombre']) ?></td>
                                <td><?= $p['tipo'] === 'mesa' ? 'Mesa #' . $p['mesa_numero'] : 'Domicilio' ?></td>
                                <td><span class="estado-pedido" style="background-color: <?= $info['color'] ?>; font-size: 0.75rem; padding: 4px 10px;"><?= $info['texto'] ?></span></td>
                                <td><?= Helper::precio($p['total']) ?></td>
                                <td><a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm">Ver</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<p class="text-center mt-3" style="color: var(--color-text-light); font-size: 0.85rem;">
    🔄 Auto-actualización cada 30 segundos
</p>
<script>setTimeout(function(){ location.reload(); }, 30000);</script>
