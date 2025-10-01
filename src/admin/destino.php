<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['active'])) {
    header('Location: login.php');
    exit();
}

// Manejo de creación de destino
$mensaje = '';
$errores = [];
$paises_validos = ['republica dominicana', 'colombia'];

$pais = isset($_POST['pais']) ? trim($_POST['pais']) : '';
$ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : (isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '');
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pais === '' || !in_array(strtolower($pais), $paises_validos, true)) {
        $errores[] = 'El país es obligatorio y debe ser República Dominicana o Colombia.';
    }
    if ($ciudad === '') {
        $errores[] = 'La ciudad es obligatoria.';
    } elseif (mb_strlen($ciudad) > 100) {
        $errores[] = 'La ciudad no debe exceder 100 caracteres.';
    }

    if (empty($errores)) {
        $sql = 'INSERT INTO destinos (pais, ciudad, descripcion) VALUES (?, ?, ?)';
        $stmt = mysqli_prepare($conexion, $sql);
        if ($stmt) {
            $pais_val = strtolower($pais);
            mysqli_stmt_bind_param($stmt, 'sss', $pais_val, $ciudad, $descripcion);
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = 'Destino creado correctamente.';
                // limpiar formulario
                $pais = '';
                $ciudad = '';
                $descripcion = '';
            } else {
                $errores[] = 'Error al crear el destino: ' . htmlspecialchars(mysqli_error($conexion));
            }
            mysqli_stmt_close($stmt);
        } else {
            $errores[] = 'No se pudo preparar la consulta.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Wilrop Colombia Travel</title>
    <meta name="description" content="Panel de administración para crear productos turísticos - Wilrop Colombia Travel">
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <!-- Header -->
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
                    <li><a href="admin.php" class="nav-link active">Admin</a></li>
                    <li><a href="../../index.php#contacto" class="nav-link">Contacto</a></li>
                    <li><a href="../../src/admin/login.php" class="nav-link login-btn">Iniciar Sesión</a></li>
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
    <!-- Admin Section -->
    <section class="admin-section">
        <div class="container">
            <div class="section-header">
                <h2>Panel de Administración</h2>
                <p>Gestiona los destinos que ofrecerás en tu sitio</p>
            </div>
            <div class="admin-content">
                <!-- Formulario de Nuevo Destino -->
                <div class="form-container admin-form-container">
                    <h3><i class="fas fa-plus-circle"></i> Agregar Nuevo destino</h3>
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul style="margin-left:1rem;">
                                <?php foreach ($errores as $e): ?>
                                    <li><?php echo htmlspecialchars($e); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                    <form id="destinoForm" class="admin-form" method="POST" action="destino.php">
                        <div class="form-row">
                            <div class="form-group" style="min-width:260px;">
                                <label for="pais">País <span style="color:#e74c3c">*</span></label>
                                <select id="pais" name="pais" required>
                                    <option value="">Selecciona un país</option>
                                    <option value="republica dominicana" <?php echo ($pais==='republica dominicana'?'selected':''); ?>>República Dominicana</option>
                                    <option value="colombia" <?php echo ($pais==='colombia'?'selected':''); ?>>Colombia</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad<span style="color:#e74c3c">*</span></label>
                                <input type="text" id="ciudad" name="ciudad" maxlength="100" required placeholder="Ej: Santo Domingo, Medellín" value="<?php echo htmlspecialchars($ciudad); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcionDestino">Descripción</label>
                            <textarea id="descripcionDestino" name="descripcion" rows="4" placeholder="Describe detalladamente el destino..."><?php echo htmlspecialchars($descripcion); ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar destino
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Limpiar Formulario
                            </button>
                        </div>
                    </form>
                </div>
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
