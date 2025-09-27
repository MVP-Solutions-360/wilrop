# 🌴 Wilrop Colombia Travel - Documentación Completa

## 📋 Índice
1. [Descripción General](#descripción-general)
2. [Estructura del Proyecto](#estructura-del-proyecto)
3. [Páginas y Funcionalidades](#páginas-y-funcionalidades)
4. [Tecnologías Utilizadas](#tecnologías-utilizadas)
5. [Instalación y Configuración](#instalación-y-configuración)
6. [Estructura de Archivos](#estructura-de-archivos)
7. [Funcionalidades JavaScript](#funcionalidades-javascript)
8. [Sistema de Carruseles](#sistema-de-carruseles)
9. [Sistema de Login](#sistema-de-login)
10. [Gestión de Productos](#gestión-de-productos)
11. [Responsive Design](#responsive-design)
12. [Personalización](#personalización)
13. [Troubleshooting](#troubleshooting)
14. [Contribución](#contribución)

---

## 🎯 Descripción General

**Wilrop Colombia Travel** es una página web de turismo especializada en conectar República Dominicana con Colombia, específicamente con Antioquia. La web ofrece una experiencia completa para usuarios que desean explorar destinos turísticos, gestionar productos y realizar reservas.

### Características Principales:
- ✅ **Diseño Responsive** - Adaptable a todos los dispositivos
- ✅ **Carruseles Dinámicos** - Imágenes automáticas por destino
- ✅ **Sistema de Login** - Autenticación de usuarios
- ✅ **Gestión de Productos** - CRUD completo de paquetes turísticos
- ✅ **Página de Detalle** - Vista detallada con video integrado
- ✅ **Formularios Interactivos** - Validación en tiempo real
- ✅ **Navegación Intuitiva** - Breadcrumbs y menús dinámicos

---

## 📁 Estructura del Proyecto

```
wilrop/
├── 📄 index.html              # Página principal
├── 📄 dominicana.html         # Página de República Dominicana
├── 📄 colombia.html           # Página de Colombia
├── 📄 products.html           # Lista de productos
├── 📄 admin.html              # Panel de administración
├── 📄 login.html              # Página de login
├── 📄 product-detail.html     # Detalle de producto
├── 🎨 styles.css              # Estilos principales
├── ⚡ scripts.js              # Funcionalidades JavaScript
├── 📁 imagenes/               # Recursos multimedia
│   ├── 📁 logos/              # Logotipos de la empresa
│   └── 📁 destinos/           # Imágenes por destino
│       ├── 📁 index/          # Imágenes del carrusel principal
│       ├── 📁 republica_dominicana/
│       │   └── 📁 punta_cana/ # Imágenes de Punta Cana
│       └── 📁 colombia/
│           └── 📁 antioquia/
│               └── 📁 guatape/ # Imágenes y video de Guatapé
└── 📄 README.md               # Esta documentación
```

---

## 🌐 Páginas y Funcionalidades

### 🏠 **index.html** - Página Principal
**Propósito**: Landing page con información general de la empresa

**Características**:
- **Carrusel Principal**: 17 imágenes rotativas de destinos
- **Sección Hero**: Mensaje principal con call-to-action
- **Destinos Destacados**: Grid de destinos principales
- **Servicios**: Información de servicios ofrecidos
- **Contacto**: Formulario de contacto
- **Footer**: Enlaces y información de contacto

**Carrusel**: Carga automáticamente imágenes de `imagenes/destinos/index/`

### 🇩🇴 **dominicana.html** - República Dominicana
**Propósito**: Página dedicada a destinos dominicanos

**Características**:
- **Carrusel Específico**: 6 imágenes de Punta Cana
- **Destinos Principales**: Punta Cana, Santo Domingo, Puerto Plata, Samaná
- **Experiencias Únicas**: Buceo, música, gastronomía, ecoturismo
- **Cultura y Tradiciones**: Información cultural detallada
- **Información Práctica**: Documentación, moneda, idioma, clima

**Carrusel**: Carga imágenes de `imagenes/destinos/republica_dominicana/punta_cana/`

### 🇨🇴 **colombia.html** - Colombia
**Propósito**: Página dedicada a destinos colombianos

**Características**:
- **Carrusel Específico**: 4 imágenes de Guatapé
- **Destinos Principales**: Bogotá, Cartagena, Medellín, Cali
- **Sección Antioquia**: Destino especial con su propio carrusel
- **Cultura Paisa**: Tradiciones y experiencias únicas
- **Información Práctica**: Datos útiles para viajeros

**Carrusel**: Carga imágenes de `imagenes/destinos/colombia/antioquia/guatape/`

### 📦 **products.html** - Lista de Productos
**Propósito**: Gestión y visualización de paquetes turísticos

**Características**:
- **Filtros Avanzados**: Por destino, tipo, precio, búsqueda
- **Vista Dual**: Grid y lista
- **Estadísticas**: Contador de productos
- **Modal de Detalle**: Vista rápida de productos
- **Exportación/Importación**: Gestión de datos

**Funcionalidades**:
- Filtrado en tiempo real
- Ordenamiento por múltiples criterios
- Búsqueda por texto
- Eliminación de productos
- Vista responsive

### ⚙️ **admin.html** - Panel de Administración
**Propósito**: Creación y gestión de productos turísticos

**Características**:
- **Formulario de Producto**: Campos completos para crear productos
- **Validación**: Validación en tiempo real
- **Acciones Rápidas**: Enlaces a funciones principales
- **Gestión de Datos**: Exportar, importar, limpiar

**Campos del Formulario**:
- Nombre del producto
- Destino
- Tipo (aventura, relax, cultural, gastronómico)
- Precio
- Duración
- Descripción
- Incluye/No incluye

### 🔐 **login.html** - Sistema de Autenticación
**Propósito**: Login de usuarios con gestión de sesiones

**Características**:
- **Formulario de Login**: Email y contraseña
- **Recordarme**: Sesión persistente
- **Redes Sociales**: Botones de Google y Facebook (placeholder)
- **Validación**: Validación de email y campos requeridos
- **Menú de Usuario**: Dropdown con opciones post-login

**Credenciales de Demo**:
- Email: `admin@wilrop.com`
- Contraseña: `admin123`

### 🎬 **product-detail.html** - Detalle de Producto
**Propósito**: Vista detallada de productos con funcionalidad de reserva

**Características**:
- **Layout de 3 Columnas**: Video, información, reserva
- **Video Integrado**: Carga automática de video promocional
- **Formulario de Reserva**: Fecha, personas, tipo de habitación
- **Cálculo Automático**: Precios con impuestos
- **Información Adicional**: Itinerario, políticas, detalles

**Video**: Carga automáticamente `Guatape.mp4` de la carpeta de Guatapé

---

## 🛠️ Tecnologías Utilizadas

### Frontend
- **HTML5**: Estructura semántica
- **CSS3**: Estilos modernos con Grid y Flexbox
- **JavaScript ES6+**: Funcionalidades interactivas
- **Font Awesome**: Iconografía
- **Google Fonts**: Tipografía Poppins

### Características Técnicas
- **Responsive Design**: Mobile-first approach
- **Local Storage**: Persistencia de datos
- **CSS Grid & Flexbox**: Layouts modernos
- **CSS Animations**: Transiciones suaves
- **Progressive Enhancement**: Funcionalidad sin JavaScript

---

## 🚀 Instalación y Configuración

### Requisitos
- Navegador web moderno
- Servidor web local (opcional)
- Editor de código (recomendado)

### Instalación Local

1. **Clonar/Descargar el proyecto**
```bash
# Si tienes git
git clone [url-del-repositorio]
cd wilrop

# O descargar y extraer el ZIP
```

2. **Levantar servidor local (opcional)**
```bash
# Con Python 3
python -m http.server 8000

# Con Python 2
python -m SimpleHTTPServer 8000

# Con Node.js (si tienes http-server instalado)
npx http-server

# Con PHP
php -S localhost:8000
```

3. **Acceder a la web**
```
http://localhost:8000
```

### Configuración de Imágenes

1. **Estructura de carpetas**:
```
imagenes/
├── logos/
│   └── wilrop_vertical.png
└── destinos/
    ├── index/ (17 imágenes para carrusel principal)
    ├── republica_dominicana/punta_cana/ (6 imágenes)
    └── colombia/antioquia/guatape/ (4 imágenes + video)
```

2. **Especificaciones de imágenes**:
- **Formato**: JPG, PNG
- **Tamaño recomendado**: 1920x1080px
- **Peso máximo**: 500KB por imagen
- **Video**: MP4, máximo 50MB

---

## 📂 Estructura de Archivos Detallada

### **styles.css** (3,028 líneas)
Contiene todos los estilos de la aplicación:

```css
/* Secciones principales */
- Reset y variables CSS
- Estilos del header y navegación
- Estilos de las páginas principales
- Sistema de carruseles
- Formularios y validaciones
- Sistema de login
- Página de detalle de producto
- Responsive design
- Animaciones y transiciones
```

### **scripts.js** (1,400+ líneas)
Contiene toda la funcionalidad JavaScript:

```javascript
/* Funciones principales */
- Gestión de productos (CRUD)
- Sistema de carruseles dinámicos
- Sistema de login y sesiones
- Validación de formularios
- Cálculo de precios
- Gestión de localStorage
- Navegación móvil
- Notificaciones
```

---

## ⚡ Funcionalidades JavaScript

### 🔄 **Sistema de Carruseles**

#### Carrusel Principal (index.html)
```javascript
// Variables globales
let currentSlideIndex = 0;
let slides = [];
let indicators = [];
let slideInterval;

// Lista de imágenes (17 total)
const carouselImages = [
    'imagenes/destinos/index/buggie.png',
    'imagenes/destinos/index/buggie2.png',
    // ... más imágenes
];

// Funciones principales
- initCarousel()           // Inicializar carrusel
- createCarouselSlides()   // Crear slides dinámicamente
- showSlide(index)         // Mostrar slide específico
- changeSlide(direction)   // Cambiar slide
- startAutoSlide()         // Iniciar auto-slide
- stopAutoSlide()          // Detener auto-slide
```

#### Carrusel República Dominicana
```javascript
// Variables específicas
let currentDomSlideIndex = 0;
let domSlides = [];
let domIndicators = [];
let domSlideInterval;

// Funciones con sufijo 'Dom'
- initDominicanaCarousel()
- createDominicanaCarouselSlides()
- showSlideDom(index)
- changeSlideDom(direction)
```

#### Carrusel Colombia
```javascript
// Variables específicas
let currentColSlideIndex = 0;
let colSlides = [];
let colIndicators = [];
let colSlideInterval;

// Funciones con sufijo 'Col'
- initColombiaCarousel()
- createColombiaCarouselSlides()
- showSlideCol(index)
- changeSlideCol(direction)
```

### 🔐 **Sistema de Login**

```javascript
// Funciones principales
- initLogin()              // Inicializar sistema de login
- handleLogin(e)           // Manejar envío del formulario
- isValidEmail(email)      // Validar formato de email
- togglePassword()         // Mostrar/ocultar contraseña
- checkUserSession()       // Verificar sesión activa
- updateNavbarForLoggedUser() // Actualizar navbar
- showUserMenu()           // Mostrar menú de usuario
- logout()                 // Cerrar sesión

// Gestión de sesiones
- localStorage: Para "recordarme"
- sessionStorage: Para sesión temporal
- Datos del usuario: email, nombre, timestamp
```

### 📦 **Gestión de Productos**

```javascript
// CRUD de productos
- addProducto()            // Agregar producto
- saveProductos()          // Guardar en localStorage
- loadProductos()          // Cargar desde localStorage
- deleteProducto(id)       // Eliminar producto
- filterProductos()        // Filtrar productos
- exportProductos()        // Exportar a JSON
- importProductos()        // Importar desde JSON
- limpiarProductos()       // Limpiar todos los productos

// Estructura de producto
{
    id: timestamp,
    nombre: string,
    destino: string,
    tipo: string,
    precio: number,
    duracion: string,
    descripcion: string,
    incluye: string,
    noIncluye: string
}
```

### 🎬 **Página de Detalle de Producto**

```javascript
// Funciones principales
- initProductDetail()      // Inicializar página
- loadProductData()        // Cargar datos del producto
- updateProductDisplay()   // Actualizar visualización
- setupDateInput()         // Configurar input de fecha
- changeQuantity(delta)    // Cambiar cantidad
- calculateTotal()         // Calcular precio total
- proceedToBooking()       // Procesar reserva
- initVideo()              // Inicializar video

// Cálculo de precios
- Precio base × cantidad × tipo de habitación
- Impuestos: 10%
- Multiplicadores por habitación:
  - Estándar: 1.0
  - Superior: 1.2
  - Deluxe: 1.5
```

---

## 🎨 Sistema de Carruseles

### Detección Automática de Página
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    
    if (currentPage === 'dominicana.html') {
        initDominicanaCarousel();
    } else if (currentPage === 'colombia.html') {
        initColombiaCarousel();
    } else if (currentPage === 'index.html' || currentPage === '') {
        initCarousel();
    }
    // ... más páginas
});
```

### Características de los Carruseles
- **Auto-slide**: Cada 5 segundos
- **Controles manuales**: Flechas izquierda/derecha
- **Indicadores**: Puntos de navegación
- **Pausa al hover**: Se detiene al pasar el mouse
- **Transiciones suaves**: 1 segundo de duración
- **Responsive**: Se adapta a móviles

### Imágenes por Carrusel
| Página | Cantidad | Carpeta | Funciones |
|--------|----------|---------|-----------|
| **Index** | 17 | `destinos/index` | `initCarousel()` |
| **República Dominicana** | 6 | `republica_dominicana/punta_cana` | `initDominicanaCarousel()` |
| **Colombia** | 4 | `colombia/antioquia/guatape` | `initColombiaCarousel()` |

---

## 🔐 Sistema de Login

### Flujo de Autenticación
1. **Usuario ingresa credenciales**
2. **Validación del formulario**
3. **Verificación de credenciales** (demo)
4. **Creación de sesión**
5. **Actualización del navbar**
6. **Redirección o menú de usuario**

### Gestión de Sesiones
```javascript
// Estructura de datos de usuario
const userData = {
    email: 'admin@wilrop.com',
    name: 'Usuario Admin',
    loginTime: '2025-01-XX...',
    rememberMe: true/false
};

// Almacenamiento
if (rememberMe) {
    localStorage.setItem('userSession', JSON.stringify(userData));
} else {
    sessionStorage.setItem('userSession', JSON.stringify(userData));
}
```

### Menú de Usuario
- **Mi Perfil**: Información del usuario
- **Mis Reservas**: Historial de reservas
- **Configuración**: Ajustes de cuenta
- **Cerrar Sesión**: Logout con limpieza

---

## 📱 Responsive Design

### Breakpoints
```css
/* Desktop */
@media (min-width: 1024px) { /* 3 columnas */ }

/* Tablet */
@media (max-width: 1024px) { /* 1 columna */ }

/* Mobile */
@media (max-width: 768px) { /* Layout optimizado */ }

/* Mobile pequeño */
@media (max-width: 480px) { /* Elementos apilados */ }
```

### Adaptaciones por Dispositivo

#### Desktop (1024px+)
- Grid de 3 columnas en detalle de producto
- Navegación horizontal completa
- Carruseles con controles laterales
- Formularios en múltiples columnas

#### Tablet (768px - 1024px)
- Grid de 1 columna
- Navegación colapsable
- Carruseles adaptados
- Formularios apilados

#### Mobile (< 768px)
- Layout vertical
- Menú hamburguesa
- Carruseles simplificados
- Botones más grandes
- Texto optimizado

---

## 🎨 Personalización

### Colores de la Marca
```css
:root {
    --primary-color: #2c5aa0;
    --secondary-color: #3498db;
    --accent-color: #ffc107;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
}
```

### Tipografía
```css
/* Fuente principal */
font-family: 'Poppins', sans-serif;

/* Pesos disponibles */
font-weight: 300, 400, 500, 600, 700;
```

### Modificar Carruseles
Para agregar más imágenes a un carrusel:

1. **Agregar imágenes** a la carpeta correspondiente
2. **Actualizar el array** en `scripts.js`:

```javascript
// Ejemplo: Agregar imagen al carrusel principal
const carouselImages = [
    'imagenes/destinos/index/nueva_imagen.png',
    // ... resto de imágenes
];
```

### Personalizar Productos
Para modificar los tipos de productos:

```javascript
// En la función updateProductDisplay()
const badgeText = {
    'aventura': 'Aventura',
    'relax': 'Relax',
    'cultural': 'Cultural',
    'gastronomico': 'Gastronómico',
    'nuevo_tipo': 'Nuevo Tipo'  // Agregar aquí
};
```

---

## 🔧 Troubleshooting

### Problemas Comunes

#### 1. **Carrusel no funciona**
```javascript
// Verificar que las imágenes existan
console.log('Imágenes del carrusel:', carouselImages);

// Verificar que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado');
});
```

#### 2. **Login no funciona**
```javascript
// Verificar credenciales
if (email === 'admin@wilrop.com' && password === 'admin123') {
    // Login exitoso
}

// Verificar localStorage
console.log('Sesión guardada:', localStorage.getItem('userSession'));
```

#### 3. **Productos no se guardan**
```javascript
// Verificar localStorage
console.log('Productos guardados:', localStorage.getItem('productos'));

// Verificar estructura de datos
const producto = {
    id: Date.now(),
    nombre: 'Producto de prueba',
    // ... resto de campos
};
```

#### 4. **Video no se reproduce**
```html
<!-- Verificar ruta del video -->
<source src="imagenes/destinos/colombia/antioquia/guatape/Guatape.mp4" type="video/mp4">

<!-- Verificar que el archivo exista -->
```

### Debugging
```javascript
// Habilitar logs en consola
console.log('Debug activado');

// Verificar errores de JavaScript
window.addEventListener('error', function(e) {
    console.error('Error:', e.error);
});

// Verificar estado de localStorage
console.log('LocalStorage:', localStorage);
```

---

## 📊 Estructura de Datos

### Producto Turístico
```javascript
{
    id: 1704067200000,                    // Timestamp único
    nombre: "Aventura en Guatapé",        // Nombre del paquete
    destino: "Colombia",                  // País de destino
    tipo: "aventura",                     // Tipo de experiencia
    precio: 450000,                       // Precio en pesos
    duracion: "2 días / 1 noche",         // Duración del viaje
    descripcion: "Descripción detallada...", // Descripción completa
    incluye: "Transporte, alojamiento...", // Servicios incluidos
    noIncluye: "Bebidas alcohólicas..."   // Servicios no incluidos
}
```

### Sesión de Usuario
```javascript
{
    email: "admin@wilrop.com",            // Email del usuario
    name: "Usuario Admin",                // Nombre mostrado
    loginTime: "2025-01-01T10:00:00.000Z", // Timestamp de login
    rememberMe: true                      // Sesión persistente
}
```

### Reserva
```javascript
{
    productId: "123",                     // ID del producto
    date: "2025-02-15",                   // Fecha de salida
    quantity: 2,                          // Número de personas
    roomType: "superior",                 // Tipo de habitación
    total: "$594.000",                    // Precio total
    timestamp: "2025-01-01T10:00:00.000Z" // Timestamp de reserva
}
```

---

## 🚀 Optimizaciones Futuras

### Performance
- [ ] Lazy loading de imágenes
- [ ] Compresión de videos
- [ ] Minificación de CSS/JS
- [ ] CDN para recursos estáticos

### Funcionalidades
- [ ] Sistema de pagos real
- [ ] Integración con APIs de reservas
- [ ] Sistema de notificaciones push
- [ ] Chat en vivo
- [ ] Mapa interactivo

### SEO
- [ ] Meta tags dinámicos
- [ ] Schema markup
- [ ] Sitemap XML
- [ ] Optimización de imágenes

---

## 🤝 Contribución

### Cómo Contribuir
1. **Fork** del repositorio
2. **Crear branch** para nueva funcionalidad
3. **Commit** con mensajes descriptivos
4. **Push** al branch
5. **Pull Request** con descripción detallada

### Estándares de Código
- **HTML**: Semántico y accesible
- **CSS**: BEM methodology
- **JavaScript**: ES6+ con comentarios
- **Imágenes**: Optimizadas y responsive

### Reportar Bugs
- Usar el sistema de issues
- Incluir pasos para reproducir
- Especificar navegador y versión
- Adjuntar screenshots si es necesario

---

## 📞 Soporte

### Contacto
- **Email**: info@wilropcolombia.com
- **Teléfono**: +1 (809) 123-4567
- **Horario**: 24/7

### Recursos Adicionales
- [Documentación de HTML5](https://developer.mozilla.org/es/docs/Web/HTML)
- [Guía de CSS Grid](https://css-tricks.com/snippets/css/complete-guide-grid/)
- [JavaScript ES6+](https://developer.mozilla.org/es/docs/Web/JavaScript)
- [Font Awesome Icons](https://fontawesome.com/icons)

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 🏆 Créditos

**Desarrollado por**: Equipo de Desarrollo Wilrop Colombia Travel
**Diseño**: Inspirado en www.wellezy.com
**Iconos**: Font Awesome
**Tipografía**: Google Fonts (Poppins)
**Imágenes**: Recursos propios de Wilrop Colombia Travel

---

*Última actualización: Enero 2025*
*Versión: 1.0.0*
