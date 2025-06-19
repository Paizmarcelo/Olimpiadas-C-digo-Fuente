<?php
/**
 * @author MangueAR Development Team
 * @version 4.0.0
 * @description Página principal de la agencia de viajes MangueAR para usuarios no registrados.
 * Muestra el contenido público y opciones para Iniciar Sesión/Registrarse.
 */

session_start();

// Si el usuario ya está logeado como 'cliente', redirigir al dashboard de usuario
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente') {
    header("Location: principal/index_usuario.php"); // Redirige al dashboard del usuario
    exit;
}

// Inicialización de variables de sesión para carrito/favoritos (aunque no se usarán directamente en esta página,
// se mantienen por si se decide agregar funcionalidad de carrito temporal para no logeados en el futuro)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangueAR - Tu Agencia de Turismo de Confianza</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="Images/Logo_icon.png" type="image/x-icon">
    <style>
        /* ==========================================================================
           1. CONFIGURACIÓN INICIAL: VARIABLES, RESETS Y ESTILOS GLOBALES
           ========================================================================== */
        :root {
            --color-primary:rgb(16, 74, 51); /* Azul vibrante */
            --color-secondary:rgb(4, 38, 112); /* Gris secundario */
            --color-success: #28a745; /* Verde éxito */
            --color-info: #17a2b8; /* Azul claro info */
            --color-warning: #ffc107; /* Amarillo advertencia */
            --color-danger: #dc3545; /* Rojo peligro */
            --color-light: #f8f9fa; /* Gris muy claro */
            --color-dark: #343a40; /* Gris oscuro */
            --color-white:rgb(255, 255, 255);
            --color-black: #000000;
            --color-grey-light: #e9ecef;
            --color-grey-dark: #6c757d;
            --color-border: #dee2e6;

            --color-petrol-green: #005047;
            --color-forest-green: #1a5a40; /* Un verde más bosque */

            --font-family-base: 'Arial', sans-serif;
            --font-size-base: 1rem;
            --line-height-base: 1.5;

            --spacing-sm: 10px;
            --spacing-md: 20px;
            --spacing-lg: 30px;

            --border-radius-base: 0.25rem;
            --box-shadow-base: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --box-shadow-light: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);

            --transition-speed: 0.3s ease;
        }


        /* Reset Básico y Estilos Globales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-poppins);
            line-height: 1.6;
            color: var(--color-black);
            background-color: var(--color-white);
            overflow-x: hidden; /* Evita el scroll horizontal */
        }

        a {
            text-decoration: none;
            color: var(--color-primary);
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--color-primary-dark);
        }

        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Contenedor principal para centrar el contenido */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Botones Globales */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 50px; /* Bordes redondeados para un aspecto moderno */
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-white);
            box-shadow: var(--shadow-light);
        }

        .btn-primary:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-secondary {
            background-color: var(--color-secondary);
            color: var(--color-white);
            box-shadow: var(--shadow-light);
        }

        .btn-secondary:hover {
            background-color: var(--color-secondary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-outline-primary {
            background-color: transparent;
            color: var(--color-primary);
            border: 2px solid var(--color-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary);
            color: var(--color-white);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--color-danger);
            color: var(--color-white);
        }

        .btn-danger:hover {
            background-color: #c82333; /* Un tono más oscuro de rojo */
        }

        /* Títulos de Sección */
        .section-title {
            font-family: var(--font-montserrat);
            font-size: 2.8rem;
            color: var(--color-primary);
            text-align: center;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--color-grey-dark);
            text-align: center;
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

          .section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-header .section-title {
    font-size: 2.8rem;
    color: var(--color-black);
    margin-bottom: 10px;
    position: relative;
    padding-bottom: 15px;
}

.section-header .section-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background-color: var(--color-primary);
    margin: 10px auto 0;
    border-radius: 2px;
}

.section-header .section-subtitle {
    font-size: 1.2rem;
    color: var(--color-grey-dark);
    max-width: 700px;
    margin: 0 auto;
}

/* ==========================================================================
   2. HEADER (Basado en header.png)
   ========================================================================== */
/* Header */
        .header {
            background-color: #005047; /* Verde Petróleo/Bosque */
            color: white;
            padding: 0.8rem 0;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            margin: 0;
        }
        
        .header-content {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 35px; /* Tamaño del logo */
            height: 35px;
            background-color: #ffffff; /* Un verde más claro para el logo */
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            border-radius: 50%;
            color: white;
            flex-shrink: 0; /* Evita que se encoja en pantallas pequeñas */
        }
        
        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
            white-space: nowrap; /* Evita que el nombre se rompa */
        }
        
        .tagline {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 1px;
        }
        
        .nav-menu {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap; /* Permite que los elementos se envuelvan */
            justify-content: center;
            flex-grow: 1; /* Permite que ocupe espacio disponible */
            margin: 0 1rem; /* Espacio a los lados */
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.7rem 1.2rem;
            border-radius: 6px;
            transition: background-color 0.2s, color 0.2s;
            white-space: nowrap; /* Evita que el texto se rompa */
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .nav-item.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-icon {
            font-size: 18px;
            margin-bottom: 3px;
        }
        
        .nav-text {
            font-size: 13px;
            font-weight: 500;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative; /* Para el menú desplegable */
        }
        
        .currency-selector {
            background-color: rgba(27, 90, 29, 0.2);
            border: 1px solid rgba(0, 0, 0, 0.3);
            color: white;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
            font-size: 13px;
            appearance: none;
            cursor: pointer;
        }

        .user-icons {
            display: flex;
            gap: 0.7rem;
        }

        .icon-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: white;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .icon-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* ==========================================================================
           3. HERO SECTION
           ========================================================================== */
        .hero-section {
            background: url('Images/pexels-asadphoto-1450360.jpg') no-repeat center center/cover;
            color: var(--color-white);
            text-align: center;
            padding: 120px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 70vh; /* Ajustado para mejor visibilidad */
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Capa oscura para mejor contraste del texto */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-family: var(--font-montserrat);
            font-size: 4rem;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
            animation: fadeInDown 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.3s;
            animation-fill-mode: both;
        }

        .hero-buttons .btn {
            margin: 0 10px;
            animation: fadeInUp 1s ease-out 0.6s;
            animation-fill-mode: both;
        }

        /* Animaciones */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==========================================================================
           4. SECCIONES DE CONTENIDO GENERAL
           ========================================================================== */
        .section {
            padding: var(--padding-section);
            background-color: var(--color-white);
        }

        .section:nth-of-type(even) { /* Para alternar colores de fondo */
            background-color: var(--color-grey-light);
        }

        /* ==========================================================================
           5. PAQUETES POPULARES
           ========================================================================== */
        .packages-section .container {
            text-align: center;
        }

        .paquetes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .package-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-medium);
        }

        .package-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .package-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .package-card:hover .package-image {
            transform: scale(1.05);
        }

        .package-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .package-title {
            font-family: var(--font-montserrat);
            font-size: 1.5rem;
            color: var(--color-primary);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .package-description {
            font-size: 0.95rem;
            color: var(--color-grey-dark);
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .package-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--color-secondary);
            margin-bottom: 15px;
        }

        .package-rating {
            color: var(--color-warning); /* Color para las estrellas */
            margin-bottom: 10px;
        }

        .package-card .btn {
            width: 100%;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            margin-top: auto; /* Empuja el botón hacia abajo */
        }
        .favorite-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            z-index: 10;
        }

        .favorite-btn i {
            color: var(--color-grey-dark);
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .favorite-btn.active i {
            color: var(--color-danger); /* Color cuando es favorito */
        }

        .favorite-btn:hover {
            background-color: var(--color-white);
            transform: scale(1.1);
        }

        /* ==========================================================================
           6. CARACTERÍSTICAS / POR QUÉ ELEGIRNOS
           ========================================================================== */
        .features-section {
            background-color: var(--color-white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 50px;
            text-align: center;
        }

        .feature-item {
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            background-color: var(--color-white);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-medium);
        }

        .feature-icon {
            font-size: 3.5rem;
            color: var(--color-primary);
            margin-bottom: 20px;
        }

        .feature-title {
            font-family: var(--font-montserrat);
            font-size: 1.8rem;
            color: var(--color-black);
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 1rem;
            color: var(--color-grey-dark);
        }

        /* ==========================================================================
           7. TESTIMONIOS
           ========================================================================== */
        .testimonials-section {
            background-color: var(--color-grey-light);
        }

        .testimonial-carousel .swiper-slide {
            padding: 30px;
            background-color: var(--color-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            text-align: center;
            margin-bottom: 20px; /* Espacio para paginación */
        }

        .testimonial-text {
            font-size: 1.1rem;
            font-style: italic;
            color: var(--color-grey-dark);
            margin-bottom: 20px;
        }

        .testimonial-author-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid var(--color-primary);
        }

        .testimonial-author-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--color-black);
        }

        .testimonial-author-title {
            font-size: 0.9rem;
            color: var(--color-grey-dark);
        }

        .testimonial-carousel .swiper-pagination-bullet {
            background-color: var(--color-primary);
        }

        .testimonial-carousel .swiper-button-next,
        .testimonial-carousel .swiper-button-prev {
            color: var(--color-primary);
        }

        /* ==========================================================================
           8. DESTINOS POPULARES (Carrusel)
           ========================================================================== */
        .destinations-section {
            background-color: var(--color-white);
        }

        .destinations-carousel .swiper-slide {
            width: 300px; /* Ancho fijo para las tarjetas en el carrusel */
            margin-right: 20px; /* Espacio entre slides */
        }

        .destination-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .destination-card:hover {
            transform: translateY(-5px);
        }

        .destination-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .destination-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .destination-card:hover .destination-image {
            transform: scale(1.05);
        }

        .destination-info {
            padding: 15px;
            text-align: center;
        }

        .destination-name {
            font-family: var(--font-montserrat);
            font-size: 1.3rem;
            color: var(--color-primary);
            margin-bottom: 5px;
        }

        .destination-country {
            font-size: 0.9rem;
            color: var(--color-grey-dark);
        }

        .destinations-carousel .swiper-button-next,
        .destinations-carousel .swiper-button-prev {
            color: var(--color-primary);
        }

        /* ==========================================================================
           9. VUELOS EN OFERTA (Carrusel)
           ========================================================================== */
        .flights-section {
            background-color: var(--color-grey-light);
        }

        .flights-carousel .swiper-slide {
            width: 300px; /* Ancho fijo para las tarjetas */
            margin-right: 20px;
        }

        .flight-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .flight-card:hover {
            transform: translateY(-5px);
        }

        .flight-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .flight-origin-destination {
            font-family: var(--font-montserrat);
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--color-black);
        }

        .flight-details {
            font-size: 0.95rem;
            color: var(--color-grey-dark);
        }

        .flight-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--color-secondary);
            text-align: right;
            margin-top: 10px;
        }

        .flights-carousel .swiper-button-next,
        .flights-carousel .swiper-button-prev {
            color: var(--color-primary);
        }

        /* ==========================================================================
           10. CONTACTO
           ========================================================================== */
        .contact-section {
            background-color: var(--color-white);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 50px;
        }

        .contact-info {
            background-color: var(--color-primary);
            color: var(--color-white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
        }

        .contact-info h3 {
            font-family: var(--font-montserrat);
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .contact-info p {
            font-size: 1.1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-info p i {
            font-size: 1.4rem;
            color: var(--color-secondary);
        }

        .contact-form-container {
            background-color: var(--color-white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
        }

        .contact-form-container h3 {
            font-family: var(--font-montserrat);
            font-size: 2rem;
            color: var(--color-primary);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .contact-form .form-group {
            margin-bottom: 20px;
        }

        .contact-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--color-black);
        }

        .contact-form input[type="text"],
        .contact-form input[type="email"],
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-border);
            border-radius: 5px;
            font-family: var(--font-poppins);
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-form input[type="text"]:focus,
        .contact-form input[type="email"]:focus,
        .contact-form textarea:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(0, 95, 107, 0.2);
            outline: none;
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-form .btn-primary {
            width: auto;
            padding: 12px 30px;
        }

      .package-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid var(--color-border);
    margin-top: 20px;
}
  
 /* Footer */
        .footer {
            background-color: #005047; /* Dark Petrol Green */
            color: var(--color-white);
            padding: 40px 20px;
            text-align: center;
            font-size: 15px;
         
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: left;
            margin-bottom: 30px;
         
        }

        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color:rgb(67, 138, 130); 
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: var(--color-white);
        }

        .footer-social-icons a {
            color: var(--color-white);
            font-size: 24px;
            margin: 0 10px;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .footer-social-icons a:hover {
            color: var(--color-forest-green);
            transform: translateY(-3px);
        }

        .footer-bottom-text {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
            margin-top: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.82);
        }  @media (max-width: 768px) { .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .footer-section ul {
                padding-left: 0;
            }
        }
.footer-bottom {
    text-align: center;
    padding-top: var(--spacing-md);
    color: rgba(255, 255, 255, 0.74); /* Color más suave */
    font-size: 0.85rem;
}

/* Media Queries para responsividad del footer */
@media (max-width: 1024px) {
    .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .footer-column {
        min-width: unset; /* Reinicia el ancho mínimo */
        width: 90%; /* Ocupa casi todo el ancho */
        margin-bottom: var(--spacing-md); /* Espacio entre columnas */
    }
    .footer-column h4::after {
        left: 50%;
        transform: translateX(-50%);
    }
    .footer-column .social-icons {
        justify-content: center;
    }
}

@media (max-width: 992px) { /* Para tabletas y pantallas de laptops pequeñas */
    .header {
        padding: 1rem 20px; /* Reduce el padding del header */
        flex-wrap: wrap; /* Permite que los elementos se envuelvan */
        justify-content: center; /* Centra los elementos cuando se envuelven */
    }

    .logo img {
        max-height: 45px; /* Reduce el tamaño del logo */
        margin-bottom: 10px; /* Espacio debajo del logo si se envuelve */
    }

    .main-nav {
        display: none; /* Oculta la navegación principal por defecto */
        width: 100%; /* Ocupa todo el ancho si se muestra */
        flex-direction: column; /* Apila los enlaces verticalmente */
        text-align: center; /* Centra los enlaces */
        margin-top: 10px;
    }

    .main-nav.active {
        display: flex; /* Muestra la navegación cuando está activa (desde el JS del menú hamburguesa) */
    }

    .main-nav li {
        margin: 10px 0; /* Espacio vertical entre los elementos del menú */
    }

    .auth-buttons {
        margin-left: 0; /* Elimina el margen izquierdo en pantallas más pequeñas */
        margin-top: 10px; /* Añade un margen superior si los botones se envuelven */
        width: 100%;
        justify-content: center; /* Centra los botones */
    }

    .menu-toggle {
        display: block; /* Muestra el botón de hamburguesa */
        position: absolute; /* Posiciona el botón de hamburguesa */
        right: 20px;
        top: 25px; /* Ajusta la posición vertical */
        z-index: 100;
    }
}

@media (max-width: 768px) { /* Para tabletas y móviles más grandes */
    .header {
        padding: 0.8rem 15px; /* Reduce aún más el padding */
    }

    .logo img {
        max-height: 40px; /* Logo más pequeño */
    }

    .menu-toggle {
        top: 20px; /* Ajusta la posición */
    }
}

@media (max-width: 480px) { /* Para móviles pequeños */
    .header {
        flex-direction: column; /* Apila el logo y los botones */
        align-items: center; /* Centra los elementos apilados */
        padding: 0.5rem 10px;
    }

    .logo img {
        max-height: 35px; /* Logo aún más pequeño */
        margin-bottom: 5px;
    }

    .auth-buttons {
        flex-direction: column; /* Apila los botones de autenticación */
        width: auto;
    }

    .auth-buttons .btn {
        margin: 5px 0; /* Espacio entre los botones apilados */
        width: 100%; /* Botones de autenticación ocupan todo el ancho */
        max-width: 200px; /* Limita el ancho máximo para que no sean demasiado grandes */
    }

    .menu-toggle {
        top: 15px; /* Ajusta la posición del toggle */
        right: 15px;
    }
}

/* ========================================= */
/* ESTILOS PARA EL PIE DE PÁGINA (FOOTER) */
/* ========================================= */

@media (max-width: 992px) {
    .footer-content {
        flex-direction: column; /* Apila las secciones del footer */
        align-items: flex-start; /* Alinea al inicio */
        padding: 30px 20px; /* Ajusta el padding */
    }

    .footer-section {
        margin-bottom: 25px; /* Espacio entre las secciones apiladas */
        width: 100%; /* Ocupa todo el ancho disponible */
        text-align: left; /* Alinea el texto a la izquierda */
    }

    .footer-bottom {
        padding: 15px 20px;
    }
}

@media (max-width: 768px) {
    .footer-content {
        padding: 20px 15px;
    }

    .footer-section h3 {
        font-size: 1.2rem; /* Reduce el tamaño del título de sección */
    }

    .footer-section p,
    .footer-section ul li a {
        font-size: 0.9rem; /* Reduce el tamaño del texto */
    }

    .social-icons a {
        font-size: 1.5rem; /* Ajusta el tamaño de los iconos sociales */
    }

    .footer-bottom {
        font-size: 0.8rem; /* Reduce el tamaño del texto del copyright */
        padding: 10px 15px;
    }
}

@media (max-width: 480px) {
    .footer-content {
        padding: 15px 10px;
    }

    .footer-section {
        margin-bottom: 20px;
    }

    .footer-section h3 {
        font-size: 1.1rem;
    }

    .footer-section p,
    .footer-section ul li a {
        font-size: 0.85rem;
    }

    .social-icons a {
        font-size: 1.3rem;
        margin: 0 5px; /* Ajusta el margen entre iconos */
    }

    .footer-bottom {
        font-size: 0.75rem;
        padding: 8px 10px;
        text-align: center; /* Centra el texto del copyright */
    }
}


@media (max-width: 480px) {
    .footer-column .footer-logo {
        height: 50px;
    }
    .footer-column h4 {
        font-size: 1.2rem;
    }
    .footer-column p, .footer-column ul li, .newsletter-form input, .newsletter-form .btn {
        font-size: 0.85rem;
    }
    .footer-column .social-icons a {
        width: 35px;
        height: 35px;
        font-size: 1.1rem;
    }
    .newsletter-form {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    .newsletter-form .btn-primary {
        width: 100%;
    }
}
.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.social-icons a {
    color: var(--color-white);
    font-size: 1.5rem;
    transition: color var(--transition-speed), transform var(--transition-speed);
}

.social-icons a:hover {
    color: var(--color-secondary);
    transform: translateY(-3px) scale(1.1);
}


        /* ==========================================================================
           RESPONSIVE DESIGN (Media Queries)
           ========================================================================== */

        /* Tablet (hasta 768px) */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 3.2rem;
            }
            .hero-subtitle {
                font-size: 1.2rem;
            }

            .main-nav {
                display: none; /* Oculta la navegación principal */
                position: absolute;
                top: 80px; /* Altura del header */
                left: 0;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.98);
                box-shadow: var(--shadow-medium);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                border-top: 1px solid var(--color-border);
            }

            .main-nav.active {
                display: flex; /* Muestra el menú cuando está activo */
            }

            .main-nav ul {
                flex-direction: column;
                gap: 15px;
                width: 100%;
                text-align: center;
            }

            .main-nav ul li a {
                padding: 10px 0;
                display: block;
            }

            .menu-toggle {
                display: block; /* Muestra el icono de hamburguesa */
            }

            /* Mostrar los botones de acción en el menú móvil si se abre, o mantenerlos ocultos */
            .header-actions {
                display: none; /* Ocultar los botones de login/registro en el header para que aparezcan en el menú hamburguesa si se desea, o manejarlos de otra forma en móvil. Por ahora se ocultan*/
            }
            .main-nav.active + .header-actions { /* Esto los mostraría si el nav se activa, pero no si están fuera del nav */
                display: flex;
                flex-direction: column;
                width: 100%;
                padding-top: 15px;
            }
            .main-nav.active + .header-actions .btn {
                width: 80%;
                margin: 0 auto 10px auto;
            }


            .section-title {
                font-size: 2.2rem;
            }
            .section-subtitle {
                font-size: 1rem;
            }

            .paquetes-grid,
            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .testimonial-carousel .swiper-button-next,
            .testimonial-carousel .swiper-button-prev,
            .destinations-carousel .swiper-button-next,
            .destinations-carousel .swiper-button-prev,
            .flights-carousel .swiper-button-next,
            .flights-carousel .swiper-button-prev {
                display: none; /* Ocultar flechas en móvil para ahorrar espacio */
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Móvil Pequeño (hasta 480px) */
        @media (max-width: 480px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1rem; }

            .section-title { font-size: 1.8rem; }
            .section-subtitle { font-size: 0.9rem; }

            .paquetes-grid,
            .features-grid {
                gap: 20px;
            }
            .package-card {
                padding-bottom: 15px;
            }
            .package-title {
            font-size: 1.3rem;
            }
            .package-price {
                font-size: 1.2rem;
            }

            .feature-icon {
                font-size: 3rem;
            }
            .feature-title {
                font-size: 1.5rem;
            }

            .testimonial-text {
                font-size: 1rem;
            }
            .testimonial-author-img {
                width: 60px;
                height: 60px;
            }

            .chatbot-container {
                width: 90%;
                right: 5%;
                bottom: 90px;
                height: 400px;
            }
            .chatbot-bubble {
                width: 50px;
                height: 50px;
                font-size: 1.8rem;
                bottom: 20px;
                right: 20px;
            }

            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .footer-logo-col, .footer-links-col, .footer-contact-col, .footer-social-col {
                min-width: unset;
                width: 100%;
            }
            .social-icons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
   <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-img"><img src="Images/logomapache-removebg-preview.png" height="40px" width="40px"></div> <div>
                    <div class="brand-name">MangueAR</div>
                    <div class="tagline">Pagá menos por viajar</div>
                </div>
            </div>
            
            <nav class="nav-menu">
                <a href="login_usuario.php" class="nav-item">
                    <i class="fa-solid fa-hotel nav-icon"></i>
                    <div class="nav-text" >Hospedaje</div>
                </a>
                <a href="login_usuario.php" class="nav-item">
                    <i class="fa-solid fa-plane nav-icon"></i>
                    <div class="nav-text">Vuelos</div>
                </a>
                <a href="login_usuario.php" class="nav-item active"> <i class="fa-solid fa-box-archive nav-icon"></i>
                    <div class="nav-text">Paquetes</div>
                </a>
                <a href="login_usuario.php" class="nav-item">
                    <i class="fa-solid fa-tag nav-icon"></i>
                    <div class="nav-text">Ofertas</div>
                </a>
                <a href="login_usuario.php" class="nav-item">
                    <i class="fa-solid fa-compass nav-icon"></i>
                    <div class="nav-text">Turismo Personalizado</div>
                </a>
                <a href="soporte_usuario.php" class="nav-item">
                    <i class="fa-solid fa-headset nav-icon"></i>
                    <div class="nav-text">Soporte</div>
                </a>
            </nav>
            
            <div class="user-section">
                <select class="currency-selector">
                    <option>AR (ARS)</option>
                    <option>US (USD)</option>
                </select>

            <div class="header-actions">
                <a href="login_usuario.php?action=login" class="btn btn-primary">Iniciar Sesión</a>
                <a href="login_usuario.php" class="btn btn-secondary">Registrarse</a> </div>
        </div>
    </header>

    <main>
        <section id="hero" class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Descubre Tu Próxima Aventura</h1>
                <p class="hero-subtitle">Viajes inolvidables a los destinos más asombrosos del mundo.</p>
                <div class="hero-buttons">
                    <a href="#packages" class="btn btn-primary">Explorar Paquetes</a>
                    <a href="#contact" class="btn btn-secondary">Contáctanos</a>
                </div>
            </div>
        </section>

        <section id="packages" class="section packages-section">
            <div class="container">
                <h2 class="section-title">Nuestros Paquetes Populares</h2>
                <p class="section-subtitle">Explora una selección de los destinos más solicitados y emocionantes que tenemos para ofrecerte.</p>
                <div class="paquetes-grid" id="packagesGrid">
                    </div>
            </div>
        </section>

      

      

      

     
        <section id="testimonials" class="section testimonials-section">
            <div class="container">
                <h2 class="section-title">Lo Que Dicen Nuestros Viajeros</h2>
                <p class="section-subtitle">Opiniones de clientes satisfechos que han confiado en MangueAR para sus aventuras.</p>
                <div class="swiper-container testimonial-carousel">
                    <div class="swiper-wrapper" id="testimonialsCarousel">
                        </div>
                  
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre MangueAR</h3>
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

  


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        /**
         * @author MangueAR Development Team
         * @version 4.0.0
         * @description Archivo JavaScript principal para MangueAR.
         * Maneja la interactividad del usuario, renderizado dinámico de contenido,
         * y efectos visuales. Modificado para eliminar funciones de carrito/favoritos
         * y enfocarse en una landing page.
         */

        document.addEventListener('DOMContentLoaded', () => {
            // =========================================================================
            // 1. DATOS DE EJEMPLO AMPLIADOS (En una aplicación real, esto vendría de una API o PHP)
            // =========================================================================

            const heroData = {
                title: "Descubre Tu Próxima Aventura",
                subtitle: "Viajes inolvidables a los destinos más asombrosos del mundo."
            };

            const packagesData = [
                // Lujo y Exotismo
                { id: 1, name: 'Riviera Maya de Lujo', price: 1500.00, imageUrl: 'Images/pexels-pho-tomass-883344227-32490384.jpg', description: '7 días en resorts exclusivos. Disfruta de playas de arena blanca, aguas turquesa y un servicio inigualable.', rating: 5, category: 'Lujo' },
                { id: 2, name: 'Safari Fotográfico en Tanzania', price: 3200.00, imageUrl: 'Images/pexels-saeb-mahajna-14125913-6297105.jpg', description: 'Captura la majestuosidad de la vida salvaje africana en un safari de 10 días con guías expertos.', rating: 5, category: 'Aventura' },
                { id: 3, name: 'Crucero por las Islas Griegas', price: 2100.00, imageUrl: 'Images/pexels-ahmetyuksek-30214899.jpg', description: 'Explora la belleza y la historia de Santorini, Mykonos y Creta en un crucero de 8 días de lujo.', rating: 4, category: 'Relax' },
                { id: 4, name: 'Expedición a la Antártida', price: 7500.00, imageUrl: 'Images/pexels-asadphoto-1450360.jpg', description: 'Una aventura única de 12 días a los confines del mundo, con avistamiento de fauna y paisajes glaciares.', rating: 5, category: 'Aventura Extrema' },
                { id: 5, name: 'Luna de Miel en Maldivas', price: 4500.00, imageUrl: 'Images/pexels-asadphoto-11118954.jpg', description: '7 noches en un bungalow sobre el agua, perfecto para una escapada romántica inolvidable.', rating: 5, category: 'Romance' },
                // Cultura y Aventura
                { id: 6, name: 'Recorrido Histórico por Roma', price: 950.00, imageUrl: 'Images/pexels-athenea-codjambassis-rossitto-472760075-26977242.jpg', description: '5 días explorando el Coliseo, el Vaticano y la Fontana di Trevi con guías locales.', rating: 4, category: 'Cultura' },
                { id: 7, name: 'Trekking en la Patagonia', price: 1800.00, imageUrl: 'Images/pexels-azaay14-32435851.jpg', description: '8 días de aventura en los paisajes imponentes de la Patagonia Argentina, incluyendo El Chaltén.', rating: 5, category: 'Aventura' },
                { id: 8, name: 'Descubriendo la Selva Amazónica', price: 2800.00, imageUrl: 'Images/pexels-dantemunozphoto-15481505.jpg', description: '6 días de inmersión en la biodiversidad del Amazonas, con excursiones y encuentros con comunidades indígenas.', rating: 4, category: 'Naturaleza' },
                { id: 9, name: 'Mochilero por el Sudeste Asiático', price: 1200.00, imageUrl: 'Images/pexels-dantemunozphoto-15941831.jpg', description: '14 días explorando Tailandia y Vietnam, ideal para viajeros con presupuesto y ganas de aventura.', rating: 4, category: 'Aventura' },
                { id: 10, name: 'Ruta del Vino en Mendoza', price: 600.00, imageUrl: 'Images/pexels-dantemunozphoto-15941836.jpg', description: '3 días disfrutando de las bodegas más prestigiosas y los paisajes vitivinícolas de Mendoza.', rating: 4, category: 'Gastronomía' },
                // Ciudades y Escapadas
                { id: 11, name: 'Fin de Semana en París', price: 700.00, imageUrl: 'Images/pexels-dantemunozphoto-15941839.jpg', description: '4 días para explorar la Torre Eiffel, el Louvre y disfrutar de la gastronomía francesa.', rating: 4, category: 'Ciudad' },
                { id: 12, name: 'Nueva York al Completo', price: 1300.00, imageUrl: 'Images/pexels-dantemunozphoto-28821762.jpg', description: '6 días para vivir la energía de la Gran Manzana: Broadway, Central Park, museos y más.', rating: 5, category: 'Ciudad' },
                { id: 13, name: 'Relax en Bali', price: 1600.00, imageUrl: 'Images/pexels-fabian-lozano-2152897796-32469373.jpg', description: '8 días de meditación, yoga y playas paradisíacas en la "Isla de los Dioses".', rating: 5, category: 'Relax' },
                { id: 14, name: 'Aventura en Patagonia Chilena', price: 2000.00, imageUrl: 'Images/pexels-lavdrim-mustafi-337111893-14529445.jpg', description: 'Recorre el Parque Nacional Torres del Paine y sus alrededores en una expedición de 7 días.', rating: 5, category: 'Aventura' },
                { id: 15, name: 'Misterios de Egipto', price: 2500.00, imageUrl: 'Images/pexels-m-emre_celik-2054744248-32496666.jpg', description: '9 días descubriendo las pirámides, templos antiguos y el río Nilo con un crucero incluido.', rating: 5, category: 'Historia' }
            ];

            const testimonialsData = [
                {
                    id: 1,
                    text: "¡Mi viaje a la Patagonia con MangueAR fue absolutamente increíble! La organización fue perfecta y los paisajes superaron todas mis expectativas. ¡Totalmente recomendado!",
                    author: "Ana López",
                    title: "Aventurera Apasionada",
                    imageUrl: "Images/fotodeperfil4m.jpeg"
                },
                {
                    id: 2,
                    text: "Nunca imaginé que un safari en Tanzania sería tan impactante. Gracias a MangueAR por hacer realidad el sueño de mi vida. Cada detalle fue cuidado a la perfección.",
                    author: "Carlos Gómez",
                    title: "Fotógrafo de Naturaleza",
                    imageUrl: "Images/fotodeperfil1.jpeg"
                },
                {
                    id: 3,
                    text: "Mi luna de miel en Maldivas fue de ensueño. El resort, las actividades, todo fue mágico. MangueAR nos brindó una experiencia inolvidable y sin preocupaciones.",
                    author: "María Fernández",
                    title: "Recién Casada",
                    imageUrl: "Images/fotodeperfil5m.jpeg"
                },
                {
                    id: 4,
                    text: "El recorrido por Roma fue fascinante. Aprendí muchísimo de historia y la comida fue espectacular. ¡Definitivamente viajaré de nuevo con ellos!",
                    author: "Juan Pérez",
                    title: "Historiador Aficionado",
                    imageUrl: "Images/fotodeperfil2.jpeg"
                },
                {
                    id: 5,
                    text: "La atención al cliente de MangueAR es excepcional. Tuvieron en cuenta cada una de mis preferencias para mi viaje a Japón. Un servicio de cinco estrellas.",
                    author: "Sofía Martínez",
                    title: "Exploradora Cultural",
                    imageUrl: "Images/fotodeperfil6m.jpeg"
                }
            ];

            const destinationsData = [
                { id: 1, name: 'París', country: 'Francia', imageUrl: 'Images/argentinaobelico.jpg' },
                { id: 2, name: 'Kioto', country: 'Japón', imageUrl: 'Images/kyoto.jpg' },
                { id: 3, name: 'Queenstown', country: 'Nueva Zelanda', imageUrl: 'Images/queenstown.jpg' },
                { id: 4, name: 'Ciudad del Cabo', country: 'Sudáfrica', imageUrl: 'Images/cape_town.jpg' },
                { id: 5, name: 'Río de Janeiro', country: 'Brasil', imageUrl: 'Images/rio.jpg' },
                { id: 6, name: 'Sídney', country: 'Australia', imageUrl: 'Images/sydney.jpg' },
                { id: 7, name: 'Dubái', country: 'Emiratos Árabes Unidos', imageUrl: 'Images/dubai.jpg' },
                { id: 8, name: 'Barcelona', country: 'España', imageUrl: 'Images/barcelona.jpg' }
            ];

            const flightsData = [
                { id: 1, origin: 'BUE', destination: 'MAD', price: 850.00, details: 'Buenos Aires a Madrid', date: 'Ida y vuelta' },
                { id: 2, origin: 'MEX', destination: 'CUN', price: 120.00, details: 'Ciudad de México a Cancún', date: 'Ida y vuelta' },
                { id: 3, origin: 'NYC', destination: 'LON', price: 600.00, details: 'Nueva York a Londres', date: 'Ida y vuelta' },
                { id: 4, origin: 'SYD', destination: 'AKL', price: 250.00, details: 'Sídney a Auckland', date: 'Ida y vuelta' },
                { id: 5, origin: 'GRU', destination: 'LIS', price: 780.00, details: 'Sao Paulo a Lisboa', date: 'Ida y vuelta' },
                { id: 6, origin: 'BOG', destination: 'MIA', price: 300.00, details: 'Bogotá a Miami', date: 'Ida y vuelta' },
                { id: 7, origin: 'SCL', destination: 'LIM', price: 150.00, details: 'Santiago a Lima', date: 'Ida y vuelta' }
            ];

            // =========================================================================
            // 2. RENDERIZADO DINÁMICO DE CONTENIDO
            // =========================================================================

            // Renderizar Paquetes
            const packagesGrid = document.getElementById('packagesGrid');
            if (packagesGrid) {
                packagesData.forEach(pkg => {
                    const card = document.createElement('div');
                    card.classList.add('package-card');
                    card.innerHTML = `
                        <div class="package-image-container">
                            <img src="${pkg.imageUrl}" alt="${pkg.name}" class="package-image">
                        </div>
                        <div class="package-content" href="unico.producto.html>
                            <h3 class="package-title">${pkg.name}</h3>
                            <p class="package-description">${pkg.description}</p>
                            <div class="package-rating">${'★'.repeat(pkg.rating)}${'☆'.repeat(5 - pkg.rating)}</div>
                            <p class="package-price">$${pkg.price.toFixed(2)}</p>
                            <a class="btn btn-primary add-to-cart-btn" href="unico_producto.html" data-id="${pkg.id}">Ver Detalles</a>
                            <div class="favorite-btn" data-id="${pkg.id}"><i class="far fa-heart"></i></div>
                        </div>
                    `;
                    packagesGrid.appendChild(card);
                });
            }

            // Renderizar Testimonios (Swiper)
            const testimonialsCarouselWrapper = document.getElementById('testimonialsCarousel');
            if (testimonialsCarouselWrapper) {
                testimonialsData.forEach(testimonial => {
                    const slide = document.createElement('div');
                    slide.classList.add('swiper-slide', 'testimonial-slide');
                    slide.innerHTML = `
                        <p class="testimonial-text">"${testimonial.text}"</p>
                        <img src="${testimonial.imageUrl}" alt="${testimonial.author}" class="testimonial-author-img">
                        <p class="testimonial-author-name">${testimonial.author}</p>
                        <p class="testimonial-author-title">${testimonial.title}</p>
                    `;
                    testimonialsCarouselWrapper.appendChild(slide);
                });
                new Swiper('.testimonial-carousel', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    },
                });
            }

            // Renderizar Destinos Populares (Swiper)
            const destinationsCarouselWrapper = document.getElementById('destinationsCarousel');
            if (destinationsCarouselWrapper) {
                destinationsData.forEach(dest => {
                    const slide = document.createElement('div');
                    slide.classList.add('swiper-slide');
                    slide.innerHTML = `
                        <div class="destination-card">
                            <div class="destination-image-container">
                                <img src="${dest.imageUrl}" alt="${dest.name}" class="destination-image">
                            </div>
                            <div class="destination-info">
                                <h3 class="destination-name">${dest.name}</h3>
                                <p class="destination-country">${dest.country}</p>
                            </div>
                        </div>
                    `;
                    destinationsCarouselWrapper.appendChild(slide);
                });
                new Swiper('.destinations-carousel', {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                });
            }

            // Renderizar Vuelos en Oferta (Swiper)
            const flightsCarouselWrapper = document.getElementById('flightsCarousel');
            if (flightsCarouselWrapper) {
                flightsData.forEach(flight => {
                    const slide = document.createElement('div');
                    slide.classList.add('swiper-slide');
                    slide.innerHTML = `
                        <div class="flight-card">
                            <div class="flight-info">
                                <p class="flight-origin-destination">${flight.origin} <i class="fas fa-arrow-right"></i> ${flight.destination}</p>
                                <p class="flight-details">${flight.details}</p>
                                <p class="flight-details">${flight.date}</p>
                                <p class="flight-price">Desde $${flight.price.toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                    flightsCarouselWrapper.appendChild(slide);
                });
                new Swiper('.flights-carousel', {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 4500,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                });
            }

            // =========================================================================
            // 3. NAVEGACIÓN Y EFECTOS
            // =========================================================================

            // Smooth Scroll para los enlaces de navegación
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                    // Ocultar menú en móviles después de hacer clic
                    if (mainNav.classList.contains('active')) {
                        mainNav.classList.remove('active');
                        menuToggle.classList.remove('active'); // Remover clase para cambiar ícono si aplica
                    }
                });
            });

            // Menu hamburguesa para móviles
            const menuToggle = document.getElementById('menuToggle');
            const mainNav = document.getElementById('mainNav');
            if (menuToggle && mainNav) {
                menuToggle.addEventListener('click', () => {
                    mainNav.classList.toggle('active');
                    menuToggle.classList.toggle('active'); // Opcional: para cambiar el ícono de hamburguesa a cruz
                });
            }

            // =========================================================================
            // 4. CHATBOT INTERACTIVO
            // =========================================================================
            const chatbotBubble = document.getElementById('chatbotBubble');
            const chatbotContainer = document.getElementById('chatbotContainer');
            const chatbotCloseBtn = document.getElementById('chatbotCloseBtn');
            const chatbotMessages = document.getElementById('chatbotMessages');
            const chatbotInputField = document.getElementById('chatbotInputField');
            const chatbotSendBtn = document.getElementById('chatbotSendBtn');
            const chatbotMenu = document.getElementById('chatbotMenu');

            if (chatbotBubble) {
                chatbotBubble.addEventListener('click', () => {
                    chatbotContainer.classList.toggle('active');
                });
            }

            if (chatbotCloseBtn) {
                chatbotCloseBtn.addEventListener('click', () => {
                    chatbotContainer.classList.remove('active');
                });
            }

            const addMessage = (message, sender) => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('chatbot-message', sender);
                messageElement.textContent = message;
                chatbotMessages.appendChild(messageElement);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight; // Scroll al último mensaje
            };

            const getBotResponse = (query) => {
                query = query.toLowerCase();
                if (query.includes('hola') || query.includes('ayuda')) {
                    return '¡Hola! ¿En qué puedo ayudarte? Puedes preguntar sobre paquetes, destinos, vuelos o contacto.';
                } else if (query.includes('paquetes')) {
                    return 'Ofrecemos paquetes de lujo, aventura, culturales y de relax. ¿Te interesa alguno en particular?';
                } else if (query.includes('contacto') || query.includes('agente')) {
                    return 'Puedes contactarnos por teléfono al +54 11 1234-5678 o enviar un mensaje a través de nuestro formulario en la sección de contacto.';
                } else if (query.includes('destinos')) {
                    return 'Nuestros destinos más populares incluyen París, Kioto, Patagonia, Maldivas y Roma. ¿Hay algún lugar que te gustaría explorar?';
                } else if (query.includes('vuelos') || query.includes('ofertas de vuelos')) {
                    return 'Sí, tenemos vuelos en oferta a varios destinos como Madrid, Cancún y Londres. Te recomiendo revisar nuestra sección de "Vuelos Imperdibles" para ver las últimas ofertas.';
                } else if (query.includes('cancelación') || query.includes('política')) {
                    return 'Nuestra política de cancelación varía según el paquete y el proveedor. Te recomendamos revisar los términos y condiciones específicos de cada reserva o contactar a un agente.';
                } else if (query.includes('gracias')) {
                    return 'De nada. ¡Estoy aquí para ayudarte!';
                } else {
                    return 'Lo siento, no entendí tu pregunta. ¿Puedes reformularla o elegir una opción del menú?';
                }
            };

            const sendMessage = () => {
                const userQuery = chatbotInputField.value.trim();
                if (!userQuery) return; // No enviar mensajes vacíos

                addMessage(userQuery, 'user'); // Añade el mensaje del usuario
                chatbotInputField.value = ''; // Limpia el campo de entrada

                // Simula un pequeño retraso para que parezca que el bot está "pensando"
                setTimeout(() => {
                    const botResponse = getBotResponse(userQuery);
                    addMessage(botResponse, 'bot'); // Añade la respuesta del bot
                }, 700); // Retraso de 0.7 segundos
            };

            if (chatbotSendBtn) {
                chatbotSendBtn.addEventListener('click', sendMessage);
            }

            if (chatbotInputField) {
                chatbotInputField.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });
            }

            // Event Listener para las opciones del menú predefinidas
            if (chatbotMenu) {
                chatbotMenu.addEventListener('click', (e) => {
                    if (e.target.classList.contains('menu-option')) {
                        const query = e.target.dataset.query; // Obtiene la pregunta del atributo data-query
                        addMessage(query, 'user'); // Muestra la "pregunta" del usuario desde el menú

                        // Simula el tiempo de respuesta del bot
                        setTimeout(() => {
                            const botResponse = getBotResponse(query);
                            addMessage(botResponse, 'bot'); // Muestra la respuesta del bot
                        }, 700);
                    }
                });
            }
        });
    </script>
</body>
</html>