-- Base de datos: wilrop
-- Sistema de usuarios y roles para Wilrop Colombia Travel

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `wilrop` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `wilrop`;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuario`
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `correo` (`correo`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `permisos`
CREATE TABLE IF NOT EXISTS `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `detalle_permisos`
CREATE TABLE IF NOT EXISTS `detalle_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_permiso` (`id_permiso`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `detalle_permisos_ibfk_1` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_permisos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Insertar permisos por defecto
INSERT INTO `permisos` (`id`, `nombre`, `descripcion`, `modulo`) VALUES
(1, 'configuración', 'Acceso a configuraciones del sistema', 'admin'),
(2, 'usuarios', 'Gestionar usuarios y cuentas', 'admin'),
(3, 'clientes', 'Administrar información de clientes', 'admin'),
(4, 'productos', 'Gestionar catálogo de productos', 'admin'),
(5, 'ventas', 'Ver historial de ventas', 'admin'),
(6, 'nueva_venta', 'Crear nuevas ventas', 'admin');

-- --------------------------------------------------------

-- Insertar usuario administrador por defecto
INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `activo`) VALUES
(1, 'Administrador Wilrop', 'admin@wilropcolombia.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

-- Asignar todos los permisos al administrador
INSERT INTO `detalle_permisos` (`id_usuario`, `id_permiso`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6);

-- --------------------------------------------------------

-- Crear índices para mejorar rendimiento (después de crear las tablas)
ALTER TABLE `usuario` ADD INDEX `idx_usuario_correo` (`correo`);
ALTER TABLE `usuario` ADD INDEX `idx_usuario_activo` (`activo`);
ALTER TABLE `detalle_permisos` ADD INDEX `idx_detalle_permisos_usuario` (`id_usuario`);
ALTER TABLE `detalle_permisos` ADD INDEX `idx_detalle_permisos_permiso` (`id_permiso`);
