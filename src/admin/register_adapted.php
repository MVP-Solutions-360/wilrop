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
    
    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        // Verificar si el correo ya existe
        $query = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = 'Este correo electrónico ya está registrado';
        } else {
            // Insertar nuevo usuario
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellido, $email, $password_hash);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Usuario registrado exitosamente. Ahora puedes iniciar sesión.';
                // Limpiar formulario
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
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .register-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }
        .register-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .register-content {
            padding: 3rem;
        }
        .register-header h2 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        .register-header p {
            color: #666;
            margin-bottom: 2rem;
        }
        .register-form .form-group {
            margin-bottom: 1.5rem;
        }
        .register-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        .register-form .input-group {
            position: relative;
        }
        .register-form .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .register-form input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .register-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .password-requirements {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
        .register-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .register-bg {
            text-align: center;
            color: white;
            padding: 2rem;
        }
        .register-bg h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .register-features {
            margin-top: 2rem;
        }
        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .feature i {
            margin-right: 1rem;
            color: #4CAF50;
        }
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert i {
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .register-container {
                grid-template-columns: 1fr;
                margin: 1rem;
            }
            .register-image {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-container">
                        <img src="imagenes/logos/wilrop_vertical.png" alt="Wilrop Colombia Travel" class="logo-image">
                        <div class="logo-text">
                            <h6>Wilrop Colombia Travel</h6>
                        </div>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">Inicio</a></li>
                    <li><a href="dominicana.html" class="nav-link">República Dominicana</a></li>
                    <li><a href="colombia.html" class="nav-link">Colombia</a></li>
                    <li><a href="index.php#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="products.html" class="nav-link">Productos</a></li>
                    <li><a href="admin.php" class="nav-link">Admin</a></li>
                    <li><a href="index.php#contacto" class="nav-link">Contacto</a></li>
                    <li><a href="login.php" class="nav-link login-btn">Iniciar Sesión</a></li>
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

    <!-- Register Section -->
    <section class="register-section">
        <div class="container">
            <div class="register-container">
                <div class="register-content">
                    <div class="register-header">
                        <h2>Crear Cuenta</h2>
                        <p>Únete a Wilrop Colombia Travel y descubre experiencias únicas</p>
                    </div>
                    
                    <form class="register-form" method="POST" action="">
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
                        
                        <div class="login-footer" style="text-align: center; margin-top: 2rem;">
                            <p>¿Ya tienes una cuenta? <a href="login.php" class="register-link">Inicia sesión aquí</a></p>
                        </div>
                    </form>
                </div>
                
                <div class="register-image">
                    <div class="register-bg">
                        <h3>¡Únete a la aventura!</h3>
                        <p>Con Wilrop Colombia Travel podrás:</p>
                        <div class="register-features">
                            <div class="feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Gestionar tus reservas</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Acceder a ofertas exclusivas</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Recibir notificaciones de viajes</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Historial completo de viajes</span>
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
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="products.html">Productos</a></li>
                        <li><a href="admin.php">Admin</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
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

    <script src="scripts.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
