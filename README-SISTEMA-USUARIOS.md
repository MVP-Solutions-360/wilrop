# Sistema de Usuarios y Roles - Wilrop Colombia Travel

## 📋 Descripción

Este sistema integra funcionalidades de gestión de usuarios y roles al proyecto de turismo Wilrop Colombia Travel, permitiendo:

- **Registro de usuarios** con validación de datos
- **Sistema de login** con verificación en base de datos
- **Gestión de usuarios** desde panel de administración
- **Sistema de roles y permisos** granular
- **Control de acceso** por módulos

## 🗄️ Estructura de Base de Datos

### Tablas Principales

1. **`usuario`** - Información de usuarios
   - `idusuario` (PK)
   - `nombre` - Nombre completo
   - `correo` - Email único
   - `usuario` - Nombre de usuario único
   - `clave` - Contraseña encriptada (MD5)

2. **`permisos`** - Permisos disponibles
   - `id` (PK)
   - `nombre` - Nombre del permiso

3. **`detalle_permisos`** - Relación usuario-permisos
   - `id` (PK)
   - `id_usuario` (FK)
   - `id_permiso` (FK)

### Permisos Disponibles

- **configuración** - Acceso a configuraciones del sistema
- **usuarios** - Gestionar usuarios y cuentas
- **clientes** - Administrar información de clientes
- **productos** - Gestionar catálogo de productos
- **ventas** - Ver historial de ventas
- **nueva_venta** - Crear nuevas ventas

## 📁 Archivos del Sistema

### Archivos PHP Principales

- `login.php` - Página de inicio de sesión
- `register.php` - Página de registro de usuarios
- `logout.php` - Cerrar sesión
- `index.php` - Página principal con PHP
- `admin.php` - Panel de administración
- `manage-users.php` - Gestión de usuarios
- `assign-roles.php` - Asignación de roles
- `delete-user.php` - Eliminar usuarios
- `conexion.php` - Conexión a base de datos
- `config-permisos.php` - Configuración de permisos

### Archivos de Configuración

- `conexion.php` - Configuración de base de datos
- `config-permisos.php` - Definición de permisos

## 🚀 Instalación

### 1. Configurar Base de Datos

1. Importar el archivo `sistema.sql` en tu base de datos MySQL
2. Verificar que la conexión en `conexion.php` sea correcta

### 2. Configurar Archivos

1. Asegúrate de que `conexion.php` tenga las credenciales correctas
2. Verifica que todos los archivos PHP estén en la raíz del proyecto

### 3. Usuario Administrador

El sistema viene con un usuario administrador por defecto:
- **Email:** ana.info1999@gmail.com
- **Usuario:** admin
- **Contraseña:** admin (encriptada en MD5)

## 🔐 Funcionalidades

### Sistema de Login

- Validación de credenciales contra base de datos
- Encriptación de contraseñas con MD5
- Gestión de sesiones PHP
- Redirección según tipo de usuario

### Registro de Usuarios

- Formulario de registro completo
- Validación de datos en tiempo real
- Verificación de correos únicos
- Validación de nombres de usuario únicos

### Panel de Administración

- Dashboard con estadísticas
- Gestión completa de usuarios
- Asignación de roles y permisos
- Interfaz responsive y moderna

### Sistema de Roles

- Permisos granulares por módulo
- Asignación individual de permisos
- Control de acceso automático
- Interfaz visual para gestión

## 🎨 Diseño

- **Responsive Design** - Adaptable a todos los dispositivos
- **Material Design** - Interfaz moderna y limpia
- **Bootstrap 4** - Componentes predefinidos
- **Font Awesome** - Iconografía consistente
- **Colores personalizados** - Mantiene la identidad de Wilrop

## 🔧 Uso del Sistema

### Para Usuarios

1. **Registrarse:** Visitar `register.php`
2. **Iniciar Sesión:** Visitar `login.php`
3. **Acceder al Panel:** Usar el menú de usuario

### Para Administradores

1. **Acceder al Admin:** Usar `admin.php`
2. **Gestionar Usuarios:** Usar `manage-users.php`
3. **Asignar Roles:** Usar `assign-roles.php`

## 🛡️ Seguridad

- **Validación de entrada** - Todos los datos son validados
- **Prepared Statements** - Prevención de SQL Injection
- **Encriptación de contraseñas** - MD5 (considerar actualizar a bcrypt)
- **Control de sesiones** - Gestión segura de sesiones
- **Verificación de permisos** - Acceso controlado por roles

## 📱 Responsive

El sistema está completamente optimizado para:
- **Desktop** - Experiencia completa
- **Tablet** - Interfaz adaptada
- **Mobile** - Navegación táctil

## 🔄 Integración

El sistema se integra perfectamente con:
- **Páginas HTML existentes** - Mantiene el diseño original
- **Sistema de estilos** - Usa `styles.css` existente
- **JavaScript** - Compatible con `scripts.js`
- **Base de datos** - Usa la estructura existente

## 🐛 Solución de Problemas

### Error de Conexión
- Verificar credenciales en `conexion.php`
- Asegurar que MySQL esté ejecutándose

### Error de Permisos
- Verificar que el usuario tenga los permisos necesarios
- Revisar la tabla `detalle_permisos`

### Error de Sesión
- Verificar que las sesiones PHP estén habilitadas
- Limpiar caché del navegador

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema:
- **Email:** info@wilropcolombia.com
- **Teléfono:** +1 (809) 123-4567

## 📄 Licencia

Este sistema está desarrollado específicamente para Wilrop Colombia Travel y está protegido por derechos de autor.

---

**Desarrollado para Wilrop Colombia Travel**  
*Especialistas en turismo entre República Dominicana y Colombia*
