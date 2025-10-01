<?php
session_start();
include 'src/admin/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilrop Colombia Travel - Turismo República Dominicana y Antioquia</title>
    <meta name="description" content="Wilrop Colombia Travel - Agencia especializada en turismo entre República Dominicana y Antioquia, Colombia. Descubre destinos únicos con nosotros.">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-container">
                        <img src="imagenes/logos/wilrop_vertical.png" alt="Wilrop Colombia Travel" class="logo-image">
                        <div class="logo-text">
                            <h6>Wilrop Colombia Travel</h6>
                        </div>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">Inicio</a></li>
                    <li><a href="src/dominicana/dominicana.php" class="nav-link">República Dominicana</a></li>
                    <li><a href="src/colombia/colombia.php" class="nav-link">Colombia</a></li>
                    <li><a href="#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="products.html" class="nav-link">Productos</a></li>
                    <?php if (isset($_SESSION['active'])): ?>
                        <li><a href="src/admin/admin.php" class="nav-link">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="#contacto" class="nav-link">Contacto</a></li>
                    <?php if (isset($_SESSION['active'])): ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link login-btn dropdown-toggle" id="userDropdown" data-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="profile.php">Mi Perfil</a>
                                <a class="dropdown-item" href="my-bookings.php">Mis Reservas</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="src/login_register/login.php" class="nav-link login-btn">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
        <!-- Overlay para menú móvil -->
        <div class="mobile-menu-overlay"></div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <!-- Carrusel de Imágenes -->
        <div class="hero-carousel" id="heroCarousel">
            <!-- Las imágenes se cargarán dinámicamente aquí -->
        </div>
        
        <!-- Controles del Carrusel -->
        <div class="carousel-controls">
            <button class="carousel-btn prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-btn next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Indicadores -->
        <div class="carousel-indicators" id="carouselIndicators">
            <!-- Los indicadores se generarán dinámicamente aquí -->
        </div>
        
        <div class="hero-content">
            <div class="hero-text">
                <h2>Descubre la Magia del Caribe y los Andes</h2>
                <p>Conectamos República Dominicana con Colombia, ofreciendo experiencias turísticas únicas e inolvidables.</p>
                <div class="hero-buttons">
                    <a href="#destinos" class="btn btn-primary">Explorar Destinos</a>
                    <a href="#contacto" class="btn btn-secondary">Contactar</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinos Section -->
    <section id="destinos" class="destinos">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Destinos</h2>
                <p>Especialistas en turismo entre República Dominicana y Antioquia</p>
            </div>
            <div class="destinos-grid">
                <a href="src/dominicana/dominicana.php" class="destino-card" style="text-decoration:none; color:inherit;">
                    <div class="destino-image">
                        <img src="imagenes/destinos/republica_dominicana/punta_cana/punta_cana3.png" alt="Punta Cana">
                    </div>
                    <div class="destino-content">
                        <h3>República Dominicana</h3>
                        <p>Playas paradisíacas, cultura vibrante y hospitalidad caribeña. Descubre la magia del Caribe con nosotros.</p>
                        <ul>
                            <li>Punta Cana</li>
                            <li>Santo Domingo</li>
                            <li>Puerto Plata</li>
                            <li>Samaná</li>
                        </ul>
                    </div>
                </a>
                <a href="src/colombia/colombia.php" class="destino-card" style="text-decoration:none; color:inherit;">
                    <div class="destino-image">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div class="destino-content">
                        <h3>Colombia</h3>
                        <p>Montañas majestuosas, café de calidad mundial y paisajes únicos. Vive la experiencia colombiana.</p>
                        <ul>
                            <li>Medellín</li>
                            <li>Santa Marta</li>
                            <li>Cartagena</li>
                            <li>San Andrés</li>
                        </ul>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="servicios">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Servicios</h2>
                <p>Ofrecemos una experiencia completa de viaje</p>
            </div>
            <div class="servicios-grid">
                <div class="servicio-card">
                    <i class="fas fa-plane"></i>
                    <h3>Vuelos</h3>
                    <p>Reservas de vuelos entre República Dominicana y Colombia con las mejores aerolíneas.</p>
                </div>
                <div class="servicio-card">
                    <i class="fas fa-bed"></i>
                    <h3>Hospedaje</h3>
                    <p>Hoteles y alojamientos seleccionados para garantizar tu comodidad y satisfacción.</p>
                </div>
                <div class="servicio-card">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Tours</h3>
                    <p>Excursiones guiadas por los mejores destinos turísticos de ambos países.</p>
                </div>
                <div class="servicio-card">
                    <i class="fas fa-concierge-bell"></i>
                    <h3>Asistencia 24/7</h3>
                    <p>Soporte completo durante tu viaje para resolver cualquier inconveniente.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="contacto">
        <div class="container">
            <div class="section-header">
                <h2>Contacto</h2>
                <p>Estamos aquí para ayudarte a planificar tu viaje perfecto</p>
                <p class="whatsapp-info">
                    <i class="fab fa-whatsapp"></i> 
                    Los mensajes se envían directamente a nuestro WhatsApp para una respuesta más rápida
                </p>
            </div>
            <div class="contacto-content">
                <div class="contacto-form-wrapper">
                    <form id="contactForm" class="contact-form">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo:</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono">
                        </div>
                        <div class="form-group">
                            <label for="destinoInteres">Destino de Interés:</label>
                            <select id="destinoInteres" name="destinoInteres">
                                <option value="">Seleccionar destino</option>
                                <option value="republica-dominicana">República Dominicana</option>
                                <option value="antioquia">Antioquia, Colombia</option>
                                <option value="ambos">Ambos destinos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje:</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fab fa-whatsapp"></i> Enviar por WhatsApp
                        </button>
                    </form>
                </div>
                <div class="contacto-info-grid">
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Teléfono</h4>
                            <p>+1 (829) 794-9960</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@wilropcolombia.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Ubicación</h4>
                            <a href="https://maps.app.goo.gl/RAmgwxr4waLiwnqH7" target="_blank" class="map-link">Medellín, Colombia</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Wilrop Colombia Travel</h3>
                    <p>Especialistas en turismo entre República Dominicana y Antioquia, Colombia. Tu agencia de confianza para experiencias únicas.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#destinos">Destinos</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Destinos</h4>
                    <ul>
                        <li>República Dominicana</li>
                        <li>Antioquia, Colombia</li>
                        <li>Punta Cana</li>
                        <li>Medellín</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-phone"></i> + 1 (829) 794-9960</p>
                    <p><i class="fas fa-envelope"></i> info@wilropcolombia.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Wilrop Colombia Travel. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
