<div class="section-title">
    <h2>Reseñas de Clientes</h2>
    <p>
        <?php if ($promedio > 0): ?>
            Calificación promedio:
            <span class="stars"><?= str_repeat('★', (int)$promedio) . str_repeat('☆', 5 - (int)$promedio) ?></span>
            <strong><?= $promedio ?>/5</strong>
        <?php else: ?>
            Sé el primero en dejar una reseña
        <?php endif; ?>
    </p>
</div>

<?php if (Auth::isLoggedIn()): ?>
    <div class="text-center mb-4">
        <a href="<?= Helper::url('resena', 'nueva') ?>" class="btn btn-lg">✍ Escribir una reseña</a>
    </div>
<?php else: ?>
    <div class="alert alert-info text-center">
        <a href="<?= Helper::url('auth', 'login') ?>">Inicia sesión</a> para dejar tu propia reseña.
    </div>
<?php endif; ?>

<?php if (empty($resenas)): ?>
    <p class="text-center" style="color: var(--color-text-light); padding: 40px;">Aún no hay reseñas publicadas. ¡Sé el primero!</p>
<?php else: ?>
    <div style="max-width: 800px; margin: 0 auto;">
        <?php foreach ($resenas as $r): ?>
            <div class="resena-card">
                <div class="resena-header">
                    <span class="resena-autor"><?= Helper::e($r['usuario_nombre'] . ' ' . $r['usuario_apellidos']) ?></span>
                    <span class="stars"><?= str_repeat('★', $r['calificacion']) . str_repeat('☆', 5 - $r['calificacion']) ?></span>
                </div>
                <p style="font-style: italic; color: var(--color-text);">"<?= Helper::e($r['comentario']) ?>"</p>
                <p class="resena-fecha" style="margin-top: 12px;"><?= Helper::fecha($r['fecha']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
