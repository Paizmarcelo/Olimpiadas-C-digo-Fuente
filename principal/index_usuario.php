<?php
/**
 * @author MangueAR Development Team
 * @version 4.0.0
 * @description Página principal de la agencia de viajes MangueAR.
 * Rediseñada con nuevas secciones, carruseles, y un enfoque dinámico.
 * Incorpora header y footer personalizados basados en imágenes.
 */

session_start();

// --- INICIALIZACIÓN Y PROTECCIÓN DE SESIONES ---
// Se asegura que las variables de sesión para carrito y favoritos existan.
// Estos se usarán en script.js para una persistencia básica en el navegador
// y podrían integrarse con PHP para una persistencia de sesión más robusta.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// *** LÓGICA DE PROTECCIÓN DE PÁGINA ***
// Si el usuario NO está logeado, O si su tipo de usuario NO es 'cliente',
// redirigirlo a la página de registro/login.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'cliente') {
    header("Location: ../index_registro.php"); // Redirige a la página de login (una carpeta arriba)
    exit; // Termina la ejecución del script
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangueAR - Tu Agencia de Turismo de Confianza</title>
    <link rel="stylesheet" href="Style_usuario.css"> <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-img"><img src="../Images/logomapache-removebg-preview.png" height="40px" width="40px"></div> <div>
                    <div class="brand-name">MangueAR</div>
                    <div class="tagline">Pagá menos por viajar</div>
                </div>
            </div>
            
             <nav class="nav-menu">
                <a href="hospedajes_usuario.php" class="nav-item active">
                    <i class="fa-solid fa-hotel nav-icon"></i>
                    <div class="nav-text">Hospedaje</div>
                </a>
                <a href="vuelos_usuario.php" class="nav-item">
                    <i class="fa-solid fa-plane nav-icon"></i>
                    <div class="nav-text">Vuelos</div>
                </a>
                <a href="index_usuario.php" class="nav-item"> <i class="fa-solid fa-box-archive nav-icon"></i>
                    <div class="nav-text">Paquetes</div>
                </a>
  <a href="../ofertas.php" class="nav-item">
                        <i class="fa-solid fa-tag nav-icon"></i>
                    <div class="nav-text">Ofertas</div>
                </a>
                <a href="../turismo_pers.php" class="nav-item">
                    <i class="fa-solid fa-compass nav-icon"></i>
                    <div class="nav-text">Turismo Personalizado</div>
                </a>
                <a href="../soporte_usuario.php" class="nav-item">
                    <i class="fa-solid fa-headset nav-icon"></i>
                    <div class="nav-text">Soporte</div>
                </a>
            </nav>
            
            <div class="user-section">
                <select class="currency-selector">
                    <option>AR (ARS)</option>
                    <option>US (USD)</option>
                </select>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <div class="header-right">
                    <div class="header-icon" id="favoriteIcon">
                        <i class="fas fa-heart"></i>
                        <span class="icon-count" id="favoriteCount">0</span>
                    </div>
                    <div class="header-icon" id="cartIcon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="icon-count" id="cartCount">0</span>
                    </div>
                    <div class="profile-menu">
                        <img src="../Images/perfil_usuario.png"  class="profile-picture">
                        <div class="profile-dropdown">
                            <a href="#">Idioma</a>
                            <a href="#">Ajustes</a>
                            <a href="#">Mi Perfil</a>
                            <a href="logout.php">Cerrar Sesión</a> 
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

<button id="chatbot-toggle-btn" class="chatbot-toggle-btn">💬</button>

    <div id="chatbot-container" class="chatbot-container">
        <div class="chatbot-header">
            <h3>MangueAR Asistente</h3>
            <span class="close-chatbot-btn">&times;</span>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                Hola! Soy tu asistente de MangueAR. ¿En qué puedo ayudarte hoy?
            </div>
            <div class="message bot-message">
                Puedes preguntar sobre:
                <ul>
                    <li>Vuelos</li>
                    <li>Paquetes</li>
                    <li>Soporte al cliente</li>
                    <li>Reservas</li>
                    <li>Cambios de pasajes</li>
                    <li>Métodos de pago</li>
                </ul>
            </div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbot-input-field" placeholder="Escribe tu pregunta o elige una opción..." autocomplete="off">
            <button id="chatbot-send-btn">Enviar</button>
        </div>
        <div class="chatbot-menu" id="chatbot-menu">
            <button class="menu-option" data-query="Vuelos">Vuelos</button>
            <button class="menu-option" data-query="Paquetes">Paquetes</button>
            <button class="menu-option" data-query="Soporte al cliente">Soporte al cliente</button>
            <button class="menu-option" data-query="Reservas">Reservas</button>
            <button class="menu-option" data-query="Cambios de pasajes">Cambios de pasajes</button>
            <button class="menu-option" data-query="Métodos de pago">Métodos de pago</button>
            <button class="menu-option" data-query="Preguntas frecuentes">Preguntas frecuentes</button>
            <button class="menu-option" data-query="Hablar con un agente">Hablar con un agente</button>
        </div>
    </div>




    <section id="home" class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title"></h1> <p class="hero-subtitle"></p> <div class="hero-buttons">
                <a href="#paquetes" class="btn btn-primary">Explorar Paquetes</a>
                <a href="#vuelos" class="btn btn-secondary">Buscar Vuelos</a>
            </div>
        </div>
    </section>

    <section id="paquetes" class="section packages-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Paquetes Turísticos</h2>
                <p class="section-subtitle">Experiencias completas y a medida para que solo te preocupes por disfrutar</p>
            </div>
            <div class="paquetes-grid" id="paquetes-grid-container">
                </div>
            <div class="view-more-button">
                <button class="btn btn-secondary" id="viewMorePackages">Ver más paquetes</button>
            </div>
        </div>
    </section>

    <section id="vuelos" class="section flights-section">
        <div class="section-header">
            <h2 class="section-title">Encuentra Tu Vuelo Perfecto</h2>
            <p class="section-subtitle">Descubre ofertas exclusivas a destinos de ensueño</p>
        </div>
        <div class="flights-carousel-container">
            <div class="flights-carousel swiper">
                <div class="swiper-wrapper">
                    </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section id="why-us" class="section why-us-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">¿Por Qué Elegir MangueAR?</h2>
                <p class="section-subtitle">Somos tu compañero de viaje ideal, comprometidos con tu experiencia.</p>
            </div>
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-hand-holding-usd fa-4x feature-icon"></i>
                    <h3>Precios Competitivos</h3>
                    <p>Las mejores tarifas garantizadas, sin sacrificar la calidad y el servicio.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset fa-4x feature-icon"></i>
                    <h3>Soporte 24/7</h3>
                    <p>Asistencia continua antes, durante y después de tu aventura, siempre a tu disposición.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt fa-4x feature-icon"></i>
                    <h3>Viajes Seguros</h3>
                    <p>Priorizamos tu seguridad con seguros de viaje robustos y alianzas de confianza.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-route fa-4x feature-icon"></i>
                    <h3>Destinos Únicos</h3>
                    <p>Accede a experiencias exclusivas y lugares que te dejarán sin aliento.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-map-marked-alt fa-4x feature-icon"></i>
                    <h3>Personalización</h3>
                    <p>Diseñamos viajes a tu medida, adaptándonos a tus preferencias y presupuesto.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-lightbulb fa-4x feature-icon"></i>
                    <h3>Innovación</h3>
                    <p>Constantemente buscamos las últimas tendencias y tecnologías para tu mejor viaje.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="reviews" class="section  reviews-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" >Lo que Dicen Nuestros Viajeros</h2>
                <p class="section-subtitle">Historias reales de aventuras inolvidables con MangueAR.</p>
            </div>
            <div class="reviews-carousel swiper">
                <div class="swiper-wrapper">
                    </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section id="faq" class="section faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Preguntas Frecuentes</h2>
                <p class="section-subtitle">Resolvemos tus dudas más comunes antes de tu viaje.</p>
            </div>
            <div class="faq-container">
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Cómo puedo reservar un paquete?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Puedes reservar directamente desde nuestro sitio web seleccionando el paquete que te interese y siguiendo los pasos de compra. Si necesitas asistencia, nuestro equipo de soporte está disponible para ayudarte.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Qué métodos de pago aceptan?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Aceptamos una amplia variedad de métodos de pago, incluyendo tarjetas de crédito (Visa, Mastercard, American Express), transferencias bancarias y plataformas de pago online como Mercado Pago. Contáctanos para otras opciones.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Puedo cancelar o modificar mi reserva?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Las políticas de cancelación y modificación varían según el paquete y el proveedor. Te recomendamos revisar los términos y condiciones específicos de cada reserva. Para cualquier gestión, contáctanos lo antes posible.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Ofrecen seguros de viaje?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Sí, ofrecemos diversas opciones de seguros de viaje adaptados a tus necesidades. Puedes agregarlos durante el proceso de reserva o contactarnos para que te asesoremos sobre la mejor cobertura.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Qué documentos necesito para viajar?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Los documentos necesarios (pasaporte, visa, vacunas, etc.) varían según tu nacionalidad y el destino. Te proporcionaremos información detallada al momento de la reserva, pero siempre es recomendable verificar con las autoridades pertinentes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


   <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre ManqueAR</h3>
                <ul>
                    <li><a href="#">Quiénes somos</a></li>
                    <li><a href="#">Nuestra historia</a></li>
                    <li><a href="#">Trabajá con nosotros</a></li>
                    <li><a href="#">Prensa</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Ayuda</h3>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Centro de ayuda</a></li>
                    <li><a href="#">Políticas de privacidad</a></li>
                    <li><a href="#">Términos y condiciones</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contactanos</h3>
                <ul>
                    <li><a href="#">Email: info@manquear.com</a></li>
                    <li><a href="#">Teléfono: +54 9 11 1234 5678</a></li>
                    <li><a href="#">Horario: Lunes a Viernes 9-18 hs</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="footer-social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom-text">
            &copy; 2025 ManqueAR. Todos los derechos reservados.
        </div>
    </footer>

    <div id="favoriteModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Mis Paquetes Favoritos</h2>
            <div id="favoriteItemsContainer" class="modal-items-container">
                <p class="empty-message">Aún no tienes favoritos. ¡Explora y añade algunos!</p>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary close-modal-btn">Seguir Mirando</button>
                <button class="btn btn-danger" id="clearFavoritesBtn">Vaciar Favoritos</button>
            </div>
        </div>
    </div>

    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Mi Carrito de Compras</h2>
            <div id="cartItemsContainer" class="modal-items-container">
                <p class="empty-message">Tu carrito está vacío. ¡Agrega tus próximos viajes!</p>
            </div>
            <div class="modal-summary">
                <h3 id="cartTotal">Total: $0</h3>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary close-modal-btn">Seguir Comprando</button>
                <button class="btn btn-danger" id="clearCartBtn">Vaciar Carrito</button>
                <button class="btn btn-primary" id="checkoutBtn">Comprar</button>
            </div>
        </div>
    </div>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="script_usuario.js"></script>

</body>
</html>