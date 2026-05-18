<?php
$estadoInfo = Helper::estadoPedido($pedido['estado']);
$progreso = $estadoInfo['progreso'];
$color = $estadoInfo['color'];
?>

<div class="flex-between mb-3">
    <h2>Pedido #<?= $pedido['id'] ?></h2>
    <a href="<?= Helper::url('pedido', 'misPedidos') ?>" class="btn btn-outline btn-sm">← Mis pedidos</a>
</div>

<!-- BARRA DE PROGRESO -->
<div class="progress-pedido">
    <div class="flex-between">
        <div>
            <p style="color: var(--color-text-light); margin: 0; font-size: 0.9rem;">Estado actual:</p>
            <span class="estado-pedido" style="background-color: <?= $color ?>;"><?= $estadoInfo['texto'] ?></span>
        </div>
        <div class="text-right">
            <p style="color: var(--color-text-light); margin: 0; font-size: 0.9rem;">Realizado:</p>
            <strong><?= Helper::fecha($pedido['fecha_pedido']) ?></strong>
        </div>
    </div>

    <div class="progress-bar-wrap">
        <div class="progress-bar-fill" style="width: <?= $progreso ?>%;">
            <?= $progreso ?>%
        </div>
    </div>

    <!-- ETAPAS -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; text-align: center; margin-top: 14px;">
        <?php
        $etapas = [
            ['nombre' => 'Pendiente',     'min' => 10],
            ['nombre' => 'Preparación',   'min' => 40],
            ['nombre' => 'Emplatado',     'min' => 70],
            ['nombre' => 'Listo',         'min' => 90],
            ['nombre' => 'Entregado',     'min' => 100],
        ];
        foreach ($etapas as $etapa):
            $activa = $progreso >= $etapa['min'];
        ?>
            <div style="padding: 8px 4px; border-radius: 6px;
                        background: <?= $activa ? 'var(--color-primary)' : 'var(--color-border)' ?>;
                        color: <?= $activa ? '#fff' : 'var(--color-text-light)' ?>;
                        font-size: 0.78rem; font-weight: 600;">
                <?= $activa ? '✓ ' : '' ?><?= $etapa['nombre'] ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- DATOS DEL PEDIDO -->
<div class="grid grid-2">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">📋 Detalles del pedido</h3>
            <p><strong>Tipo:</strong>
                <?= $pedido['tipo'] === 'mesa' ? '🪑 En mesa #' . $pedido['mesa_numero'] : '🚴 A domicilio' ?>
            </p>
            <?php if ($pedido['tipo'] === 'domicilio'): ?>
                <p><strong>Dirección:</strong> <?= Helper::e($pedido['direccion_entrega']) ?></p>
            <?php endif; ?>
            <p><strong>Cliente:</strong> <?= Helper::e($pedido['cliente_nombre'] . ' ' . $pedido['cliente_apellidos']) ?></p>
            <p><strong>Teléfono:</strong> <?= Helper::e($pedido['cliente_telefono']) ?: '—' ?></p>
            <?php if (!empty($pedido['notas'])): ?>
                <p><strong>Notas:</strong> <?= Helper::e($pedido['notas']) ?></p>
            <?php endif; ?>
            <p><strong>Última actualización:</strong> <?= Helper::fecha($pedido['fecha_actualizacion']) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title">🍽 Productos pedidos</h3>
            <table class="table" style="background: transparent; box-shadow: none; border: none;">
                <thead>
                    <tr><th>Producto</th><th>Cant.</th><th class="text-right">Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $d): ?>
                        <tr>
                            <td><?= Helper::e($d['producto_nombre']) ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td class="text-right"><?= Helper::precio($d['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="carrito-total">
                Total: <?= Helper::precio($pedido['total']) ?>
            </div>
        </div>
    </div>
</div>

<!-- ACCIONES SEGÚN ROL -->
<?php if (Auth::hasRole(['administrador','mesero','barra']) && !in_array($pedido['estado'], ['entregado','cancelado'])): ?>
    <div class="card mt-3">
        <div class="card-body">
            <h3 class="card-title">⚙ Cambiar estado</h3>
            <form method="POST" action="<?= Helper::url('pedido', 'actualizarEstado') ?>" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                <select name="estado" class="form-control" style="max-width: 260px;">
                    <option value="pendiente"      <?= $pedido['estado']==='pendiente'?'selected':'' ?>>Pendiente</option>
                    <option value="en_preparacion" <?= $pedido['estado']==='en_preparacion'?'selected':'' ?>>En preparación</option>
                    <option value="emplatado"      <?= $pedido['estado']==='emplatado'?'selected':'' ?>>Emplatado</option>
                    <option value="listo"          <?= $pedido['estado']==='listo'?'selected':'' ?>>Listo para servir</option>
                    <option value="entregado"     <?= $pedido['estado']==='entregado'?'selected':'' ?>>Entregado</option>
                    <option value="cancelado"     <?= $pedido['estado']==='cancelado'?'selected':'' ?>>Cancelado</option>
                </select>
                <button type="submit" class="btn">Actualizar</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Auto-refresh para cliente cada 30s -->
<?php if (Auth::hasRole('cliente') && !in_array($pedido['estado'], ['entregado','cancelado'])): ?>
    <script>setTimeout(function(){ location.reload(); }, 30000);</script>
    <p class="text-center mt-3" style="color: var(--color-text-light); font-size: 0.85rem;">
        🔄 Esta página se actualiza automáticamente cada 30 segundos
    </p>
<?php endif; ?>
