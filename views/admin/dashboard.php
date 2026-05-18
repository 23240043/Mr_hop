<div class="section-title">
    <h2>Panel de Administración</h2>
    <p>Bienvenido, <?= Helper::e(Auth::user()['nombre']) ?></p>
</div>

<!-- ESTADÍSTICAS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Pedidos totales</div>
        <div class="stat-value"><?= $stats['total_pedidos'] ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #f59e0b;">
        <div class="stat-label">Pendientes</div>
        <div class="stat-value"><?= $stats['pendientes'] ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #3b82f6;">
        <div class="stat-label">En proceso</div>
        <div class="stat-value"><?= $stats['en_preparacion'] ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #10b981;">
        <div class="stat-label">Entregados hoy</div>
        <div class="stat-value"><?= $stats['entregados_hoy'] ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--color-accent);">
        <div class="stat-label">Ingresos hoy</div>
        <div class="stat-value"><?= Helper::precio($stats['ingresos_hoy']) ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #8b5cf6;">
        <div class="stat-label">Usuarios</div>
        <div class="stat-value"><?= $total_usuarios ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #ec4899;">
        <div class="stat-label">Reseñas</div>
        <div class="stat-value"><?= $total_resenas ?></div>
    </div>
</div>

<!-- ACCESO RÁPIDO -->
<h3 style="color: var(--color-primary-dark); margin-bottom: 20px;">Gestión rápida</h3>
<div class="grid grid-4">
    <a href="<?= Helper::url('admin', 'usuarios') ?>" class="card" style="text-decoration: none;">
        <div class="card-body text-center" style="padding: 30px;">
            <div style="font-size: 3rem;">👥</div>
            <h3 class="card-title">Usuarios</h3>
            <p class="card-text">Gestionar cuentas y roles</p>
        </div>
    </a>
    <a href="<?= Helper::url('carta', 'gestionar') ?>" class="card" style="text-decoration: none;">
        <div class="card-body text-center" style="padding: 30px;">
            <div style="font-size: 3rem;">🍽</div>
            <h3 class="card-title">Carta</h3>
            <p class="card-text">Productos y precios</p>
        </div>
    </a>
    <a href="<?= Helper::url('pedido', 'listar') ?>" class="card" style="text-decoration: none;">
        <div class="card-body text-center" style="padding: 30px;">
            <div style="font-size: 3rem;">📋</div>
            <h3 class="card-title">Pedidos</h3>
            <p class="card-text">Ver todos los pedidos</p>
        </div>
    </a>
    <a href="<?= Helper::url('resena', 'gestionar') ?>" class="card" style="text-decoration: none;">
        <div class="card-body text-center" style="padding: 30px;">
            <div style="font-size: 3rem;">⭐</div>
            <h3 class="card-title">Reseñas</h3>
            <p class="card-text">Moderar reseñas</p>
        </div>
    </a>

    <!-- BOTÓN DE RESPALDO -->
    <a href="<?= Helper::url('admin', 'backupDB') ?>" class="card" style="text-decoration: none;">
        <div class="card-body text-center" style="padding: 30px;">
            <div style="font-size: 3rem;">💾</div>
            <h3 class="card-title">Respaldo</h3>
            <p class="card-text">Generar copia de seguridad</p>
        </div>
    </a>

    <a href="<?= Helper::url('admin', 'vistaRestoreDB') ?>" class="card" style="text-decoration: none;">
    <div class="card-body text-center" style="padding: 30px;">
        <div style="font-size: 3rem;">♻️</div>
        <h3 class="card-title">Restaurar BD</h3>
        <p class="card-text">Subir respaldo SQL</p>
    </div>
</a>

</div>

<!-- PEDIDOS RECIENTES -->
<h3 style="color: var(--color-primary-dark); margin: 30px 0 20px;">Pedidos recientes</h3>
<?php if (empty($pedidos_recientes)): ?>
    <p style="color: var(--color-text-light);">No hay pedidos recientes.</p>
<?php else: ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos_recientes as $p):
                    $info = Helper::estadoPedido($p['estado']);
                ?>
                    <tr>
                        <td>#<?= $p['id'] ?></td>
                        <td><?= Helper::e($p['cliente_nombre']) ?></td>
                        <td><?= Helper::fecha($p['fecha_pedido']) ?></td>
                        <td><?= Helper::precio($p['total']) ?></td>
                        <td><span class="estado-pedido" style="background-color: <?= $info['color'] ?>; font-size: 0.75rem; padding: 4px 10px;"><?= $info['texto'] ?></span></td>
                        <td><a href="<?= Helper::url('pedido', 'ver', ['id' => $p['id']]) ?>" class="btn btn-sm">Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
