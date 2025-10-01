<?php
// Script de prueba para verificar la conexión y funcionalidad
include 'conexion.php';

echo "<h2>Prueba de Conexión - Wilrop Colombia Travel</h2>";

// Verificar conexión
if (mysqli_connect_errno()) {
    echo "<p style='color: red;'>❌ Error: No se pudo conectar a la base de datos.</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
    exit();
}

echo "<p style='color: green;'>✅ Conexión a la base de datos exitosa.</p>";

// Verificar si las tablas existen
$tables = ['usuarios', 'permisos', 'detalle_permisos'];
foreach ($tables as $table) {
    $result = mysqli_query($conexion, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✅ Tabla '$table' existe</p>";
    } else {
        echo "<p style='color: red;'>❌ Tabla '$table' NO existe</p>";
    }
}

// Verificar estructura de la tabla usuarios
echo "<h3>Estructura de la tabla usuarios:</h3>";
$result = mysqli_query($conexion, "DESCRIBE usuarios");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Por defecto</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error al obtener estructura de la tabla usuarios</p>";
}

// Verificar usuarios existentes
echo "<h3>Usuarios existentes:</h3>";
$result = mysqli_query($conexion, "SELECT id, nombre, apellido, email, creado_en FROM usuarios ORDER BY id");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Fecha Registro</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . $row['creado_en'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No hay usuarios en la base de datos</p>";
}

// Probar inserción de un usuario de prueba
echo "<h3>Prueba de inserción:</h3>";
$test_email = 'test@wilropcolombia.com';
$test_nombre = 'Usuario';
$test_apellido = 'Prueba';
$test_password = password_hash('123456', PASSWORD_DEFAULT);

// Verificar si el usuario de prueba ya existe
$check_query = "SELECT id FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $check_query);
mysqli_stmt_bind_param($stmt, "s", $test_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: orange;'>⚠️ El usuario de prueba ya existe</p>";
} else {
    // Insertar usuario de prueba
    $insert_query = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssss", $test_nombre, $test_apellido, $test_email, $test_password);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>✅ Usuario de prueba insertado correctamente</p>";
    } else {
        echo "<p style='color: red;'>❌ Error al insertar usuario de prueba: " . mysqli_error($conexion) . "</p>";
    }
}

mysqli_close($conexion);

echo "<h3>Enlaces de prueba:</h3>";
echo "<p><a href='register.php'>Ir al registro</a></p>";
echo "<p><a href='login.php'>Ir al login</a></p>";
echo "<p><a href='admin.php'>Ir al admin</a></p>";
?>
