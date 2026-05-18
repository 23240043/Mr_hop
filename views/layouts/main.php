<?php
$usuario = Auth::user();
$rol = $usuario['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($titulo ?? 'Inicio') ?> · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>" class="navbar-brand">
            <span class="navbar-brand-icon">M</span>
            <span class="navbar-brand-text">
                MR. HOP
                <small>Cerveza Artesanal</small>
            </span>
        </a>
        <ul class="navbar-menu">
            <li><a href="<?= Helper::url('home', 'index') ?>">Inicio</a></li>
            <li><a href="<?= Helper::url('home', 'historia') ?>">Historia</a></li>
            <li><a href="<?= Helper::url('carta', 'index') ?>">Carta</a></li>
            <li><a href="<?= Helper::url('home', 'ambiente') ?>">Ambiente</a></li>
            <li><a href="<?= Helper::url('resena', 'index') ?>">Reseñas</a></li>

            <?php if (Auth::isLoggedIn()): ?>
                <?php if ($rol === 'cliente'): ?>
                    <li><a href="<?= Helper::url('pedido', 'nuevo') ?>">Pedir</a></li>
                    <li><a href="<?= Helper::url('pedido', 'misPedidos') ?>">Mis Pedidos</a></li>
                <?php elseif ($rol === 'administrador'): ?>
                    <li><a href="<?= Helper::url('admin', 'dashboard') ?>">Admin</a></li>
                <?php elseif ($rol === 'mesero'): ?>
                    <li><a href="<?= Helper::url('mesero', 'dashboard') ?>">Mesero</a></li>
                <?php elseif ($rol === 'barra'): ?>
                    <li><a href="<?= Helper::url('barra', 'dashboard') ?>">Barra</a></li>
                <?php endif; ?>

                <li class="navbar-user">
                    <span><strong><?= Helper::e($usuario['nombre']) ?></strong></span>
                    <a href="<?= Helper::url('auth', 'logout') ?>" class="btn btn-sm btn-ghost">Salir</a>
                </li>
            <?php else: ?>
                <li><a href="<?= Helper::url('auth', 'login') ?>">Iniciar sesión</a></li>
                <li><a href="<?= Helper::url('auth', 'registro') ?>" class="btn btn-sm">Crear cuenta</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- CONTENIDO -->
<main class="main-content">
    <div class="container">
        <?php if ($msg = Helper::getFlash('success')): ?>
            <div class="alert alert-success">✓ <?= Helper::e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Helper::getFlash('error')): ?>
            <div class="alert alert-error">⚠ <?= Helper::e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Helper::getFlash('info')): ?>
            <div class="alert alert-info">ℹ <?= Helper::e($msg) ?></div>
        <?php endif; ?>

        <?= $contenido ?>
    </div>
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4>Mr. Hop</h4>
                <p>Cervecería artesanal desde 2015. Birra fresca, cocina honesta y la mejor selección de lúpulos de la ciudad.</p>
            </div>
            <div>
                <h4>Navegación</h4>
                <ul>
                    <li><a href="<?= Helper::url('home', 'index') ?>">Inicio</a></li>
                    <li><a href="<?= Helper::url('home', 'historia') ?>">Historia</a></li>
                    <li><a href="<?= Helper::url('carta', 'index') ?>">Carta</a></li>
                    <li><a href="<?= Helper::url('resena', 'index') ?>">Reseñas</a></li>
                </ul>
            </div>
            <div>
                <h4>Contacto</h4>
                <ul>
                    <li>📍 Av. Reforma 123, Centro</li>
                    <li>📞 (222) 555-1234</li>
                    <li>✉ hola@mrhop.com</li>
                    <li>🕐 Mar-Dom · 17:00 - 02:00</li>
                </ul>
            </div>
            <div>
                <h4>Síguenos</h4>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Untappd</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> <?= APP_NAME ?> · Todos los derechos reservados
        </div>
    </div>
</footer>

</body>
</html>
