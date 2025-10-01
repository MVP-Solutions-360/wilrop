<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['active']) || $_SESSION['idUser'] != 1) {
    header('Location: login.php');
    exit();
}

$alert = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($usuario)) {
        $alert = '<div class="alert alert-danger">Todos los campos son obligatorios</div>';
    } else {
        if (empty($id)) {
            // Crear nuevo usuario
            if (empty($password)) {
                $alert = '<div class="alert alert-danger">La contraseña es requerida para nuevos usuarios</div>';
            } else {
                // Verificar si el correo ya existe
                $query = "SELECT idusuario FROM usuario WHERE correo = ?";
                $stmt = mysqli_prepare($conexion, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    $alert = '<div class="alert alert-danger">Este correo electrónico ya está registrado</div>';
                } else {
                    // Verificar si el usuario ya existe
                    $query = "SELECT idusuario FROM usuario WHERE usuario = ?";
                    $stmt = mysqli_prepare($conexion, $query);
                    mysqli_stmt_bind_param($stmt, "s", $usuario);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if (mysqli_num_rows($result) > 0) {
                        $alert = '<div class="alert alert-danger">Este nombre de usuario ya está en uso</div>';
                    } else {
                        // Insertar nuevo usuario
                        $password_hash = md5($password);
                        $query = "INSERT INTO usuario (nombre, correo, usuario, clave, activo) VALUES (?, ?, ?, ?, 1)";
                        $stmt = mysqli_prepare($conexion, $query);
                        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $email, $usuario, $password_hash);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            $alert = '<div class="alert alert-success">Usuario creado exitosamente</div>';
                        } else {
                            $alert = '<div class="alert alert-danger">Error al crear el usuario</div>';
                        }
                    }
                }
            }
        } else {
            // Actualizar usuario existente
            if (!empty($password)) {
                $password_hash = md5($password);
                $query = "UPDATE usuario SET nombre = ?, correo = ?, usuario = ?, clave = ? WHERE idusuario = ?";
                $stmt = mysqli_prepare($conexion, $query);
                mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $email, $usuario, $password_hash, $id);
            } else {
                $query = "UPDATE usuario SET nombre = ?, correo = ?, usuario = ? WHERE idusuario = ?";
                $stmt = mysqli_prepare($conexion, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $nombre, $email, $usuario, $id);
            }
            
            if (mysqli_stmt_execute($stmt)) {
                $alert = '<div class="alert alert-success">Usuario actualizado exitosamente</div>';
            } else {
                $alert = '<div class="alert alert-danger">Error al actualizar el usuario</div>';
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Obtener usuarios
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY idusuario DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Wilrop Colombia Travel</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
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
            margin-bottom: 2rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
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
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .user-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .user-actions {
                flex-direction: column;
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
            <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
            <p>Crear, editar y gestionar usuarios del sistema</p>
        </div>

        <div class="form-container">
            <h3><i class="fas fa-user-plus"></i> <?php echo isset($_GET['edit']) ? 'Editar Usuario' : 'Crear Nuevo Usuario'; ?></h3>
            
            <?php echo $alert; ?>
            
            <form method="POST" action="">
                <input type="hidden" id="id" name="id" value="<?php echo $_GET['edit'] ?? ''; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['nombre'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['email'] ?? '') : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="usuario">Nombre de Usuario</label>
                        <input type="text" id="usuario" name="usuario" required 
                               value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['usuario'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" 
                               placeholder="<?php echo isset($_GET['edit']) ? 'Dejar vacío para mantener la actual' : 'Mínimo 6 caracteres'; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo isset($_GET['edit']) ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
                    </button>
                    <a href="manage-users.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h3><i class="fas fa-list"></i> Lista de Usuarios</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($usuarios)): ?>
                        <tr>
                            <td><?php echo $user['idusuario']; ?></td>
                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['correo']); ?></td>
                            <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                            <td>
                                <div class="user-actions">
                                    <a href="manage-users.php?edit=<?php echo $user['idusuario']; ?>&nombre=<?php echo urlencode($user['nombre']); ?>&email=<?php echo urlencode($user['correo']); ?>&usuario=<?php echo urlencode($user['usuario']); ?>" 
                                       class="btn btn-success btn-small">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="assign-roles.php?id=<?php echo $user['idusuario']; ?>" 
                                       class="btn btn-warning btn-small">
                                        <i class="fas fa-key"></i> Roles
                                    </a>
                                    <?php if ($user['idusuario'] != 1): ?>
                                        <a href="delete-user.php?id=<?php echo $user['idusuario']; ?>" 
                                           class="btn btn-danger btn-small" 
                                           onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
