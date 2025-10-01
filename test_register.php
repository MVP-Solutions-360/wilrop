<?php
// Script de prueba para el registro
include 'conexion.php';

echo "<h2>Prueba de Registro - Wilrop Colombia Travel</h2>";

// Simular datos de POST
$_POST['nombre'] = 'Usuario';
$_POST['apellido'] = 'Prueba';
$_POST['email'] = 'prueba@wilropcolombia.com';
$_POST['password'] = '123456';
$_POST['confirm_password'] = '123456';
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<h3>Datos de prueba:</h3>";
echo "<p>Nombre: " . $_POST['nombre'] . "</p>";
echo "<p>Apellido: " . $_POST['apellido'] . "</p>";
echo "<p>Email: " . $_POST['email'] . "</p>";

// Procesar el registro
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

echo "<h3>Procesando registro...</h3>";

// Validaciones
if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
    echo "<p style='color: red;'>❌ Error: Todos los campos son obligatorios</p>";
    exit();
}

if ($password !== $confirm_password) {
    echo "<p style='color: red;'>❌ Error: Las contraseñas no coinciden</p>";
    exit();
}

if (strlen($password) < 6) {
    echo "<p style='color: red;'>❌ Error: La contraseña debe tener al menos 6 caracteres</p>";
    exit();
}

// Verificar si el correo ya existe
$query = "SELECT id FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: orange;'>⚠️ Este correo electrónico ya está registrado</p>";
} else {
    // Insertar nuevo usuario
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellido, $email, $password_hash);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>✅ Usuario registrado exitosamente</p>";
        echo "<p>ID del usuario: " . mysqli_insert_id($conexion) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Error al registrar el usuario: " . mysqli_error($conexion) . "</p>";
    }
}

mysqli_stmt_close($stmt);

// Verificar usuarios en la BD
echo "<h3>Usuarios en la base de datos:</h3>";
$query = "SELECT id, nombre, apellido, email, creado_en FROM usuarios ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conexion, $query);

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
    echo "<p style='color: red;'>❌ No hay usuarios en la base de datos</p>";
}

mysqli_close($conexion);

echo "<h3>Enlaces:</h3>";
echo "<p><a href='register.php'>Ir al formulario de registro</a></p>";
echo "<p><a href='login.php'>Ir al login</a></p>";
?>
