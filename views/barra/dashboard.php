<div class="section-title">
    <h2>🍳 Panel de Barra / Cocina</h2>
    <p>Bienvenido, <?= Helper::e(Auth::user()['nombre']) ?></p>
</div>

<!-- PEDIDOS PENDIENTES -->
<div class="card mb-4" style="border-top: 4px solid #f59e0b;">
    <div class="card-body">
        <h3 style="color: #b45309;">🆕 Pendientes por iniciar (<?= count($pedidos_pendientes) ?>)</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem; margin-bottom: 14px;">Pedidos nuevos que aún no se han comenzado a preparar.</p>

        <?php if (empty($pedidos_pendientes)): ?>
            <p style="color: var(--color-text-light); padding: 20px; text-align: center;">¡No hay pedidos pendientes! ✨</p>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach ($pedidos_pendientes as $p):
                    require_once ROOT_PATH . '/models/Pedido.php';
                    $pedidoModel = new Pedido();
                    $items = $pedidoModel->obtenerDetalle($p['id']);
                ?>
                    <div style="border: 2px solid #f59e0b; border-radius: 6px; padding: 18px; background: #fffbeb;">
                        <div class="flex-between" style="margin-bottom: 10px;">
                            <strong style="font-size: 1.2rem;">Pedido #<?= $p['id'] ?></strong>
                            <span class="badge badge-warning">Pendiente</span>
                        </div>
                        <p>
                            <strong>Tipo:</strong>
                            <?= $p['tipo'] === 'mesa' ? '🪑 Mesa #' . $p['mesa_numero'] : '🚴 Domicilio' ?>
                        </p>
                        <p><strong>Cliente:</strong> <?= Helper::e($p['cliente_nombre']) ?></p>

                        <details style="margin: 10px 0;">
                            <summary style="cursor: pointer; font-weight: 600;">Ver productos (<?= count($items) ?>)</summary>
                            <ul style="margin-top: 8px; padding-left: 18px;">
                                <?php foreach ($items as $item): ?>
                                    <li><?= $item['cantidad'] ?>× <?= Helper::e($item['producto_nombre']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>

                        <?php if (!empty($p['notas'])): ?>
                            <p style="background: #fff; padding: 8px; border-left: 3px solid #f59e0b; font-size: 0.88rem;">
                                <strong>📝 Notas:</strong> <?= Helper::e($p['notas']) ?>
                            </p>
                        <?php endif; ?>

                        <a href="<?= Helper::url('pedido', 'actualizarEstado', ['id' => $p['id'], 'estado' => 'en_preparacion']) ?>"
                           class="btn btn-block"
                           onclick="return confirm('¿Iniciar preparación del pedido #<?= $p['id'] ?>?')">▶ Iniciar preparación</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- EN PREPARACIÓN -->
<div class="card" style="border-top: 4px solid #3b82f6;">
    <div class="card-body">
        <h3 style="color: #1d4ed8;">🔥 En preparación (<?= count($pedidos_en_preparacion) ?>)</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem; margin-bottom: 14px;">Pedidos en curso. Avanza al siguiente estado cuando estén listos.</p>

        <?php if (empty($pedidos_en_preparacion)): ?>
            <p style="color: var(--color-text-light); padding: 20px; text-align: center;">No hay pedidos en preparación.</p>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach ($pedidos_en_preparacion as $p):
                    $info = Helper::estadoPedido($p['estado']);
                    $siguiente = $p['estado'] === 'en_preparacion' ? 'emplatado' : 'listo';
                    $textoSiguiente = $p['estado'] === 'en_preparacion' ? '🍽 Marcar emplatado' : '🔔 Marcar listo';
                ?>
                    <div style="border: 2px solid #3b82f6; border-radius: 6px; padding: 18px; background: #eff6ff;">
                        <div class="flex-between" style="margin-bottom: 10px;">
                            <strong style="font-size: 1.2rem;">Pedido #<?= $p['id'] ?></strong>
                            <span class="estado-pedido" style="background-color: <?= $info['color'] ?>; font-size: 0.75rem; padding: 4px 10px;"><?= $info['texto'] ?></span>
                        </div>
                        <p>
                            <strong>Tipo:</strong>
                            <?= $p['tipo'] === 'mesa' ? '🪑 Mesa #' . $p['mesa_numero'] : '🚴 Domicilio' ?>
                        </p>
                        <p><strong>Cliente:</strong> <?= Helper::e($p['cliente_nombre']) ?></p>

                        <a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm btn-outline btn-block" style="margin-bottom: 8px;">Ver detalle</a>
                        <a href="<?= Helper::url('pedido', 'actualizarEstado', ['id' => $p['id'], 'estado' => $siguiente]) ?>"
                           class="btn btn-sm btn-success btn-block"><?= $textoSiguiente ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<p class="text-center mt-3" style="color: var(--color-text-light); font-size: 0.85rem;">
    🔄 Auto-actualización cada 30 segundos
</p>
<script>setTimeout(function(){ location.reload(); }, 30000);</script>
