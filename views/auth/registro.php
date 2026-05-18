<div class="form-container" style="max-width: 600px;">
    <h2>Crear cuenta</h2>
    <p class="text-center" style="color: var(--color-text-light); margin-bottom: 24px; font-style: italic;">Únete a la familia Mr. Hop</p>

    <form method="POST" action="<?= Helper::url('auth', 'registro') ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Correo electrónico *</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="tel" name="telefono" class="form-control">
        </div>

        <div class="form-group">
            <label>Dirección (para envíos a domicilio)</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña *</label>
                <input type="password" name="password" class="form-control" required minlength="5">
            </div>
            <div class="form-group">
                <label>Confirmar contraseña *</label>
                <input type="password" name="confirmar_password" class="form-control" required minlength="5">
            </div>
        </div>

        <button type="submit" class="btn btn-block btn-lg">Crear cuenta</button>

        <p class="text-center mt-3" style="font-size: 0.95rem;">
            ¿Ya tienes cuenta? <a href="<?= Helper::url('auth', 'login') ?>"><strong>Inicia sesión</strong></a>
        </p>
    </form>
</div>
