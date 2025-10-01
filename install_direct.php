<?php
// Instalador directo para tablas de permisos
include 'conexion.php';

echo "<h2>Instalación Directa de Tablas de Permisos - Wilrop Colombia Travel</h2>";

// Verificar conexión
if (mysqli_connect_errno()) {
    echo "<p style='color: red;'>Error: No se pudo conectar a la base de datos.</p>";
    exit();
}

echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa.</p>";

$queries = [
    // Crear tabla de permisos
    "CREATE TABLE IF NOT EXISTS permisos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(30) NOT NULL UNIQUE,
        descripcion VARCHAR(100) DEFAULT NULL,
        modulo VARCHAR(50) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Crear tabla de detalle de permisos
    "CREATE TABLE IF NOT EXISTS detalle_permisos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_permiso INT NOT NULL,
        id_usuario INT NOT NULL,
        fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_permiso) REFERENCES permisos(id) ON DELETE CASCADE,
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Insertar permisos por defecto
    "INSERT IGNORE INTO permisos (nombre, descripcion, modulo) VALUES
    ('configuracion', 'Acceso a configuraciones del sistema', 'admin'),
    ('usuarios', 'Gestionar usuarios y cuentas', 'admin'),
    ('clientes', 'Administrar información de clientes', 'admin'),
    ('productos', 'Gestionar catálogo de productos', 'admin'),
    ('ventas', 'Ver historial de ventas', 'admin'),
    ('nueva_venta', 'Crear nuevas ventas', 'admin')",
    
    // Insertar usuario administrador por defecto si no existe
    "INSERT IGNORE INTO usuarios (id, nombre, apellido, email, password) VALUES
    (1, 'Administrador', 'Wilrop', 'admin@wilropcolombia.com', '21232f297a57a5a743894a0e4a801fc3')",
    
    // Asignar todos los permisos al administrador
    "INSERT IGNORE INTO detalle_permisos (id_usuario, id_permiso) VALUES
    (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6)"
];

$success_count = 0;
$error_count = 0;

echo "<h3>Ejecutando consultas SQL...</h3>";

foreach ($queries as $index => $query) {
    echo "<p>Ejecutando consulta " . ($index + 1) . "...</p>";
    
    if (mysqli_query($conexion, $query)) {
        $success_count++;
        echo "<p style='color: green;'>✓ Consulta " . ($index + 1) . " ejecutada correctamente</p>";
    } else {
        $error_count++;
        echo "<p style='color: red;'>✗ Error en consulta " . ($index + 1) . ": " . mysqli_error($conexion) . "</p>";
    }
}

echo "<h3>Resumen de la instalación:</h3>";
echo "<p style='color: green;'>Consultas exitosas: $success_count</p>";
echo "<p style='color: red;'>Consultas con error: $error_count</p>";

if ($error_count == 0) {
    echo "<h3 style='color: green;'>¡Instalación completada exitosamente!</h3>";
    echo "<p>Las tablas de permisos han sido agregadas a tu base de datos wilrop.</p>";
    echo "<p><strong>Usuario administrador por defecto:</strong></p>";
    echo "<ul>";
    echo "<li>Email: admin@wilropcolombia.com</li>";
    echo "<li>Contraseña: admin</li>";
    echo "</ul>";
    echo "<p><a href='login.php'>Ir al login</a> | <a href='register_adapted.php'>Ir al registro</a></p>";
} else {
    echo "<h3 style='color: red;'>La instalación tuvo algunos errores.</h3>";
    echo "<p>Revisa los errores anteriores y vuelve a intentar.</p>";
}

mysqli_close($conexion);
?>
