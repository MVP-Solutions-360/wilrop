<?php
// Configuración de permisos del sistema
// Este archivo define los permisos disponibles y sus descripciones

$permisos_sistema = [
    1 => [
        'id' => 1,
        'nombre' => 'configuración',
        'descripcion' => 'Acceso a configuraciones del sistema',
        'icono' => 'cog',
        'modulo' => 'admin'
    ],
    2 => [
        'id' => 2,
        'nombre' => 'usuarios',
        'descripcion' => 'Gestionar usuarios y cuentas',
        'icono' => 'users',
        'modulo' => 'admin'
    ],
    3 => [
        'id' => 3,
        'nombre' => 'clientes',
        'descripcion' => 'Administrar información de clientes',
        'icono' => 'user-friends',
        'modulo' => 'admin'
    ],
    4 => [
        'id' => 4,
        'nombre' => 'productos',
        'descripcion' => 'Gestionar catálogo de productos',
        'icono' => 'box',
        'modulo' => 'admin'
    ],
    5 => [
        'id' => 5,
        'nombre' => 'ventas',
        'descripcion' => 'Ver historial de ventas',
        'icono' => 'cash-register',
        'modulo' => 'admin'
    ],
    6 => [
        'id' => 6,
        'nombre' => 'nueva_venta',
        'descripcion' => 'Crear nuevas ventas',
        'icono' => 'plus-circle',
        'modulo' => 'admin'
    ]
];

// Función para verificar si un usuario tiene un permiso específico
function tienePermiso($conexion, $usuario_id, $permiso_nombre) {
    // El administrador (ID = 1) tiene todos los permisos
    if ($usuario_id == 1) {
        return true;
    }
    
    $query = "SELECT p.*, d.* FROM permisos p 
              INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
              WHERE d.id_usuario = ? AND p.nombre = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "is", $usuario_id, $permiso_nombre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $existe = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return !empty($existe);
}

// Función para obtener todos los permisos de un usuario
function obtenerPermisosUsuario($conexion, $usuario_id) {
    $query = "SELECT p.* FROM permisos p 
              INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
              WHERE d.id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $permisos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $permisos[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    return $permisos;
}

// Función para verificar si el usuario es administrador
function esAdministrador($usuario_id) {
    return $usuario_id == 1;
}

// Función para requerir un permiso específico
function requerirPermiso($conexion, $usuario_id, $permiso_nombre) {
    if (!esAdministrador($usuario_id) && !tienePermiso($conexion, $usuario_id, $permiso_nombre)) {
        header('Location: permisos.php');
        exit();
    }
}
?>
