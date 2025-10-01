<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['active'])) {
    header('Location: login.php');
    exit();
}
// Lógica de creación de productos en este mismo archivo
$mensaje = '';
$errores = [];

// Detectar columnas adicionales en productos
$productos_tiene_destino_id = false;
$productos_tiene_incluye = false;
$productos_tiene_no_incluye = false;
$res_cols = @mysqli_query($conexion, "SHOW COLUMNS FROM productos");
if ($res_cols) {
    while ($c = mysqli_fetch_assoc($res_cols)) {
        if (strcasecmp($c['Field'], 'destino_id') === 0) $productos_tiene_destino_id = true;
        if (strcasecmp($c['Field'], 'incluye') === 0) $productos_tiene_incluye = true;
        if (strcasecmp($c['Field'], 'no_incluye') === 0) $productos_tiene_no_incluye = true;
    }
    @mysqli_free_result($res_cols);
}

// Detectar columna de nombre de destino
$destino_nombre_col = null;
$has_nombre = @mysqli_query($conexion, "SHOW COLUMNS FROM destinos LIKE 'nombre'");
if ($has_nombre && mysqli_num_rows($has_nombre) > 0) {
    $destino_nombre_col = 'nombre';
}
@mysqli_free_result($has_nombre);
if ($destino_nombre_col === null) {
    $has_ciudad = @mysqli_query($conexion, "SHOW COLUMNS FROM destinos LIKE 'ciudad'");
    if ($has_ciudad && mysqli_num_rows($has_ciudad) > 0) {
        $destino_nombre_col = 'ciudad';
    }
    @mysqli_free_result($has_ciudad);
}

// Cargar destinos para el select, si aplica
$destinos = [];
if ($productos_tiene_destino_id && $destino_nombre_col !== null) {
    $sqlDest = "SELECT id, pais, `{$destino_nombre_col}` AS nombre FROM destinos ORDER BY pais, `{$destino_nombre_col}`";
    $resDest = @mysqli_query($conexion, $sqlDest);
    if ($resDest) {
        while ($d = mysqli_fetch_assoc($resDest)) { $destinos[] = $d; }
        @mysqli_free_result($resDest);
    }
}

// Manejar POST del formulario de producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['precio'], $_POST['pais'])) {
    $pais = trim((string)$_POST['pais']);
    $nombre = trim((string)$_POST['nombre']);
    $descripcion = isset($_POST['descripcion']) ? trim((string)$_POST['descripcion']) : '';
    $precio_raw = isset($_POST['precio']) ? (string)$_POST['precio'] : '';
    $disponible = isset($_POST['disponible']) ? 1 : 1;
    $incluye = isset($_POST['incluye']) ? trim((string)$_POST['incluye']) : '';
    $no_incluye = isset($_POST['no_incluye']) ? trim((string)$_POST['no_incluye']) : '';
    $destino_id = $productos_tiene_destino_id ? (isset($_POST['destino_id']) ? (int)$_POST['destino_id'] : 0) : null;

    if ($pais === '') $errores[] = 'Seleccione un país';
    if ($nombre === '' || mb_strlen($nombre) < 3 || mb_strlen($nombre) > 100) $errores[] = 'El nombre del producto debe tener entre 3 y 100 caracteres';
    if ($precio_raw === '' || !is_numeric($precio_raw) || (float)$precio_raw <= 0) $errores[] = 'El precio debe ser mayor a 0';
    if ($productos_tiene_destino_id) {
        if ($destino_id <= 0) {
            $errores[] = 'Seleccione un destino válido';
        } else if ($destino_nombre_col !== null) {
            $stmt = mysqli_prepare($conexion, "SELECT pais FROM destinos WHERE id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $destino_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                if (!$row) {
                    $errores[] = 'El destino seleccionado no existe';
                } elseif (!empty($row['pais']) && $row['pais'] !== $pais) {
                    $errores[] = 'El destino no coincide con el país seleccionado';
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    if (empty($errores)) {
        $precio = number_format((float)$precio_raw, 2, '.', '');
        // Construir SQL dinámico según columnas disponibles
        $cols = ['pais','nombre','descripcion','precio','disponible'];
        $placeholders = ['?','?','?','?','?'];
        $types = 'sssdi';
        $vals = [$pais, $nombre, $descripcion, $precio, $disponible];
        if ($productos_tiene_incluye) { $cols[]='incluye'; $placeholders[]='?'; $types.='s'; $vals[]=$incluye; }
        if ($productos_tiene_no_incluye) { $cols[]='no_incluye'; $placeholders[]='?'; $types.='s'; $vals[]=$no_incluye; }
        if ($productos_tiene_destino_id) { $cols[]='destino_id'; $placeholders[]='?'; $types.='i'; $vals[]=$destino_id; }

        $sql = 'INSERT INTO productos ('.implode(',', $cols).') VALUES ('.implode(',', $placeholders).')';
        $stmt = mysqli_prepare($conexion, $sql);
        if ($stmt) {
            $bind_params = [];
            $bind_params[] = $stmt;
            $bind_params[] = $types;
            for ($i=0; $i<count($vals); $i++) { $bind_params[] = &$vals[$i]; }
            call_user_func_array('mysqli_stmt_bind_param', $bind_params);
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = 'Producto creado exitosamente.';
                $_POST = [];
            } else {
                $errores[] = 'Error al crear el producto: ' . htmlspecialchars(mysqli_error($conexion));
            }
            mysqli_stmt_close($stmt);
        } else {
            $errores[] = 'No se pudo preparar la consulta';
        }
    }
}
// Cargar y manejar creación de productos en este mismo archivo
// include removed
// Cargar países disponibles desde la tabla destinos (valores distintos)
$paises_disponibles = [];
$res_paises = @mysqli_query($conexion, "SELECT DISTINCT pais FROM destinos ORDER BY pais");
if ($res_paises) {
    while ($row = mysqli_fetch_assoc($res_paises)) {
        if (!empty($row['pais'])) {
            $paises_disponibles[] = $row['pais'];
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
                <p>Gestiona tus productos turísticos de manera sencilla</p>
            </div>
            <div class="admin-content">
                <!-- Formulario de Nuevo Producto -->
                <div class="form-container admin-form-container">
                    <h3><i class="fas fa-plus-circle"></i> Agregar Nuevo Producto</h3>
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul style="margin-left:1rem;">
                                <?php foreach ($errores as $e): ?>
                                    <li><?php echo htmlspecialchars($e); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php if (isset($productos_tiene_destino_id) && $productos_tiene_destino_id): ?>
                        <div class="form-row">
                            <div class="form-group" style="min-width:260px;">
                                <label for="destino_id">Destino (ciudad) <span style="color:#e74c3c">*</span></label>
                                <select id="destino_id" name="destino_id" required>
                                    <option value="">Selecciona un destino</option>
                                    <?php if (!empty($destinos)): foreach ($destinos as $d): ?>
                                        <?php $label = ($d['pais'] ?? '') . ' - ' . ($d['nombre'] ?? ''); ?>
                                        <option value="<?php echo (int)$d['id']; ?>" <?php echo (isset($_POST['destino_id']) && (int)$_POST['destino_id'] === (int)$d['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                    <form id="productoForm" class="admin-form" method="POST" action="admin.php">
                        <div class="form-row">
                            <div class="form-group" style="min-width:260px;">
                                <label for="paisProducto">País <span style="color:#e74c3c">*</span></label>
                                <select id="paisProducto" name="pais" required>
                                    <option value="">Selecciona un país</option>
                                    <?php if (!empty($paises_disponibles)): ?>
                                        <?php foreach ($paises_disponibles as $pais): ?>
                                            <?php
                                                $value = strtolower($pais);
                                                $label = ($value === 'republica dominicana') ? 'República Dominicana' : (($value === 'colombia') ? 'Colombia' : ucfirst($value));
                                            ?>
                                            <option value="<?php echo htmlspecialchars($value); ?>" <?php echo (isset($_POST['pais']) && $_POST['pais'] === $value) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="republica dominicana">República Dominicana</option>
                                        <option value="colombia">Colombia</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombreProducto">Nombre del Producto <span style="color:#e74c3c">*</span></label>
                                <input type="text" id="nombreProducto" name="nombre" maxlength="100" required placeholder="Ej: Paquete Punta Cana 7 días">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="precioProducto">Precio (USD) <span style="color:#e74c3c">*</span></label>
                                <input type="number" id="precioProducto" name="precio" min="0" step="0.01" required placeholder="0.00" value="<?php echo isset($_POST['precio']) ? htmlspecialchars($_POST['precio']) : ''; ?>">
                            </div>
                            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                                <label for="disponibleProducto" style="margin:0;">Disponible</label>
                                <input type="checkbox" id="disponibleProducto" name="disponible" checked>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcionProducto">Descripción</label>
                            <textarea id="descripcionProducto" name="descripcion" rows="4" placeholder="Describe detalladamente el producto..."><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="incluye">Incluye</label>
                            <textarea id="incluye" name="incluye" rows="4" placeholder="Describe detalladamente los servicios incluidos..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="no_incluye">No incluye</label>
                            <textarea id="no_incluye" name="no_incluye" rows="4" placeholder="Describe detalladamente los servicios no incluidos..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Producto
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Limpiar Formulario
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Panel de Acciones Rápidas -->
                <div class="admin-panel">
                    <h3><i class="fas fa-tools"></i> Acciones Rápidas</h3>
                    <div class="quick-actions">
                        <a href="../../src/producto/products.php" class="action-card">
                            <i class="fas fa-eye"></i>
                            <h4>Ver Productos</h4>
                            <p>Visualizar y gestionar productos existentes</p>
                        </a>
                        <a href="../../src/admin/destino.php" class="action-card">
                            <i class="fa-solid fa-earth-americas"></i>
                            <h4>Agregar destino</h4>
                            <p>Agrega un destino que ofrecerás en tu web</p>
                        </a>
                        <button onclick="exportarProductos()" class="action-card">
                            <i class="fas fa-download"></i>
                            <h4>Exportar</h4>
                            <p>Descargar lista de productos</p>
                        </button>
                        <label for="importarArchivo" class="action-card">
                            <i class="fas fa-upload"></i>
                            <h4>Importar</h4>
                            <p>Cargar productos desde archivo</p>
                            <input type="file" id="importarArchivo" accept=".json" style="display: none;" onchange="importarProductos(event)">
                        </label>
                        <button onclick="limpiarProductos()" class="action-card danger">
                            <i class="fas fa-trash"></i>
                            <h4>Limpiar Todo</h4>
                            <p>Eliminar todos los productos</p>
                        </button>
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



