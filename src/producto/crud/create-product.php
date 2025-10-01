<?php
// create-product.php - Formulario para crear nuevos productos y guardarlos en la base de datos
include '../../admin/conexion.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $destino = $_POST['destino'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $duracion = $_POST['duracion'];
    $capacidad = $_POST['capacidad'];
    $fechaCreacion = date('Y-m-d');

    $query = "INSERT INTO productos (nombre, descripcion, destino, tipo, precio, duracion, capacidad, fechaCreacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'ssssddis', $nombre, $descripcion, $destino, $tipo, $precio, $duracion, $capacidad, $fechaCreacion);
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = 'Producto creado exitosamente.';
    } else {
        $mensaje = 'Error al crear el producto.';
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - Wilrop Colombia Travel</title>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
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
                    <li><a href="/src/producto/products.php" class="nav-link">Productos</a></li>
                    <li><a href="/src/admin/admin.php" class="nav-link">Admin</a></li>
                </ul>
            </div>
        </nav>
        <div class="mobile-menu-overlay"></div>
    </header>
    <section class="form-section">
        <div class="container">
            <div class="section-header">
                <h2>Crear Nuevo Producto</h2>
                <p>Agrega un nuevo producto turístico al catálogo</p>
            </div>
            <?php if ($mensaje): ?>
                <div class="alert alert-info"> <?php echo $mensaje; ?> </div>
            <?php endif; ?>
            <form class="product-form" method="POST" action="">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="form-group">
                    <label for="destino">Destino</label>
                    <select id="destino" name="destino" required>
                        <option value="">Selecciona un destino</option>
                        <option value="republica-dominicana">República Dominicana</option>
                        <option value="antioquia">Antioquia, Colombia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecciona un tipo</option>
                        <option value="paquete">Paquete Turístico</option>
                        <option value="vuelo">Vuelo</option>
                        <option value="hotel">Hospedaje</option>
                        <option value="tour">Tour/Excursión</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (USD)</label>
                    <input type="number" id="precio" name="precio" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="duracion">Duración (días)</label>
                    <input type="number" id="duracion" name="duracion" min="1" required>
                </div>
                <div class="form-group">
                    <label for="capacidad">Capacidad (personas)</label>
                    <input type="number" id="capacidad" name="capacidad" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-plus"></i> Crear Producto
                </button>
            </form>
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
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="/index.php">Inicio</a></li>
                        <li><a href="/src/producto/products.php">Productos</a></li>
                        <li><a href="/src/admin/admin.php">Admin</a></li>
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
