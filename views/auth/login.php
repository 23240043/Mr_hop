<div class="form-container">
    <h2>Iniciar sesión</h2>
    <p class="text-center" style="color: var(--color-text-light); margin-bottom: 24px; font-style: italic;">Bienvenido de vuelta a Mr. Hop</p>

    <form method="POST" action="<?= Helper::url('auth', 'login') ?>">
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-block btn-lg">Entrar</button>

        <p class="text-center mt-3" style="font-size: 0.92rem;">
            <a href="<?= Helper::url('auth', 'recuperar') ?>">¿Olvidaste tu contraseña?</a>
        </p>
        <hr style="margin: 22px 0; border: none; border-top: 1px solid var(--color-border);">
        <p class="text-center" style="font-size: 0.95rem;">
            ¿No tienes cuenta? <a href="<?= Helper::url('auth', 'registro') ?>"><strong>Regístrate aquí</strong></a>
        </p>
    </form>

    <!-- Tip de usuarios de prueba -->
    <div style="background: #fdf3e3; padding: 14px; border-radius: 6px; margin-top: 20px; font-size: 0.85rem;">
        <strong style="color: #7a5a1a;">💡 Usuarios de prueba (contraseña: <code>12345</code>):</strong>
        <ul style="margin: 8px 0 0 18px; color: #7a5a1a;">
            <li>admin@bar.com (Admin)</li>
            <li>cliente@bar.com (Cliente)</li>
            <li>mesero@bar.com (Mesero)</li>
            <li>barra@bar.com (Barra)</li>
        </ul>
    </div>
</div>
