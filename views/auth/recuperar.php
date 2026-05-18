<div class="form-container">
    <h2>Recuperar contraseña</h2>

    <div class="alert alert-info">
        <strong>🔧 Función en desarrollo</strong><br>
        La recuperación por correo se implementará próximamente.
        Por ahora, contacta al administrador si olvidaste tu contraseña.
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="tu@correo.com" disabled>
        </div>
        <button type="submit" class="btn btn-block" disabled>Enviar enlace de recuperación</button>
    </form>

    <p class="text-center mt-3">
        <a href="<?= Helper::url('auth', 'login') ?>">← Volver al inicio de sesión</a>
    </p>
</div>
