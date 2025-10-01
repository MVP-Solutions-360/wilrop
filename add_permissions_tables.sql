-- Agregar tablas de permisos a la base de datos wilrop existente
USE wilrop;

-- Tabla de permisos
CREATE TABLE IF NOT EXISTS permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    descripcion VARCHAR(100) DEFAULT NULL,
    modulo VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de detalle de permisos (relación usuario-permisos)
CREATE TABLE IF NOT EXISTS detalle_permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_permiso INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_permiso) REFERENCES permisos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar permisos por defecto
INSERT INTO permisos (nombre, descripcion, modulo) VALUES
('configuracion', 'Acceso a configuraciones del sistema', 'admin'),
('usuarios', 'Gestionar usuarios y cuentas', 'admin'),
('clientes', 'Administrar información de clientes', 'admin'),
('productos', 'Gestionar catálogo de productos', 'admin'),
('ventas', 'Ver historial de ventas', 'admin'),
('nueva_venta', 'Crear nuevas ventas', 'admin');

-- Insertar usuario administrador por defecto si no existe
INSERT IGNORE INTO usuarios (id, nombre, apellido, email, password) VALUES
(1, 'Administrador', 'Wilrop', 'admin@wilropcolombia.com', '21232f297a57a5a743894a0e4a801fc3');

-- Asignar todos los permisos al administrador
INSERT IGNORE INTO detalle_permisos (id_usuario, id_permiso) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6);

-- Crear índices para mejorar rendimiento (después de crear las tablas)
-- Los índices se crearán automáticamente con las claves foráneas
