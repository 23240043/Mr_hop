<div class="section-title">
    <h2>Nuestro Ambiente</h2>
    <p>Espacios pensados para cada momento</p>
</div>

<?php if (empty($ambientes)): ?>
    <p class="text-center" style="color: var(--color-text-light); padding: 40px;">Aún no hay información de ambientes disponible.</p>
<?php else: ?>
    <div class="grid grid-2">
        <?php foreach ($ambientes as $a): ?>
            <div class="card">
                <div class="card-image" style="height: 280px; background: linear-gradient(135deg, #8a541d, #2a1f17);">
                    <span style="font-size: 4rem;">🏛</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?= Helper::e($a['titulo']) ?></h3>
                    <p class="card-text"><?= Helper::e($a['descripcion']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="text-center mt-4" style="background: #fff; padding: 40px; border-radius: 6px; margin-top: 40px; border-top: 4px solid var(--color-accent);">
    <h3 style="color: var(--color-primary-dark);">¿Quieres reservar un espacio?</h3>
    <p style="color: var(--color-text-light); margin: 14px 0 22px;">Llámanos al <strong>(222) 555-1234</strong> o pásate por el local.</p>
    <a href="<?= Helper::url('home', 'contacto') ?>" class="btn">Más información</a>
</div>
