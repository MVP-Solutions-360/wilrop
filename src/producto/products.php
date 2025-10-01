<?php
// products.php - Vista completa de productos con los mismos estilos y contenido que products.html
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Wilrop Colombia Travel</title>
    <meta name="description" content="Catálogo de productos turísticos - Wilrop Colombia Travel">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-container">
                        <img src="/imagenes/logos/wilrop_vertical.png" alt="Wilrop Colombia Travel" class="logo-image">
                        <div class="logo-text">
                            <h6>Wilrop Colombia Travel</h6>
                        </div>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="/index.php" class="nav-link">Inicio</a></li>
                    <li><a href="/src/dominicana/dominicana.php" class="nav-link">República Dominicana</a></li>
                    <li><a href="/src/colombia/colombia.php" class="nav-link">Colombia</a></li>
                    <li><a href="/index.php#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="/src/producto/products.php" class="nav-link active">Productos</a></li>
                    <li><a href="/src/admin/admin.php" class="nav-link">Admin</a></li>
                    <li><a href="/index.php#contacto" class="nav-link">Contacto</a></li>
                    <li><a href="/src/admin/login.php" class="nav-link login-btn">Iniciar Sesión</a></li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
        <div class="mobile-menu-overlay"></div>
    </header>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Productos</h2>
                <p>Descubre nuestras ofertas turísticas entre República Dominicana y Antioquia</p>
            </div>
            <!-- Filtros -->
            <div class="filters-container">
                <div class="filters-header">
                    <h3><i class="fas fa-filter"></i> Filtrar Productos</h3>
                    <button onclick="limpiarFiltros()" class="btn btn-secondary btn-small">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
                <form id="filtrosForm" class="filters-form">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label for="filtroDestino">Destino:</label>
                            <select id="filtroDestino" name="filtroDestino">
                                <option value="">Todos los destinos</option>
                                <option value="republica-dominicana">República Dominicana</option>
                                <option value="antioquia">Antioquia, Colombia</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filtroTipo">Tipo:</label>
                            <select id="filtroTipo" name="filtroTipo">
                                <option value="">Todos los tipos</option>
                                <option value="paquete">Paquete Turístico</option>
                                <option value="vuelo">Vuelo</option>
                                <option value="hotel">Hospedaje</option>
                                <option value="tour">Tour/Excursión</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="precioMin">Precio Mínimo (USD):</label>
                            <input type="number" id="precioMin" name="precioMin" min="0" step="0.01" placeholder="0">
                        </div>
                        <div class="filter-group">
                            <label for="precioMax">Precio Máximo (USD):</label>
                            <input type="number" id="precioMax" name="precioMax" min="0" step="0.01" placeholder="10000">
                        </div>
                    </div>
                    <div class="filters-row">
                        <div class="filter-group">
                            <label for="duracionMin">Duración Mínima (días):</label>
                            <input type="number" id="duracionMin" name="duracionMin" min="1" placeholder="1">
                        </div>
                        <div class="filter-group">
                            <label for="duracionMax">Duración Máxima (días):</label>
                            <input type="number" id="duracionMax" name="duracionMax" min="1" placeholder="30">
                        </div>
                        <div class="filter-group">
                            <label for="buscarTexto">Buscar:</label>
                            <input type="text" id="buscarTexto" name="buscarTexto" placeholder="Nombre o descripción...">
                        </div>
                        <div class="filter-group">
                            <label for="ordenarPor">Ordenar por:</label>
                            <select id="ordenarPor" name="ordenarPor">
                                <option value="nombre">Nombre</option>
                                <option value="precio-asc">Precio (Menor a Mayor)</option>
                                <option value="precio-desc">Precio (Mayor a Menor)</option>
                                <option value="duracion-asc">Duración (Menor a Mayor)</option>
                                <option value="duracion-desc">Duración (Mayor a Menor)</option>
                                <option value="fecha-desc">Más Recientes</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Estadísticas -->
            <div class="stats-container">
                <div class="stat-card">
                    <i class="fas fa-box"></i>
                    <div>
                        <h4 id="totalProductos">0</h4>
                        <p>Productos Totales</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4 id="productosDominicana">0</h4>
                        <p>República Dominicana</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-mountain"></i>
                    <div>
                        <h4 id="productosAntioquia">0</h4>
                        <p>Antioquia, Colombia</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <div>
                        <h4 id="precioPromedio">$0</h4>
                        <p>Precio Promedio</p>
                    </div>
                </div>
            </div>
            <!-- Lista de Productos -->
            <div class="products-container">
                <div class="products-header">
                    <h3>Resultados</h3>
                    <div class="view-controls">
                        <button onclick="cambiarVista('grid')" class="view-btn active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button onclick="cambiarVista('list')" class="view-btn" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                <div id="productosLista" class="products-grid">
                    <!-- Los productos se cargarán dinámicamente aquí -->
                </div>
                <div id="sinResultados" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h3>No se encontraron productos</h3>
                    <p>Intenta ajustar los filtros o crear un nuevo producto.</p>
                    <a href="/src/admin/admin.php" class="btn btn-primary">Crear Producto</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal para ver detalles del producto -->
    <div id="productoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Detalles del Producto</h3>
                <span class="close" onclick="cerrarModal()">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button onclick="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                <button onclick="editarProducto()" class="btn btn-primary">Editar</button>
                <button onclick="eliminarProductoModal()" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
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
                        <li><a href="/index.php">Inicio</a></li>
                        <li><a href="/index.php#destinos">Destinos</a></li>
                        <li><a href="/index.php#servicios">Servicios</a></li>
                        <li><a href="/src/producto/products.php">Productos</a></li>
                        <li><a href="/src/admin/admin.php">Admin</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-phone"></i> +1 (809) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> info@wilropcolombia.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Wilrop Colombia Travel. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="/scripts.js"></script>
    <script>
        // ...código JS igual que products.html...
    </script>
</body>
</html>
