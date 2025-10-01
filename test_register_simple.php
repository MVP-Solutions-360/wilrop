<?php
// Prueba simple del registro
include 'conexion.php';

echo "<h2>Prueba de Registro - Puerto 8080</h2>";

// Simular un POST
$_POST['nombre'] = 'Test';
$_POST['apellido'] = 'Usuario';
$_POST['email'] = 'test8080@wilropcolombia.com';
$_POST['password'] = '123456';
$_POST['confirm_password'] = '123456';
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<h3>Procesando registro de prueba...</h3>";

$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Verificar si el correo ya existe
$query = "SELECT id FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: orange;'>⚠️ Este correo ya existe</p>";
} else {
    // Insertar nuevo usuario
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellido, $email, $password_hash);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>✅ Usuario registrado exitosamente</p>";
        echo "<p>ID: " . mysqli_insert_id($conexion) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . mysqli_error($conexion) . "</p>";
    }
}

mysqli_stmt_close($stmt);

// Mostrar usuarios
echo "<h3>Usuarios en la BD:</h3>";
$query = "SELECT id, nombre, apellido, email FROM usuarios ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conexion, $query);

if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

mysqli_close($conexion);

echo "<h3>Enlaces:</h3>";
echo "<p><a href='register.php'>Formulario de registro</a></p>";
echo "<p><a href='login.php'>Formulario de login</a></p>";
?>
