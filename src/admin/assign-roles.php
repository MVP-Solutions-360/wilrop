<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['active']) || $_SESSION['idUser'] != 1) {
    header('Location: login.php');
    exit();
}

$user_id = $_GET['id'] ?? '';
$alert = '';

if (empty($user_id)) {
    header('Location: manage-users.php');
    exit();
}

// Obtener información del usuario
$user_query = "SELECT * FROM usuario WHERE idusuario = ?";
$stmt = mysqli_prepare($conexion, $user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$user) {
    header('Location: manage-users.php');
    exit();
}

// Obtener permisos disponibles
$permisos = mysqli_query($conexion, "SELECT * FROM permisos ORDER BY id");

// Obtener permisos asignados al usuario
$permisos_asignados = mysqli_query($conexion, "SELECT id_permiso FROM detalle_permisos WHERE id_usuario = $user_id");
$permisos_usuario = array();
while ($permiso = mysqli_fetch_assoc($permisos_asignados)) {
    $permisos_usuario[] = $permiso['id_permiso'];
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $permisos_seleccionados = $_POST['permisos'] ?? array();
    
    // Eliminar permisos actuales
    mysqli_query($conexion, "DELETE FROM detalle_permisos WHERE id_usuario = $user_id");
    
    // Insertar nuevos permisos
    if (!empty($permisos_seleccionados)) {
        foreach ($permisos_seleccionados as $permiso_id) {
            $query = "INSERT INTO detalle_permisos (id_usuario, id_permiso) VALUES (?, ?)";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $permiso_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    
    $alert = '<div class="alert alert-success">Permisos actualizados exitosamente</div>';
    
    // Actualizar lista de permisos del usuario
    $permisos_asignados = mysqli_query($conexion, "SELECT id_permiso FROM detalle_permisos WHERE id_usuario = $user_id");
    $permisos_usuario = array();
    while ($permiso = mysqli_fetch_assoc($permisos_asignados)) {
        $permisos_usuario[] = $permiso['id_permiso'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Roles - Wilrop Colombia Travel</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .user-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }
        .user-info h4 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }
        .user-info p {
            margin: 0;
            color: #666;
        }
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .permission-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .permission-item:hover {
            border-color: #667eea;
            background: #f0f2ff;
        }
        .permission-item input[type="checkbox"] {
            margin-right: 0.5rem;
            transform: scale(1.2);
        }
        .permission-item label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
            color: #333;
        }
        .permission-item.checked {
            background: #e8f5e8;
            border-color: #28a745;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5a6fd8;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .permission-description {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
        .permission-icon {
            margin-right: 0.5rem;
            color: #667eea;
        }
        @media (max-width: 768px) {
            .permissions-grid {
                grid-template-columns: 1fr;
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
                    <li><a href="admin.php" class="nav-link active">Admin</a></li>
                    <li><a href="logout.php" class="nav-link login-btn">Cerrar Sesión</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-key"></i> Asignar Roles y Permisos</h1>
            <p>Gestiona los permisos de acceso para los usuarios del sistema</p>
        </div>

        <div class="form-container">
            <div class="user-info">
                <h4><i class="fas fa-user"></i> Usuario: <?php echo htmlspecialchars($user['nombre']); ?></h4>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['correo']); ?> | 
                   <strong>Usuario:</strong> <?php echo htmlspecialchars($user['usuario']); ?></p>
            </div>

            <?php echo $alert; ?>

            <form method="POST" action="">
                <h3><i class="fas fa-shield-alt"></i> Permisos Disponibles</h3>
                <p>Selecciona los permisos que deseas asignar a este usuario:</p>
                
                <div class="permissions-grid">
                    <?php while ($permiso = mysqli_fetch_assoc($permisos)): ?>
                        <?php $is_checked = in_array($permiso['id'], $permisos_usuario); ?>
                        <div class="permission-item <?php echo $is_checked ? 'checked' : ''; ?>">
                            <label>
                                <input type="checkbox" name="permisos[]" value="<?php echo $permiso['id']; ?>" 
                                       <?php echo $is_checked ? 'checked' : ''; ?> 
                                       onchange="togglePermission(this)">
                                <i class="fas fa-<?php 
                                    $icons = [
                                        'configuración' => 'cog',
                                        'usuarios' => 'users',
                                        'clientes' => 'user-friends',
                                        'productos' => 'box',
                                        'ventas' => 'cash-register',
                                        'nueva_venta' => 'plus-circle'
                                    ];
                                    echo $icons[$permiso['nombre']] ?? 'shield-alt';
                                ?> permission-icon"></i>
                                <?php echo ucfirst(str_replace('_', ' ', $permiso['nombre'])); ?>
                            </label>
                            <div class="permission-description">
                                <?php
                                $descriptions = [
                                    'configuración' => 'Acceso a configuraciones del sistema',
                                    'usuarios' => 'Gestionar usuarios y cuentas',
                                    'clientes' => 'Administrar información de clientes',
                                    'productos' => 'Gestionar catálogo de productos',
                                    'ventas' => 'Ver historial de ventas',
                                    'nueva_venta' => 'Crear nuevas ventas'
                                ];
                                echo $descriptions[$permiso['nombre']] ?? 'Permiso del sistema';
                                ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Permisos
                    </button>
                    <a href="manage-users.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Usuarios
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePermission(checkbox) {
            const permissionItem = checkbox.closest('.permission-item');
            if (checkbox.checked) {
                permissionItem.classList.add('checked');
            } else {
                permissionItem.classList.remove('checked');
            }
        }
    </script>
    <script src="scripts.js"></script>
</body>
</html>
