<!-- HERO -->
<section class="hero" style="margin: -48px -24px 0;">
    <div class="container">
        <p class="eyebrow">✦ Cerveza Artesanal · Desde 2015</p>
        <h1>Mr. <em>Hop</em></h1>
        <p class="tagline">
            Birra fresca recién tirada, lúpulos seleccionados y cocina honesta para acompañar.
            Un templo cervecero en el corazón de la ciudad.
        </p>
        <div class="cta-buttons">
            <a href="<?= Helper::url('carta', 'index') ?>" class="btn btn-lg">Ver carta</a>
            <?php if (!Auth::isLoggedIn()): ?>
                <a href="<?= Helper::url('auth', 'registro') ?>" class="btn btn-lg btn-ghost">Crear cuenta</a>
            <?php else: ?>
                <a href="<?= Helper::url('pedido', 'nuevo') ?>" class="btn btn-lg btn-ghost">Hacer pedido</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ESENCIA -->
<section class="section">
    <div class="section-title">
        <p class="eyebrow">✦ Nuestra esencia</p>
        <h2>Lo que hace única a <em>Mr. Hop</em></h2>
        <p>Tres pilares que definen cada visita</p>
    </div>

    <div class="grid grid-3">
        <div class="card">
            <div class="card-image">🍺</div>
            <div class="card-body">
                <h3 class="card-title">Birra artesanal</h3>
                <p class="card-text">Más de 20 grifos rotativos con cervezas de producción propia y colaboraciones con las mejores cervecerías independientes.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-image">🌿</div>
            <div class="card-body">
                <h3 class="card-title">Lúpulos premium</h3>
                <p class="card-text">Citra, Mosaic, Galaxy, Nelson Sauvin... seleccionamos los lúpulos más frescos para que cada sorbo cuente una historia.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-image">🔥</div>
            <div class="card-body">
                <h3 class="card-title">Cocina honesta</h3>
                <p class="card-text">Hamburguesas, alitas, tacos y antojos pensados para maridar con birra. Ingredientes locales, preparación sin atajos.</p>
            </div>
        </div>
    </div>
</section>

<!-- RESEÑAS DESTACADAS -->
<?php if (!empty($resenas_destacadas)): ?>
<section class="section" style="background: var(--color-bg-alt); margin: 40px -24px; padding: 70px 24px; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="section-title">
        <p class="eyebrow">✦ Testimonios</p>
        <h2>Lo que dicen <em>nuestros parroquianos</em></h2>
        <p>
            <span class="stars"><?= str_repeat('★', (int)$promedio) . str_repeat('☆', 5 - (int)$promedio) ?></span>
            <strong style="color: var(--color-primary);"><?= $promedio ?>/5</strong>
        </p>
    </div>

    <div class="grid grid-3">
        <?php foreach ($resenas_destacadas as $r): ?>
            <div class="resena-card">
                <div class="resena-header">
                    <span class="resena-autor"><?= Helper::e($r['usuario_nombre']) ?></span>
                    <span class="stars"><?= str_repeat('★', $r['calificacion']) ?></span>
                </div>
                <p style="font-style: italic; color: var(--color-text);">"<?= Helper::e($r['comentario']) ?>"</p>
                <p class="resena-fecha" style="margin-top: 12px;"><?= Helper::fecha($r['fecha'], 'd/m/Y') ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <a href="<?= Helper::url('resena', 'index') ?>" class="btn btn-outline">Ver todas las reseñas</a>
    </div>
</section>
<?php endif; ?>

<!-- AMBIENTE DESTACADO -->
<?php if (!empty($ambientes)): ?>
<section class="section">
    <div class="section-title">
        <p class="eyebrow">✦ El lugar</p>
        <h2>Conoce <em>nuestro espacio</em></h2>
        <p>Un sitio diseñado para cada ocasión</p>
    </div>

    <div class="grid grid-3">
        <?php foreach ($ambientes as $a): ?>
            <div class="card">
                <div class="card-image">🏛</div>
                <div class="card-body">
                    <h3 class="card-title"><?= Helper::e($a['titulo']) ?></h3>
                    <p class="card-text"><?= Helper::e($a['descripcion']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
