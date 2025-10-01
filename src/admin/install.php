<?php
// Script de instalación para el sistema de usuarios Wilrop
// Este archivo crea las tablas necesarias en la base de datos

include 'conexion.php';

echo "<h2>Instalación del Sistema de Usuarios - Wilrop Colombia Travel</h2>";

// Verificar conexión
if (mysqli_connect_errno()) {
    echo "<p style='color: red;'>Error: No se pudo conectar a la base de datos.</p>";
    exit();
}

echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa.</p>";

// Leer el archivo SQL
$sql_file = 'wilrop_database.sql';
if (!file_exists($sql_file)) {
    echo "<p style='color: red;'>Error: No se encontró el archivo wilrop_database.sql</p>";
    exit();
}

$sql_content = file_get_contents($sql_file);

// Dividir el contenido en consultas individuales
$queries = explode(';', $sql_content);

$success_count = 0;
$error_count = 0;

echo "<h3>Ejecutando consultas SQL...</h3>";

foreach ($queries as $query) {
    $query = trim($query);
    
    if (empty($query) || strpos($query, '--') === 0) {
        continue;
    }
    
    if (mysqli_query($conexion, $query)) {
        $success_count++;
        echo "<p style='color: green;'>✓ Consulta ejecutada correctamente</p>";
    } else {
        $error_count++;
        echo "<p style='color: red;'>✗ Error en consulta: " . mysqli_error($conexion) . "</p>";
    }
}

echo "<h3>Resumen de la instalación:</h3>";
echo "<p style='color: green;'>Consultas exitosas: $success_count</p>";
echo "<p style='color: red;'>Consultas con error: $error_count</p>";

if ($error_count == 0) {
    echo "<h3 style='color: green;'>¡Instalación completada exitosamente!</h3>";
    echo "<p>El sistema de usuarios está listo para usar.</p>";
    echo "<p><strong>Usuario administrador por defecto:</strong></p>";
    echo "<ul>";
    echo "<li>Email: admin@wilropcolombia.com</li>";
    echo "<li>Usuario: admin</li>";
    echo "<li>Contraseña: admin</li>";
    echo "</ul>";
    echo "<p><a href='login.php'>Ir al login</a> | <a href='admin.php'>Ir al panel de administración</a></p>";
} else {
    echo "<h3 style='color: red;'>La instalación tuvo algunos errores.</h3>";
    echo "<p>Revisa los errores anteriores y vuelve a intentar.</p>";
}

mysqli_close($conexion);
?>
