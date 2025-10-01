// Variables globales
let productos = JSON.parse(localStorage.getItem('productos')) || [];

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Función principal de inicialización
function initializeApp() {
    setupNavigation();
    setupForms();
    setupScrollEffects();
    loadProductos();
    checkUserSession();
}

// Configuración de navegación
function setupNavigation() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');
    const overlay = document.querySelector('.mobile-menu-overlay');

    // Toggle del menú móvil
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
    }

    // Cerrar menú al hacer clic en el overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Cerrar menú al hacer clic en un enlace
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            if (overlay) {
                overlay.classList.remove('active');
            }
        });
    });

    // Scroll suave para enlaces internos
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Configuración de formularios
function setupForms() {
    // Formulario de contacto
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactForm);
    }

    // Formulario de producto
    const productoForm = document.getElementById('productoForm');
    if (productoForm) {
        productoForm.addEventListener('submit', handleProductoForm);
    }

    // Formulario de consulta
    const consultaForm = document.getElementById('consultaForm');
    if (consultaForm) {
        consultaForm.addEventListener('submit', handleConsultaForm);
    }

    // Validación en tiempo real
    setupRealTimeValidation();
}

// Manejo del formulario de contacto
function handleContactForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    // Validación
    if (!validateContactForm(data)) {
        return;
    }
    
    // Crear mensaje para WhatsApp
    const whatsappMessage = createWhatsAppMessage(data);
    
    // Redirigir a WhatsApp
    const whatsappUrl = `https://wa.me/573506852261?text=${encodeURIComponent(whatsappMessage)}`;
    window.open(whatsappUrl, '_blank');
    
    // Mostrar notificación
    showNotification('¡Redirigiendo a WhatsApp!', 'success');
    
    // Resetear formulario
    e.target.reset();
}

// Crear mensaje formateado para WhatsApp
function createWhatsAppMessage(data) {
    const destinoText = data.destinoInteres ? 
        (data.destinoInteres === 'republica-dominicana' ? 'República Dominicana' :
         data.destinoInteres === 'antioquia' ? 'Antioquia, Colombia' :
         data.destinoInteres === 'ambos' ? 'Ambos destinos' : 'No especificado') : 'No especificado';
    
    return `🌴 *Nuevo mensaje desde Wilrop Colombia Travel*

👤 *Nombre:* ${data.nombre}
📧 *Email:* ${data.email}
📱 *Teléfono:* ${data.telefono || 'No proporcionado'}
🌍 *Destino de interés:* ${destinoText}

💬 *Mensaje:*
${data.mensaje}

---
*Enviado desde la web de Wilrop Colombia Travel*`;
}

// Manejo del formulario de producto
function handleProductoForm(e) {
    const formEl = e.target;
    const isServerMode = !!formEl.querySelector('[name="pais"]'); // nuevo formulario (PHP)

    if (isServerMode) {
        // Validación mínima acorde a la BD
        const formData = new FormData(formEl);
        const nombre = (formData.get('nombre') || '').trim();
        const pais = (formData.get('pais') || '').trim();
        const precio = parseFloat(formData.get('precio'));
        const descripcion = (formData.get('descripcion') || '').trim();

        const errors = [];
        if (!nombre || nombre.length < 3) errors.push('El nombre del producto debe tener al menos 3 caracteres');
        if (!pais) errors.push('Seleccione un país');
        if (!Number.isFinite(precio) || precio <= 0) errors.push('El precio debe ser mayor a 0');
        if (descripcion && descripcion.length < 3) errors.push('La descripción es muy corta');

        if (errors.length > 0) {
            e.preventDefault();
            showNotification(errors.join('<br>'), 'error');
            return;
        }
        // Dejar enviar al backend PHP
        return;
    }

    // Modo anterior (frontend/localStorage)
    e.preventDefault();
    const formData = new FormData(formEl);
    const data = Object.fromEntries(formData);

    if (!validateProductoForm(data)) {
        return;
    }

    const producto = {
        id: Date.now(),
        nombre: data.nombreProducto,
        destino: data.destinoProducto,
        tipo: data.tipoProducto,
        precio: parseFloat(data.precioProducto),
        duracion: parseInt(data.duracionProducto),
        descripcion: data.descripcionProducto,
        incluye: data.incluyeProducto ? data.incluyeProducto.split(';').map(item => item.trim()) : [],
        fechaCreacion: new Date().toISOString()
    };

    productos.push(producto);
    saveProductos();

    showNotification('Producto agregado exitosamente', 'success');
    formEl.reset();
}

// Manejo del formulario de consulta
function handleConsultaForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const filtros = Object.fromEntries(formData);
    
    // Filtrar productos
    const productosFiltrados = filtrarProductos(filtros);
    
    // Mostrar resultados
    mostrarResultadosConsulta(productosFiltrados);
}

// Validación del formulario de contacto
function validateContactForm(data) {
    const errors = [];
    
    if (!data.nombre || data.nombre.trim().length < 2) {
        errors.push('El nombre debe tener al menos 2 caracteres');
    }
    
    if (!data.email || !isValidEmail(data.email)) {
        errors.push('Ingrese un email válido');
    }
    
    if (!data.mensaje || data.mensaje.trim().length < 10) {
        errors.push('El mensaje debe tener al menos 10 caracteres');
    }
    
    if (errors.length > 0) {
        showNotification(errors.join('<br>'), 'error');
        return false;
    }
    
    return true;
}

// Validación del formulario de producto
function validateProductoForm(data) {
    const errors = [];
    
    if (!data.nombreProducto || data.nombreProducto.trim().length < 3) {
        errors.push('El nombre del producto debe tener al menos 3 caracteres');
    }
    
    if (!data.destinoProducto) {
        errors.push('Seleccione un destino');
    }
    
    if (!data.tipoProducto) {
        errors.push('Seleccione un tipo de producto');
    }
    
    if (!data.precioProducto || parseFloat(data.precioProducto) <= 0) {
        errors.push('El precio debe ser mayor a 0');
    }
    
    if (!data.duracionProducto || parseInt(data.duracionProducto) < 1) {
        errors.push('La duración debe ser al menos 1 día');
    }
    
    if (!data.descripcionProducto || data.descripcionProducto.trim().length < 10) {
        errors.push('La descripción debe tener al menos 10 caracteres');
    }
    
    if (errors.length > 0) {
        showNotification(errors.join('<br>'), 'error');
        return false;
    }
    
    return true;
}

// Validación en tiempo real
function setupRealTimeValidation() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

// Validar campo individual
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let message = '';
    
    // Validaciones específicas por tipo de campo
    switch (field.type) {
        case 'email':
            if (value && !isValidEmail(value)) {
                isValid = false;
                message = 'Ingrese un email válido';
            }
            break;
        case 'tel':
            if (value && !isValidPhone(value)) {
                isValid = false;
                message = 'Ingrese un teléfono válido';
            }
            break;
        case 'number':
            if (value && parseFloat(value) < 0) {
                isValid = false;
                message = 'El valor debe ser positivo';
            }
            break;
    }
    
    // Validaciones por campo requerido
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'Este campo es obligatorio';
    }
    
    // Validaciones por longitud mínima
    if (value && field.hasAttribute('minlength')) {
        const minLength = parseInt(field.getAttribute('minlength'));
        if (value.length < minLength) {
            isValid = false;
            message = `Mínimo ${minLength} caracteres`;
        }
    }
    
    if (!isValid) {
        showFieldError(field, message);
    } else {
        clearFieldError(field);
    }
    
    return isValid;
}

// Mostrar error en campo
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#e74c3c';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    
    field.parentNode.appendChild(errorDiv);
}

// Limpiar error de campo
function clearFieldError(field) {
    field.style.borderColor = '';
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Filtrar productos
function filtrarProductos(filtros) {
    return productos.filter(producto => {
        // Filtro por destino
        if (filtros.filtroDestino && producto.destino !== filtros.filtroDestino) {
            return false;
        }
        
        // Filtro por tipo
        if (filtros.filtroTipo && producto.tipo !== filtros.filtroTipo) {
            return false;
        }
        
        // Filtro por precio mínimo
        if (filtros.precioMin && producto.precio < parseFloat(filtros.precioMin)) {
            return false;
        }
        
        // Filtro por precio máximo
        if (filtros.precioMax && producto.precio > parseFloat(filtros.precioMax)) {
            return false;
        }
        
        return true;
    });
}

// Mostrar resultados de consulta
function mostrarResultadosConsulta(productos) {
    const resultadosDiv = document.getElementById('resultadosConsulta') || crearResultadosDiv();
    
    if (productos.length === 0) {
        resultadosDiv.innerHTML = '<p>No se encontraron productos con los filtros seleccionados.</p>';
        return;
    }
    
    let html = '<h4>Productos encontrados:</h4><div class="productos-grid">';
    
    productos.forEach(producto => {
        html += `
            <div class="producto-card">
                <h5>${producto.nombre}</h5>
                <p><strong>Destino:</strong> ${getDestinoName(producto.destino)}</p>
                <p><strong>Tipo:</strong> ${getTipoName(producto.tipo)}</p>
                <p><strong>Precio:</strong> $${producto.precio.toFixed(2)} USD</p>
                <p><strong>Duración:</strong> ${producto.duracion} días</p>
                <p><strong>Descripción:</strong> ${producto.descripcion}</p>
                ${producto.incluye.length > 0 ? `<p><strong>Incluye:</strong> ${producto.incluye.join(', ')}</p>` : ''}
                <button onclick="eliminarProducto(${producto.id})" class="btn btn-danger">Eliminar</button>
            </div>
        `;
    });
    
    html += '</div>';
    resultadosDiv.innerHTML = html;
}

// Crear div de resultados
function crearResultadosDiv() {
    const div = document.createElement('div');
    div.id = 'resultadosConsulta';
    div.className = 'resultados-consulta';
    div.style.marginTop = '2rem';
    div.style.padding = '2rem';
    div.style.background = '#f8f9fa';
    div.style.borderRadius = '10px';
    
    const formContainer = document.querySelector('#consultaForm').closest('.form-container');
    formContainer.appendChild(div);
    
    return div;
}

// Eliminar producto
function eliminarProducto(id) {
    if (confirm('¿Está seguro de que desea eliminar este producto?')) {
        productos = productos.filter(p => p.id !== id);
        saveProductos();
        showNotification('Producto eliminado exitosamente', 'success');
        
        // Actualizar resultados si están visibles
        const consultaForm = document.getElementById('consultaForm');
        if (consultaForm) {
            const formData = new FormData(consultaForm);
            const filtros = Object.fromEntries(formData);
            const productosFiltrados = filtrarProductos(filtros);
            mostrarResultadosConsulta(productosFiltrados);
        }
    }
}

// Efectos de scroll
function setupScrollEffects() {
    // Header transparente al hacer scroll
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
        }
    });
    
    // Animaciones al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observar elementos para animación
    const animatedElements = document.querySelectorAll('.destino-card, .servicio-card, .form-container');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Utilidades
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

function getDestinoName(destino) {
    const destinos = {
        'republica-dominicana': 'República Dominicana',
        'antioquia': 'Antioquia, Colombia'
    };
    return destinos[destino] || destino;
}

function getTipoName(tipo) {
    const tipos = {
        'paquete': 'Paquete Turístico',
        'vuelo': 'Vuelo',
        'hotel': 'Hospedaje',
        'tour': 'Tour/Excursión'
    };
    return tipos[tipo] || tipo;
}

// Notificaciones
function showNotification(message, type = 'info') {
    // Remover notificación existente
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = message;
    
    // Estilos de la notificación
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '1rem 1.5rem',
        borderRadius: '10px',
        color: 'white',
        fontWeight: '500',
        zIndex: '10000',
        maxWidth: '400px',
        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
        transform: 'translateX(100%)',
        transition: 'transform 0.3s ease'
    });
    
    // Colores según el tipo
    const colors = {
        success: '#27ae60',
        error: '#e74c3c',
        warning: '#f39c12',
        info: '#3498db'
    };
    
    notification.style.background = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Estados de carga
function showLoading(button) {
    button.disabled = true;
    button.dataset.originalText = button.textContent;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
}

function hideLoading(button) {
    button.disabled = false;
    button.textContent = button.dataset.originalText;
}

// Gestión de productos en localStorage
function saveProductos() {
    localStorage.setItem('productos', JSON.stringify(productos));
}

function loadProductos() {
    productos = JSON.parse(localStorage.getItem('productos')) || [];
}

// Función para exportar productos (útil para administración)
function exportarProductos() {
    const dataStr = JSON.stringify(productos, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = 'productos-wilrop.json';
    link.click();
    
    URL.revokeObjectURL(url);
}

// Función para importar productos
function importarProductos(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const importedProductos = JSON.parse(e.target.result);
            if (Array.isArray(importedProductos)) {
                productos = importedProductos;
                saveProductos();
                showNotification('Productos importados exitosamente', 'success');
            } else {
                showNotification('Formato de archivo inválido', 'error');
            }
        } catch (error) {
            showNotification('Error al importar productos', 'error');
        }
    };
    reader.readAsText(file);
}

// Inicializar contador de productos en el header (opcional)
function updateProductCounter() {
    const counter = document.querySelector('.product-counter');
    if (counter) {
        counter.textContent = productos.length;
    }
}

// Llamar a updateProductCounter cuando se carguen o modifiquen los productos
document.addEventListener('DOMContentLoaded', updateProductCounter);

// Funcionalidad del Carrusel de República Dominicana
let currentDomSlideIndex = 0;
let domSlides = [];
let domIndicators = [];
let domSlideInterval;

// Lista de imágenes de República Dominicana
const dominicanaImages = [
    'imagenes/destinos/republica_dominicana/punta_cana/pareja_playa1.png',
    'imagenes/destinos/republica_dominicana/punta_cana/pareja_playa2.png',
    'imagenes/destinos/republica_dominicana/punta_cana/punta_cana1.png',
    'imagenes/destinos/republica_dominicana/punta_cana/punta_cana2.png',
    'imagenes/destinos/republica_dominicana/punta_cana/punta_cana3.png',
    'imagenes/destinos/republica_dominicana/punta_cana/punta_cana4.png'
];

// Crear slides dinámicamente para República Dominicana
function createDominicanaCarouselSlides() {
    const carousel = document.getElementById('dominicanaCarousel');
    const indicatorsContainer = document.getElementById('dominicanaIndicators');
    
    if (!carousel || !indicatorsContainer) return;
    
    // Limpiar contenido existente
    carousel.innerHTML = '';
    indicatorsContainer.innerHTML = '';
    
    // Crear slides
    dominicanaImages.forEach((imagePath, index) => {
        // Crear slide
        const slide = document.createElement('div');
        slide.className = `carousel-slide ${index === 0 ? 'active' : ''}`;
        slide.style.backgroundImage = `url('${imagePath}')`;
        
        // Crear overlay
        const overlay = document.createElement('div');
        overlay.className = 'carousel-overlay';
        slide.appendChild(overlay);
        
        carousel.appendChild(slide);
        
        // Crear indicador
        const indicator = document.createElement('span');
        indicator.className = `indicator ${index === 0 ? 'active' : ''}`;
        indicator.onclick = () => currentSlideDom(index + 1);
        indicatorsContainer.appendChild(indicator);
    });
    
    // Actualizar referencias
    domSlides = document.querySelectorAll('#dominicanaCarousel .carousel-slide');
    domIndicators = document.querySelectorAll('#dominicanaIndicators .indicator');
}

// Inicializar carrusel de República Dominicana
function initDominicanaCarousel() {
    createDominicanaCarouselSlides();
    
    if (domSlides.length === 0) return;
    
    showSlideDom(0);
    startAutoSlideDom();
    
    // Pausar auto-slide al hacer hover
    const carousel = document.querySelector('#dominicanaCarousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoSlideDom);
        carousel.addEventListener('mouseleave', startAutoSlideDom);
    }
}

// Mostrar slide específico de República Dominicana
function showSlideDom(index) {
    // Remover clase active de todos los slides e indicadores
    domSlides.forEach(slide => slide.classList.remove('active'));
    domIndicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Agregar clase active al slide e indicador actual
    if (domSlides[index]) {
        domSlides[index].classList.add('active');
    }
    if (domIndicators[index]) {
        domIndicators[index].classList.add('active');
    }
}

// Cambiar slide de República Dominicana
function changeSlideDom(direction) {
    currentDomSlideIndex += direction;
    
    if (currentDomSlideIndex >= domSlides.length) {
        currentDomSlideIndex = 0;
    } else if (currentDomSlideIndex < 0) {
        currentDomSlideIndex = domSlides.length - 1;
    }
    
    showSlideDom(currentDomSlideIndex);
}

// Ir a slide específico de República Dominicana
function currentSlideDom(slideNumber) {
    currentDomSlideIndex = slideNumber - 1;
    showSlideDom(currentDomSlideIndex);
}

// Iniciar auto-slide de República Dominicana
function startAutoSlideDom() {
    domSlideInterval = setInterval(() => {
        changeSlideDom(1);
    }, 5000);
}

// Detener auto-slide de República Dominicana
function stopAutoSlideDom() {
    clearInterval(domSlideInterval);
}

// Funcionalidad del Carrusel de Colombia
let currentColSlideIndex = 0;
let colSlides = [];
let colIndicators = [];
let colSlideInterval;

// Lista de imágenes de Colombia (Guatapé)
const colombiaImages = [
    'imagenes/destinos/colombia/antioquia/guatape/guatape1.png',
    'imagenes/destinos/colombia/antioquia/guatape/guatape2.png',
    'imagenes/destinos/colombia/antioquia/guatape/guatape3.png',
    'imagenes/destinos/colombia/antioquia/guatape/penol1.png'
];

// Crear slides dinámicamente para Colombia
function createColombiaCarouselSlides() {
    const carousel = document.getElementById('colombiaCarousel');
    const indicatorsContainer = document.getElementById('colombiaIndicators');
    
    if (!carousel || !indicatorsContainer) return;
    
    // Limpiar contenido existente
    carousel.innerHTML = '';
    indicatorsContainer.innerHTML = '';
    
    // Crear slides
    colombiaImages.forEach((imagePath, index) => {
        // Crear slide
        const slide = document.createElement('div');
        slide.className = `carousel-slide ${index === 0 ? 'active' : ''}`;
        slide.style.backgroundImage = `url('${imagePath}')`;
        
        // Crear overlay
        const overlay = document.createElement('div');
        overlay.className = 'carousel-overlay';
        slide.appendChild(overlay);
        
        carousel.appendChild(slide);
        
        // Crear indicador
        const indicator = document.createElement('span');
        indicator.className = `indicator ${index === 0 ? 'active' : ''}`;
        indicator.onclick = () => currentSlideCol(index + 1);
        indicatorsContainer.appendChild(indicator);
    });
    
    // Actualizar referencias
    colSlides = document.querySelectorAll('#colombiaCarousel .carousel-slide');
    colIndicators = document.querySelectorAll('#colombiaIndicators .indicator');
}

// Inicializar carrusel de Colombia
function initColombiaCarousel() {
    createColombiaCarouselSlides();
    
    if (colSlides.length === 0) return;
    
    showSlideCol(0);
    startAutoSlideCol();
    
    // Pausar auto-slide al hacer hover
    const carousel = document.querySelector('#colombiaCarousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoSlideCol);
        carousel.addEventListener('mouseleave', startAutoSlideCol);
    }
}

// Mostrar slide específico de Colombia
function showSlideCol(index) {
    // Remover clase active de todos los slides e indicadores
    colSlides.forEach(slide => slide.classList.remove('active'));
    colIndicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Agregar clase active al slide e indicador actual
    if (colSlides[index]) {
        colSlides[index].classList.add('active');
    }
    if (colIndicators[index]) {
        colIndicators[index].classList.add('active');
    }
}

// Cambiar slide de Colombia
function changeSlideCol(direction) {
    currentColSlideIndex += direction;
    
    if (currentColSlideIndex >= colSlides.length) {
        currentColSlideIndex = 0;
    } else if (currentColSlideIndex < 0) {
        currentColSlideIndex = colSlides.length - 1;
    }
    
    showSlideCol(currentColSlideIndex);
}

// Ir a slide específico de Colombia
function currentSlideCol(slideNumber) {
    currentColSlideIndex = slideNumber - 1;
    showSlideCol(currentColSlideIndex);
}

// Iniciar auto-slide de Colombia
function startAutoSlideCol() {
    colSlideInterval = setInterval(() => {
        changeSlideCol(1);
    }, 5000);
}

// Detener auto-slide de Colombia
function stopAutoSlideCol() {
    clearInterval(colSlideInterval);
}

// Funcionalidad del Login
function initLogin() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    // Inicializar botones de redes sociales
    const googleBtn = document.querySelector('.btn-google');
    const facebookBtn = document.querySelector('.btn-facebook');
    
    if (googleBtn) {
        googleBtn.addEventListener('click', handleGoogleLogin);
    }
    
    if (facebookBtn) {
        facebookBtn.addEventListener('click', handleFacebookLogin);
    }
}

// Manejar el envío del formulario de login
function handleLogin(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;
    
    // Validaciones básicas
    if (!email || !password) {
        showNotification('Por favor, completa todos los campos', 'error');
        return;
    }
    
    if (!isValidEmail(email)) {
        showNotification('Por favor, ingresa un email válido', 'error');
        return;
    }
    
    // El formulario se enviará al servidor PHP para procesamiento
    // No necesitamos JavaScript adicional ya que PHP maneja la autenticación
    showNotification('Iniciando sesión...', 'info');
}

// Validar formato de email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Toggle de visibilidad de contraseña
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Manejar login con Google
function handleGoogleLogin() {
    showNotification('Iniciando sesión con Google...', 'info');
    // En un proyecto real, aquí se integraría con la API de Google
    setTimeout(() => {
        showNotification('Funcionalidad de Google en desarrollo', 'info');
    }, 1000);
}

// Manejar login con Facebook
function handleFacebookLogin() {
    showNotification('Iniciando sesión con Facebook...', 'info');
    // En un proyecto real, aquí se integraría con la API de Facebook
    setTimeout(() => {
        showNotification('Funcionalidad de Facebook en desarrollo', 'info');
    }, 1000);
}

// Verificar si el usuario está logueado
function checkUserSession() {
    // En PHP, la sesión se maneja del lado del servidor
    // Esta función se mantiene para compatibilidad con funcionalidades del frontend
    return false;
}

// Actualizar navbar para usuario logueado
function updateNavbarForLoggedUser(userData) {
    const loginBtn = document.querySelector('.login-btn');
    if (loginBtn) {
        loginBtn.innerHTML = `
            <i class="fas fa-user"></i>
            ${userData.name}
        `;
        loginBtn.href = '#';
        loginBtn.onclick = showUserMenu;
    }
}

// Mostrar menú de usuario
function showUserMenu(e) {
    e.preventDefault();
    
    // Crear menú desplegable
    const existingMenu = document.querySelector('.user-menu');
    if (existingMenu) {
        existingMenu.remove();
        return;
    }
    
    const userMenu = document.createElement('div');
    userMenu.className = 'user-menu';
    userMenu.innerHTML = `
        <div class="user-menu-content">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Usuario Admin</span>
            </div>
            <div class="user-menu-items">
                <a href="#" class="user-menu-item">
                    <i class="fas fa-user"></i>
                    Mi Perfil
                </a>
                <a href="#" class="user-menu-item">
                    <i class="fas fa-calendar"></i>
                    Mis Reservas
                </a>
                <a href="#" class="user-menu-item">
                    <i class="fas fa-cog"></i>
                    Configuración
                </a>
                <hr>
                <a href="#" class="user-menu-item logout" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </div>
    `;
    
    // Posicionar el menú
    const loginBtn = document.querySelector('.login-btn');
    loginBtn.style.position = 'relative';
    loginBtn.appendChild(userMenu);
    
    // Cerrar menú al hacer clic fuera
    setTimeout(() => {
        document.addEventListener('click', function closeMenu(e) {
            if (!loginBtn.contains(e.target)) {
                userMenu.remove();
                document.removeEventListener('click', closeMenu);
            }
        });
    }, 100);
}

// Cerrar sesión
function logout() {
    localStorage.removeItem('userSession');
    sessionStorage.removeItem('userSession');
    
    showNotification('Sesión cerrada correctamente', 'success');
    
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Funcionalidad para la página de Detalle de Producto
function initProductDetail() {
    // Cargar datos del producto desde URL o localStorage
    loadProductData();
    
    // Configurar fecha mínima para el input de fecha
    setupDateInput();
    
    // Calcular precio inicial
    calculateTotal();
    
    // Inicializar video
    initVideo();
    
    // Agregar event listeners
    setupProductDetailEvents();
}

// Cargar datos del producto
function loadProductData() {
    // Obtener ID del producto desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (productId) {
        // Buscar producto en localStorage
        const productos = JSON.parse(localStorage.getItem('productos')) || [];
        const producto = productos.find(p => p.id == productId);
        
        if (producto) {
            updateProductDisplay(producto);
        } else {
            // Producto no encontrado, usar datos por defecto
            showNotification('Producto no encontrado, mostrando información de ejemplo', 'warning');
        }
    }
}

// Actualizar la visualización del producto
function updateProductDisplay(producto) {
    // Actualizar breadcrumb
    const breadcrumb = document.getElementById('productBreadcrumb');
    if (breadcrumb) {
        breadcrumb.textContent = producto.nombre;
    }
    
    // Actualizar título
    const title = document.getElementById('productTitle');
    if (title) {
        title.textContent = producto.nombre;
    }
    
    // Actualizar descripción
    const description = document.getElementById('productDescription');
    if (description) {
        description.textContent = producto.descripcion || 'Descripción del producto no disponible.';
    }
    
    // Actualizar precio
    const price = document.getElementById('productPrice');
    if (price) {
        price.textContent = producto.precio.toLocaleString();
    }
    
    // Actualizar badge según el tipo
    const badge = document.getElementById('productBadge');
    if (badge) {
        const badgeText = {
            'aventura': 'Aventura',
            'relax': 'Relax',
            'cultural': 'Cultural',
            'gastronomico': 'Gastronómico'
        };
        badge.textContent = badgeText[producto.tipo] || 'Paquete Premium';
    }
    
    // Recalcular total
    calculateTotal();
}

// Configurar input de fecha
function setupDateInput() {
    const dateInput = document.getElementById('departureDate');
    if (dateInput) {
        // Establecer fecha mínima como mañana
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
        
        // Establecer fecha por defecto en 7 días
        const defaultDate = new Date();
        defaultDate.setDate(defaultDate.getDate() + 7);
        dateInput.value = defaultDate.toISOString().split('T')[0];
    }
}

// Cambiar cantidad de personas
function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        let currentValue = parseInt(quantityInput.value);
        let newValue = currentValue + delta;
        
        // Limitar entre 1 y 10
        newValue = Math.max(1, Math.min(10, newValue));
        quantityInput.value = newValue;
        
        // Recalcular total
        calculateTotal();
    }
}

// Calcular precio total
function calculateTotal() {
    const priceElement = document.getElementById('productPrice');
    const quantityElement = document.getElementById('quantity');
    const roomTypeElement = document.getElementById('roomType');
    
    if (priceElement && quantityElement && roomTypeElement) {
        const basePrice = parseInt(priceElement.textContent.replace(/[^\d]/g, ''));
        const quantity = parseInt(quantityElement.value);
        const roomType = roomTypeElement.value;
        
        // Multiplicadores por tipo de habitación
        const roomMultipliers = {
            'standard': 1.0,
            'superior': 1.2,
            'deluxe': 1.5
        };
        
        const multiplier = roomMultipliers[roomType] || 1.0;
        const subtotal = basePrice * quantity * multiplier;
        const taxes = subtotal * 0.1; // 10% de impuestos
        const total = subtotal + taxes;
        
        // Actualizar elementos
        const subtotalElement = document.getElementById('subtotal');
        const taxesElement = document.getElementById('taxes');
        const totalElement = document.getElementById('totalPrice');
        
        if (subtotalElement) subtotalElement.textContent = `$${subtotal.toLocaleString()}`;
        if (taxesElement) taxesElement.textContent = `$${taxes.toLocaleString()}`;
        if (totalElement) totalElement.textContent = `$${total.toLocaleString()}`;
    }
}

// Configurar event listeners
function setupProductDetailEvents() {
    // Cambio en cantidad
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', calculateTotal);
    }
    
    // Cambio en tipo de habitación
    const roomTypeSelect = document.getElementById('roomType');
    if (roomTypeSelect) {
        roomTypeSelect.addEventListener('change', calculateTotal);
    }
    
    // Cambio en fecha
    const dateInput = document.getElementById('departureDate');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            
            if (selectedDate <= today) {
                showNotification('Por favor selecciona una fecha futura', 'warning');
                this.value = '';
            }
        });
    }
}

// Inicializar video
function initVideo() {
    const video = document.querySelector('#videoPlayer video');
    if (video) {
        // Configurar eventos del video
        video.addEventListener('loadstart', function() {
            console.log('Iniciando carga del video...');
        });
        
        video.addEventListener('canplay', function() {
            console.log('Video listo para reproducir');
        });
        
        video.addEventListener('error', function() {
            showNotification('Error al cargar el video', 'error');
        });
        
        // Auto-play opcional (descomentado si quieres que se reproduzca automáticamente)
        // video.play();
    }
}

// Proceder a la reserva
function proceedToBooking() {
    const dateInput = document.getElementById('departureDate');
    const quantityInput = document.getElementById('quantity');
    const roomTypeSelect = document.getElementById('roomType');
    
    // Validaciones
    if (!dateInput.value) {
        showNotification('Por favor selecciona una fecha de salida', 'error');
        return;
    }
    
    if (!quantityInput.value || quantityInput.value < 1) {
        showNotification('Por favor selecciona el número de personas', 'error');
        return;
    }
    
    // Recopilar datos de la reserva
    const bookingData = {
        productId: new URLSearchParams(window.location.search).get('id'),
        date: dateInput.value,
        quantity: parseInt(quantityInput.value),
        roomType: roomTypeSelect.value,
        total: document.getElementById('totalPrice').textContent,
        timestamp: new Date().toISOString()
    };
    
    // Guardar en localStorage (en un proyecto real, esto se enviaría al servidor)
    const bookings = JSON.parse(localStorage.getItem('bookings')) || [];
    bookings.push(bookingData);
    localStorage.setItem('bookings', JSON.stringify(bookings));
    
    showNotification('¡Reserva realizada con éxito! Te contactaremos pronto.', 'success');
    
    // Redirigir después de 2 segundos
    setTimeout(() => {
        window.location.href = 'products.html';
    }, 2000);
}

// Funcionalidad del Carrusel
let currentSlideIndex = 0;
let slides = [];
let indicators = [];
let slideInterval;

// Lista de imágenes disponibles en la carpeta
const carouselImages = [
    'imagenes/destinos/index/buggie.png',
    'imagenes/destinos/index/buggie2.png',
    'imagenes/destinos/index/buggie3.png',
    'imagenes/destinos/index/cocobongo.png',
    'imagenes/destinos/index/dominicana1.png',
    'imagenes/destinos/index/dominicana2.png',
    'imagenes/destinos/index/dominicana3.png',
    'imagenes/destinos/index/guatape1.png',
    'imagenes/destinos/index/guatape2.png',
    'imagenes/destinos/index/guatape3.png',
    'imagenes/destinos/index/pareja_playa1.png',
    'imagenes/destinos/index/pareja_playa2.png',
    'imagenes/destinos/index/penol1.png',
    'imagenes/destinos/index/punta_cana1.png',
    'imagenes/destinos/index/punta_cana2.png',
    'imagenes/destinos/index/punta_cana3.png',
    'imagenes/destinos/index/punta_cana4.png'
];

// Crear slides dinámicamente
function createCarouselSlides() {
    const carousel = document.getElementById('heroCarousel');
    const indicatorsContainer = document.getElementById('carouselIndicators');
    
    if (!carousel || !indicatorsContainer) return;
    
    // Limpiar contenido existente
    carousel.innerHTML = '';
    indicatorsContainer.innerHTML = '';
    
    // Crear slides
    carouselImages.forEach((imagePath, index) => {
        // Crear slide
        const slide = document.createElement('div');
        slide.className = `carousel-slide ${index === 0 ? 'active' : ''}`;
        slide.style.backgroundImage = `url('${imagePath}')`;
        
        // Crear overlay
        const overlay = document.createElement('div');
        overlay.className = 'carousel-overlay';
        slide.appendChild(overlay);
        
        carousel.appendChild(slide);
        
        // Crear indicador
        const indicator = document.createElement('span');
        indicator.className = `indicator ${index === 0 ? 'active' : ''}`;
        indicator.onclick = () => currentSlide(index + 1);
        indicatorsContainer.appendChild(indicator);
    });
    
    // Actualizar referencias
    slides = document.querySelectorAll('.carousel-slide');
    indicators = document.querySelectorAll('.indicator');
}

// Inicializar carrusel
function initCarousel() {
    createCarouselSlides();
    
    if (slides.length === 0) return;
    
    showSlide(0);
    startAutoSlide();
    
    // Pausar auto-slide al hacer hover
    const carousel = document.querySelector('.hero-carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoSlide);
        carousel.addEventListener('mouseleave', startAutoSlide);
    }
}

// Mostrar slide específico
function showSlide(index) {
    // Remover clase active de todos los slides e indicadores
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Agregar clase active al slide e indicador actual
    if (slides[index]) {
        slides[index].classList.add('active');
    }
    if (indicators[index]) {
        indicators[index].classList.add('active');
    }
    
    currentSlideIndex = index;
}

// Cambiar slide (siguiente/anterior)
function changeSlide(direction) {
    let newIndex = currentSlideIndex + direction;
    
    if (newIndex >= slides.length) {
        newIndex = 0;
    } else if (newIndex < 0) {
        newIndex = slides.length - 1;
    }
    
    showSlide(newIndex);
}

// Ir a slide específico
function currentSlide(index) {
    showSlide(index - 1);
}

// Auto-slide
function startAutoSlide() {
    stopAutoSlide(); // Limpiar intervalo anterior
    slideInterval = setInterval(() => {
        changeSlide(1);
    }, 5000); // Cambiar cada 5 segundos
}

function stopAutoSlide() {
    if (slideInterval) {
        clearInterval(slideInterval);
    }
}

// Inicializar carrusel cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Detectar qué página estamos cargando
    const currentPage = window.location.pathname.split('/').pop();
    // Permitir carrusel en index.php y index.html
    if (currentPage === 'dominicana.html') {
        initDominicanaCarousel();
    } else if (currentPage === 'colombia.html') {
        initColombiaCarousel();
    } else if (currentPage === 'index.html' || currentPage === 'index.php' || currentPage === '') {
        initCarousel();
    } else if (currentPage === 'login.html' || currentPage === 'login.php') {
        initLogin();
    } else if (currentPage === 'product-detail.html') {
        initProductDetail();
    }
});

// Funciones específicas para admin.html
function limpiarProductos() {
    if (confirm('¿Está seguro de que desea eliminar TODOS los productos? Esta acción no se puede deshacer.')) {
        productos = [];
        saveProductos();
        showNotification('Todos los productos han sido eliminados', 'success');
        
        // Si estamos en products.html, recargar la página
        if (window.location.pathname.includes('products.html')) {
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    }
}

// Función para cargar productos (usada en products.html)
function cargarProductos() {
    productos = JSON.parse(localStorage.getItem('productos')) || [];
    if (typeof productosFiltrados !== 'undefined') {
        productosFiltrados = [...productos];
    }
}

// Función para editar producto (placeholder)
function editarProducto() {
    if (productoSeleccionado) {
        showNotification('Función de edición en desarrollo', 'info');
        // Aquí se podría implementar la edición
    }
}

// Función para eliminar producto desde modal
function eliminarProductoModal() {
    if (productoSeleccionado) {
        if (confirm(`¿Está seguro de que desea eliminar el producto "${productoSeleccionado.nombre}"?`)) {
            productos = productos.filter(p => p.id !== productoSeleccionado.id);
            saveProductos();
            showNotification('Producto eliminado exitosamente', 'success');
            cerrarModal();
            
            // Recargar productos si estamos en products.html
            if (window.location.pathname.includes('products.html')) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }
    }
}
