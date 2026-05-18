<div class="section-title">
    <h2>Nuestra Carta</h2>
    <p>Sabores que cuentan historias</p>
</div>

<?php
$secciones = [
    ['titulo' => '🍷 Bebidas', 'productos' => $bebidas, 'descripcion' => 'Cervezas, cócteles, vinos y refrescos'],
    ['titulo' => '🍽 Comidas', 'productos' => $comidas, 'descripcion' => 'Entradas, platos fuertes y postres'],
    ['titulo' => '🎉 Combos', 'productos' => $combos, 'descripcion' => 'Especiales para compartir'],
];
?>

<?php foreach ($secciones as $seccion): ?>
    <section class="section" style="padding-top: 20px; padding-bottom: 40px;">
        <h2 style="border-bottom: 3px solid var(--color-accent); padding-bottom: 10px; color: var(--color-primary-dark);">
            <?= $seccion['titulo'] ?>
        </h2>
        <p style="color: var(--color-text-light); font-style: italic; margin-bottom: 24px;"><?= $seccion['descripcion'] ?></p>

        <?php if (empty($seccion['productos'])): ?>
            <p style="color: var(--color-text-light);">No hay productos disponibles en esta categoría.</p>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($seccion['productos'] as $p): ?>
                    <div class="card">
                        <div class="card-image">
                            <?= strpos($p['categoria_tipo'], 'bebida') !== false ? '🍹' :
                                (strpos($p['categoria_tipo'], 'combo') !== false ? '🍽' : '🍴') ?>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= Helper::e($p['nombre']) ?></h3>
                            <p class="card-text"><?= Helper::e($p['descripcion']) ?></p>
                            <p style="font-size: 0.8rem; color: var(--color-text-light); text-transform: uppercase; letter-spacing: 1px;">
                                <?= Helper::e($p['categoria_nombre']) ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <span class="card-price"><?= Helper::precio($p['precio']) ?></span>
                            <?php if (Auth::isLoggedIn() && Auth::hasRole('cliente')): ?>
                                <a href="<?= Helper::url('pedido', 'nuevo') ?>" class="btn btn-sm">Pedir</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endforeach; ?>

<?php if (!Auth::isLoggedIn()): ?>
    <div class="text-center" style="background: var(--color-bg-dark); color: #f5ebde; padding: 40px; border-radius: 6px; margin-top: 40px;">
        <h3 style="color: var(--color-accent);">¿Listo para hacer un pedido?</h3>
        <p style="margin: 14px 0 22px;">Crea una cuenta o inicia sesión para ordenar.</p>
        <a href="<?= Helper::url('auth', 'registro') ?>" class="btn btn-lg">Crear cuenta</a>
        <a href="<?= Helper::url('auth', 'login') ?>" class="btn btn-lg btn-outline" style="color: #fff; border-color: #fff; margin-left: 10px;">Iniciar sesión</a>
    </div>
<?php endif; ?>
