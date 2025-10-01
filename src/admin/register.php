<?php
session_start();
include 'conexion.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        $query = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = 'Este correo electrónico ya está registrado';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellido, $email, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Usuario registrado exitosamente. Ahora puedes iniciar sesión.';
                $nombre = $apellido = $email = '';
            } else {
                $error = 'Error al registrar el usuario. Inténtalo de nuevo.';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Wilrop Colombia Travel</title>
    <meta name="description" content="Regístrate en Wilrop Colombia Travel para acceder a servicios exclusivos y gestionar tus reservas.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-container">
                        <img src="/imagenes/logos/wilrop_vertical.png" alt="Wilrop Colombia Travel" class="logo-image">
                        <div class="logo-text">
                            <h6>Wilrop Colombia Travel</h6>
                        </div>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="/index.php" class="nav-link">Inicio</a></li>
                    <li><a href="/src/dominicana/dominicana.php" class="nav-link">República Dominicana</a></li>
                    <li><a href="/src/colombia/colombia.php" class="nav-link">Colombia</a></li>
                    <li><a href="/index.php#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="/products.html" class="nav-link">Productos</a></li>
                    <li><a href="/src/admin/admin.php" class="nav-link">Admin</a></li>
                    <li><a href="/index.php#contacto" class="nav-link">Contacto</a></li>
                    <li><a href="/src/admin/login.php" class="nav-link login-btn">Iniciar Sesión</a></li>
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

    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-content">
                    <div class="login-header">
                        <h2>Crear Cuenta</h2>
                        <p>Únete a Wilrop Colombia Travel y descubre experiencias únicas</p>
                    </div>

                    <form class="login-form" method="POST" action="/src/admin/register.php">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="apellido" name="apellido" required placeholder="Tu apellido" value="<?php echo isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required placeholder="tu@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" required placeholder="Mínimo 6 caracteres">
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            <div class="password-requirements">
                                La contraseña debe tener al menos 6 caracteres
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirmar Contraseña</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirma tu contraseña">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="fas fa-user-plus"></i>
                            Crear Cuenta
                        </button>

                        <div class="login-footer">
                            <p>¿Ya tienes una cuenta? <a href="/src/admin/login.php" class="register-link">Inicia sesión aquí</a></p>
                        </div>
                    </form>
                </div>

                <div class="login-image">
                    <div class="login-bg">
                        <div class="login-overlay"></div>
                        <div class="login-text">
                            <h3>¡Únete a la aventura!</h3>
                            <p>Crea tu cuenta y empieza a gestionar tus viajes con Wilrop Colombia Travel</p>
                            <div class="login-features">
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Gestiona tus reservas</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Accede a ofertas exclusivas</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Recibe notificaciones de viajes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Wilrop Colombia Travel</h3>
                    <p>Especialistas en turismo entre República Dominicana y Colombia. Tu agencia de confianza para experiencias únicas.</p>
                </div>
                <div class="footer-section">
                    <h4>Destinos</h4>
                    <ul>
                        <li><a href="/src/dominicana/dominicana.php">República Dominicana</a></li>
                        <li><a href="/src/colombia/colombia.php">Colombia</a></li>
                        <li><a href="/src/colombia/colombia.php#antioquia">Antioquia</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="/index.php">Inicio</a></li>
                        <li><a href="/products.html">Productos</a></li>
                        <li><a href="/src/admin/admin.php">Admin</a></li>
                        <li><a href="/index.php#contacto">Contacto</a></li>
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

    <script src="/scripts.js"></script>
</body>
</html>
