<?php
    $host = "localhost";
    $user = "root";
    $clave = "";
    $bd = "wilrop";

    // Conectar al servidor MySQL sin seleccionar base de datos
    $conexion = mysqli_connect($host, $user, $clave);
    if (mysqli_connect_errno()) {
        echo "No se pudo conectar al servidor de base de datos";
        exit();
    }

    // Crear la base de datos si no existe
    $createDbSql = "CREATE DATABASE IF NOT EXISTS `" . $bd . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!mysqli_query($conexion, $createDbSql)) {
        echo "No se pudo crear/verificar la base de datos: " . htmlspecialchars(mysqli_error($conexion));
        exit();
    }

    // Seleccionar la base de datos
    if (!mysqli_select_db($conexion, $bd)) {
        echo "No se pudo seleccionar la base de datos";
        exit();
    }

    // Ajustar charset
    mysqli_set_charset($conexion, "utf8mb4");
?>
