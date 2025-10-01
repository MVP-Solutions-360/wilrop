<?php
session_start();
include 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        // Buscar usuario por email
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // Verificar contraseña (usando password_verify para mayor seguridad)
            if (password_verify($password, $user['password']) || md5($password) === $user['password']) {
                // Login exitoso
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'] . ' ' . $user['apellido'];
                $_SESSION['correo'] = $user['email'];
                $_SESSION['usuario'] = $user['email']; // Usar email como usuario
                
                // Redirigir según el tipo de usuario
                if ($user['id'] == 1) {
                    header('Location: admin.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = 'Por favor completa todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Wilrop Colombia Travel</title>
    <meta name="description" content="Inicia sesión en Wilrop Colombia Travel para acceder a servicios exclusivos y gestionar tus reservas.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
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
                    <li><a href="/src/admin/login.php" class="nav-link login-btn active">Iniciar Sesión</a></li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
        <!-- Overlay para menú móvil -->
        <div class="mobile-menu-overlay"></div>
    </header>

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-content">
                    <div class="login-header">
                        <h2>Iniciar Sesión</h2>
                        <p>Accede a tu cuenta para gestionar tus reservas y disfrutar de servicios exclusivos</p>
                    </div>
                    
                    <form class="login-form" method="POST" action="/src/admin/login.php">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
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
                                <input type="password" id="password" name="password" required placeholder="Tu contraseña">
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-options">
                            <label class="checkbox-container">
                                <input type="checkbox" id="rememberMe" name="rememberMe">
                                <span class="checkmark"></span>
                                Recordarme
                            </label>
                            <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </button>
                        <div class="login-divider">
                            <span>o</span>
                        </div>
                        <div class="social-login">
                            <button type="button" class="btn btn-social btn-google">
                                <i class="fab fa-google"></i>
                                Continuar con Google
                            </button>
                            <button type="button" class="btn btn-social btn-facebook">
                                <i class="fab fa-facebook-f"></i>
                                Continuar con Facebook
                            </button>
                        </div>
                    </form>
                    
                    <div class="login-footer">
                        <p>¿No tienes una cuenta? <a href="/src/admin/register.php" class="register-link">Regístrate aquí</a></p>
                    </div>
                </div>
                
                <div class="login-image">
                    <div class="login-bg">
                        <div class="login-overlay"></div>
                        <div class="login-text">
                            <h3>¡Bienvenido de vuelta!</h3>
                            <p>Accede a tu cuenta y descubre nuevas aventuras con Wilrop Colombia Travel</p>
                            <div class="login-features">
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Gestiona tus reservas</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Acceso a ofertas exclusivas</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Historial de viajes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <li><a href="dominicana.html">República Dominicana</a></li>
                        <li><a href="colombia.html">Colombia</a></li>
                        <li><a href="colombia.html#antioquia">Antioquia</a></li>
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
