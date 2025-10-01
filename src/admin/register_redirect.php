<?php
// Redirección después de registro exitoso
session_start();
if (!isset($_SESSION['active'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Wilrop Colombia Travel</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-container">
                        <img src="../../imagenes/logos/wilrop_vertical.png" alt="Wilrop Colombia Travel" class="logo-image">
                        <div class="logo-text">
                            <h6>Wilrop Colombia Travel</h6>
                        </div>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="../../index.php" class="nav-link">Inicio</a></li>
                    <li><a href="../../src/dominicana/dominicana.php" class="nav-link">República Dominicana</a></li>
                    <li><a href="../../src/colombia/colombia.php" class="nav-link">Colombia</a></li>
                    <li><a href="../../index.php#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="../../src/producto/products.php" class="nav-link">Productos</a></li>
                    <li><a href="../../src/admin/admin.php" class="nav-link">Admin</a></li>
                    <li><a href="../../index.php#contacto" class="nav-link">Contacto</a></li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
        <div class="mobile-menu-overlay"></div>
    </header>
    <section class="register-redirect-section">
        <div class="container">
            <div class="section-header">
                <h2>¡Registro Exitoso!</h2>
                <p>Tu cuenta ha sido creada correctamente. Ahora puedes acceder a todas las funcionalidades del sistema.</p>
            </div>
            <div class="redirect-actions">
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                <a href="../../index.php" class="btn btn-secondary">Ir al Inicio</a>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Wilrop Colombia Travel</h3>
                    <p>Especialistas en turismo entre República Dominicana y Antioquia, Colombia. Tu agencia de confianza para experiencias únicas.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="../../index.php#destinos">Destinos</a></li>
                        <li><a href="../../index.php#servicios">Servicios</a></li>
                        <li><a href="../../src/producto/products.php">Productos</a></li>
                        <li><a href="admin.php">Admin</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-phone"></i> +1 (809) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> info@wilropcolombia.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Wilrop Colombia Travel. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="../../scripts.js"></script>
</body>
</html>
