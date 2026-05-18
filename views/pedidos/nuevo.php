<div class="section-title">
    <h2>Hacer un Pedido</h2>
    <p>Elige el tipo de pedido y los productos</p>
</div>

<form method="POST" action="<?= Helper::url('pedido', 'guardar') ?>">

    <!-- TIPO DE PEDIDO -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">1. Tipo de pedido</h3>
            <div class="form-row">
                <div class="form-check">
                    <input type="radio" id="tipo_mesa" name="tipo" value="mesa" checked onchange="toggleTipo()">
                    <label for="tipo_mesa"><strong>🪑 En mesa</strong> — Selecciona tu mesa</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="tipo_domicilio" name="tipo" value="domicilio" onchange="toggleTipo()">
                    <label for="tipo_domicilio"><strong>🚴 A domicilio</strong> — Envío a tu dirección</label>
                </div>
            </div>

            <div id="campo_mesa" class="form-group" style="margin-top: 14px;">
                <label>Selecciona tu mesa</label>
                <select name="mesa_id" class="form-control">
                    <option value="">— Selecciona —</option>
                    <?php foreach ($mesas as $m): ?>
                        <option value="<?= $m['id'] ?>">
                            Mesa #<?= $m['numero'] ?> · <?= Helper::e($m['ubicacion']) ?> (capacidad: <?= $m['capacidad'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="campo_domicilio" class="form-group" style="margin-top: 14px; display: none;">
                <label>Dirección de entrega</label>
                <input type="text" name="direccion_entrega" class="form-control" placeholder="Calle, número, colonia, referencias...">
            </div>
        </div>
    </div>

    <!-- PRODUCTOS -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">2. Selecciona productos</h3>
            <p style="color: var(--color-text-light); font-size: 0.9rem;">Indica la cantidad de cada producto que deseas pedir (deja en 0 los que no quieras).</p>

            <?php
            $todasSecciones = [
                ['titulo' => '🍷 Bebidas', 'productos' => $bebidas],
                ['titulo' => '🍽 Comidas', 'productos' => $comidas],
                ['titulo' => '🎉 Combos', 'productos' => $combos],
            ];
            $contador = 0;
            ?>

            <?php foreach ($todasSecciones as $sec): ?>
                <?php if (!empty($sec['productos'])): ?>
                    <h4 style="color: var(--color-primary-dark); margin-top: 20px; padding-bottom: 6px; border-bottom: 2px solid var(--color-accent);">
                        <?= $sec['titulo'] ?>
                    </h4>
                    <div class="grid grid-2" style="margin-top: 14px;">
                        <?php foreach ($sec['productos'] as $p): ?>
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; padding: 12px; border: 1px solid var(--color-border); border-radius: 6px; background: #fff; align-items: center;">
                                <div>
                                    <strong><?= Helper::e($p['nombre']) ?></strong><br>
                                    <span style="color: var(--color-primary-dark); font-weight: 600;"><?= Helper::precio($p['precio']) ?></span>
                                    <br><span style="font-size: 0.82rem; color: var(--color-text-light);"><?= Helper::e($p['descripcion']) ?></span>
                                </div>
                                <div>
                                    <input type="hidden" name="producto_id[]" value="<?= $p['id'] ?>">
                                    <input type="number" name="cantidad[]" value="0" min="0" max="50"
                                           class="form-control"
                                           style="width: 75px; text-align: center; padding: 8px;"
                                           data-precio="<?= $p['precio'] ?>"
                                           onchange="actualizarTotal()">
                                </div>
                            </div>
                            <?php $contador++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- NOTAS -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">3. Notas adicionales (opcional)</h3>
            <textarea name="notas" class="form-control" rows="3" placeholder="Ej: sin cebolla, sin picante, alergias..."></textarea>
        </div>
    </div>

    <!-- TOTAL Y ENVIAR -->
    <div class="card" style="border-top: 4px solid var(--color-primary);">
        <div class="card-body">
            <div class="flex-between" style="margin-bottom: 20px;">
                <h3 style="margin: 0;">Total estimado:</h3>
                <span style="font-family: var(--font-display); font-size: 2rem; color: var(--color-primary-dark); font-weight: 700;">
                    $<span id="total">0.00</span>
                </span>
            </div>
            <button type="submit" class="btn btn-block btn-lg btn-success">Confirmar pedido →</button>
        </div>
    </div>
</form>

<script>
function toggleTipo() {
    var tipo = document.querySelector('input[name="tipo"]:checked').value;
    document.getElementById('campo_mesa').style.display = (tipo === 'mesa') ? 'block' : 'none';
    document.getElementById('campo_domicilio').style.display = (tipo === 'domicilio') ? 'block' : 'none';
}

function actualizarTotal() {
    var total = 0;
    document.querySelectorAll('input[name="cantidad[]"]').forEach(function(input) {
        var cant = parseInt(input.value) || 0;
        var precio = parseFloat(input.dataset.precio) || 0;
        total += cant * precio;
    });
    document.getElementById('total').textContent = total.toFixed(2);
}
</script>
