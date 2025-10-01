<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['active']) || $_SESSION['idUser'] != 1) {
    header('Location: login.php');
    exit();
}

$user_id = $_GET['id'] ?? '';

if (empty($user_id) || $user_id == 1) {
    header('Location: manage-users.php');
    exit();
}

// Eliminar usuario
$query = "DELETE FROM usuario WHERE idusuario = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    // También eliminar permisos asociados
    mysqli_query($conexion, "DELETE FROM detalle_permisos WHERE id_usuario = $user_id");
    header('Location: manage-users.php?deleted=1');
} else {
    header('Location: manage-users.php?error=1');
}

mysqli_stmt_close($stmt);
exit();
?>
