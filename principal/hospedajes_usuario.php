<?php
session_start();
require_once 'db_connection.php'; // NO SE TOCA YA FUNCIONA

// Variables para mensajes de notificación
$message = '';
$message_type = '';

if (isset($_SESSION['success_message'])) {
    $message = htmlspecialchars($_SESSION['success_message']);
    $message_type = 'success';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $message = htmlspecialchars($_SESSION['error_message']);
    $message_type = 'error';
    unset($_SESSION['error_message']);
}

// Lógica para obtener los hospedajes de la base de datos
$hospedajes = [];
try {
    $stmt = $pdo->prepare("SELECT id, nombre, ubicacion, descripcion, categoria, estrellas, precio_por_noche, imagen_url, servicios FROM hospedajes WHERE disponibilidad = 1 ORDER BY nombre ASC");
    $stmt->execute();
    $hospedajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al cargar hospedajes: " . $e->getMessage());
    $message = 'Hubo un error al cargar los hospedajes. Por favor, inténtalo de nuevo más tarde.';
    $message_type = 'error';
}

// --- DATOS FICTICIOS PARA HOTELES ALIADOS (Reducidos y con logos de ejemplo de alta calidad) ---
$hoteles_aliados = [
    [
        'nombre' => 'Hilton Worldwide',
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Hilton_Worldwide_logo.svg/1024px-Hilton_Worldwide_logo.svg.png',
        'descripcion' => 'Una de las cadenas hoteleras más grandes y reconocidas a nivel mundial, ofreciendo lujo y confort.',
        'web_url' => 'https://www.hilton.com/'
    ],
    [
        'nombre' => 'Marriott International',
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Marriott_International_logo.svg/1024px-Marriott_International_logo.svg.png',
        'descripcion' => 'Líder en hospitalidad global con una vasta cartera de marcas, desde el lujo hasta estancias prolongadas.',
        'web_url' => 'https://www.marriott.com/'
    ],
    [
        'nombre' => 'Hyatt Hotels Corporation',
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Hyatt_Logo.svg/1024px-Hyatt_Logo.svg.png',
        'descripcion' => 'Conocida por su enfoque en experiencias auténticas y servicio personalizado en destinos clave.',
        'web_url' => 'https://www.hyatt.com/'
    ],
    [
        'nombre' => 'Accor',
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Accor_logo.svg/1024px-Accor_logo.svg.png',
        'descripcion' => 'Grupo hotelero francés con presencia global, abarcando desde hoteles económicos hasta de lujo.',
        'web_url' => 'https://group.accor.com/'
    ],
    [
        'nombre' => 'IHG Hotels & Resorts',
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/IHG_Hotels_%26%20Resorts_logo.svg/1024px-IHG_Hotels_%26%20Resorts_logo.svg.png',
        'descripcion' => 'Ofrece una amplia gama de marcas, desde Holiday Inn hasta InterContinental, adaptándose a cada viajero.',
        'web_url' => 'https://www.ihg.com/'
    ]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospedajes Disponibles - MangueAR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ======================================= */
        /* CSS GLOBAL Y VARIABLES                  */
        /* ======================================= */
        :root {
            --color-primary: #007bff; /* Azul vibrante */
            --color-secondary: #6c757d; /* Gris secundario */
            --color-success: #28a745; /* Verde éxito */
            --color-info: #17a2b8; /* Azul claro info */
            --color-warning: #ffc107; /* Amarillo advertencia */
            --color-danger: #dc3545; /* Rojo peligro */
            --color-light: #f8f9fa; /* Gris muy claro */
            --color-dark: #343a40; /* Gris oscuro */
            --color-white: rgb(255, 255, 255);
            --color-black: #000000;
            --color-grey-light: #e9ecef;
            --color-grey-dark: #6c757d;
            --color-border: #dee2e6;

            --color-petrol-green: #005047; /* Verde Petróleo, el principal del header/footer */
            --color-forest-green: #1a5a40; /* Un verde más bosque, para highlights */
            --color-accent-blue: #00bcd4; /* Azul turquesa para acentos de innovación */

            --font-family-base: 'Arial', sans-serif;
            --font-size-base: 1rem;
            --line-height-base: 1.5;

            --spacing-sm: 10px;
            --spacing-md: 20px;
            --spacing-lg: 30px;

            --border-radius-base: 0.75rem; /* Aumentado para un look más moderno */
            --box-shadow-base: 0 0.75rem 2rem rgba(0, 0, 0, 0.12); /* Sombra más pronunciada */
            --box-shadow-light: 0 0.35rem 0.75rem rgba(0, 0, 0, 0.07);

            --transition-speed: 0.4s ease-in-out; /* Velocidad de transición general */
            --fast-transition: 0.2s ease;

            /* Custom for this page */
            --card-bg: #ffffff;
            --card-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.2);
            --primary-highlight-color: var(--color-forest-green); /* Usado para botones, títulos */
            --primary-highlight-hover: #164a34; /* Darker forest green */
            --secondary-highlight-color: var(--color-accent-blue); /* Usado para precio u otros detalles */
            --text-light-grey: var(--color-grey-dark);
            --font-headings: 'Montserrat', sans-serif; /* Un tipo de letra más moderno para los encabezados */
        }

        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

        body {
            font-family: var(--font-family-base);
            margin: 0;
            padding: 0;
            background-color: var(--color-light);
            color: var(--color-dark);
            line-height: var(--line-height-base);
            overflow-x: hidden; /* Evita desbordamiento horizontal debido a animaciones */
        }

        /* Utility classes */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* ======================================= */
        /* HEADER STYLES (INNOVADOR)               */
        /* ======================================= */
        .header {
            background-color: var(--color-petrol-green);
            color: white;
            padding: 0.8rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background-color var(--transition-speed), padding var(--transition-speed);
        }

        .header.scrolled {
            background-color: rgba(0, 80, 71, 0.95); /* Ligeramente más transparente */
            padding: 0.5rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.25);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            max-width: 1300px;
            margin: 0 auto;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            opacity: 0; /* Animación de entrada */
            transform: translateX(-20px);
            animation: fadeInSlideRight 0.8s forwards 0.2s;
        }

        @keyframes fadeInSlideRight {
            to { opacity: 1; transform: translateX(0); }
        }

        .logo-img {
            width: 40px; /* Slightly larger */
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            color: var(--color-white);
            flex-shrink: 0;
            overflow: hidden; /* Para la imagen del logo */
        }
        .logo-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-name {
            font-size: 26px; /* Slightly larger */
            font-weight: 700;
            color: white;
            letter-spacing: -0.8px;
            white-space: nowrap;
        }

        .tagline {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 2px;
        }

        .nav-menu {
            display: flex;
            gap: 1rem; /* More space between items */
            align-items: center;
            flex-grow: 1;
            margin: 0 1rem;
            justify-content: center;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1.4rem; /* More padding */
            border-radius: 8px; /* Softer corners */
            transition: background-color var(--fast-transition), color var(--fast-transition), transform var(--fast-transition);
            white-space: nowrap;
            position: relative;
            overflow: hidden; /* For underline effect */
        }

        .nav-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--color-accent-blue);
            transform: translateX(-100%);
            transition: transform var(--fast-transition) ease-out;
        }

        .nav-item:hover::before, .nav-item.active::before {
            transform: translateX(0);
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .nav-item.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-icon {
            font-size: 20px; /* Larger icons */
            margin-bottom: 5px;
        }

        .nav-text {
            font-size: 14px; /* Slightly larger text */
            font-weight: 600;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            position: relative;
            flex-shrink: 0;
            opacity: 0; /* Animación de entrada */
            transform: translateX(20px);
            animation: fadeInSlideLeft 0.8s forwards 0.2s;
        }
        @keyframes fadeInSlideLeft {
            to { opacity: 1; transform: translateX(0); }
        }

        .currency-selector {
            background-color: rgba(27, 90, 29, 0.3); /* Darker, more prominent */
            border: 1px solid rgba(255, 255, 255, 0.4); /* Lighter border */
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-size: 14px;
            appearance: none;
            cursor: pointer;
            transition: background-color var(--fast-transition);
        }
        .currency-selector:hover {
            background-color: rgba(27, 90, 29, 0.5);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 25px; /* More space */
        }

        .header-icon {
            position: relative;
            font-size: 1.4rem; /* Larger icons */
            color: white;
            cursor: pointer;
            padding: 5px; /* Clickable area */
            border-radius: 50%;
            transition: background-color var(--fast-transition), transform var(--fast-transition);
        }

        .header-icon:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.1);
        }

        .icon-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--color-danger);
            color: var(--color-white);
            font-size: 0.7em;
            font-weight: 700;
            border-radius: 50%;
            padding: 2px 6px;
            min-width: 18px;
            text-align: center;
            line-height: 1;
            border: 1px solid var(--color-white);
            animation: bounceIn 0.5s ease-out; /* Animation for new notifications */
        }
        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        .profile-menu {
            position: relative;
            cursor: pointer;
        }

        .profile-picture {
            width: 45px; /* Larger profile pic */
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--color-accent-blue); /* Accent border */
            transition: transform var(--fast-transition), box-shadow var(--fast-transition);
        }

        .profile-picture:hover {
            transform: scale(1.08);
            box-shadow: 0 0 0 5px rgba(0, 188, 212, 0.3); /* Glow effect */
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 15px); /* More space */
            right: 0;
            background-color: var(--color-white);
            box-shadow: var(--box-shadow-base);
            border-radius: var(--border-radius-base);
            min-width: 180px; /* Wider dropdown */
            overflow: hidden;
            z-index: 999;
            opacity: 0;
            transform: translateY(15px);
            transition: opacity var(--transition-speed), transform var(--transition-speed);
        }

        .profile-dropdown.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .profile-dropdown a {
            display: flex; /* Para íconos si se quieren añadir */
            align-items: center;
            gap: 10px;
            padding: 12px 20px; /* More padding */
            color: var(--color-dark);
            font-size: 1rem;
            text-decoration: none;
            transition: background-color var(--fast-transition), color var(--fast-transition);
        }

        .profile-dropdown a:hover {
            background-color: var(--color-grey-light);
            color: var(--primary-highlight-color);
        }
        .profile-dropdown a i {
            color: var(--text-light-grey); /* Icon color */
            font-size: 1.1em;
        }
        .profile-dropdown a:hover i {
            color: var(--primary-highlight-color);
        }

        /* ======================================= */
        /* MAIN CONTENT - HOSTEL LISTING (INNOVADOR) */
        /* ======================================= */
        .section-header {
            text-align: center;
            margin-bottom: 60px; /* More space */
            padding-top: 40px;
        }

        .section-header .section-title {
            font-size: 3.5rem; /* Larger title */
            color: var(--primary-highlight-color);
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 20px;
            font-family: var(--font-headings);
            letter-spacing: -0.06em;
            animation: fadeInScale 1s ease-out;
        }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        .section-header .section-title::after {
            content: '';
            display: block;
            width: 100px; /* Wider underline */
            height: 6px; /* Thicker underline */
            background-color: var(--secondary-highlight-color);
            margin: 15px auto 0;
            border-radius: 3px;
            animation: expandUnderline 1s ease-out 0.5s forwards;
            transform: scaleX(0);
        }
        @keyframes expandUnderline {
            to { transform: scaleX(1); }
        }

        .section-header .section-subtitle {
            font-size: 1.3rem;
            color: var(--text-light-grey);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
            animation: fadeIn 1s ease-out 0.8s forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* FILTERS BAR */
        .filters-bar {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 50px;
            padding: 20px;
            background-color: var(--color-white);
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-light);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-group label {
            font-weight: 600;
            color: var(--color-dark);
            font-size: 1.1em;
        }
        .filter-group select, .filter-group input[type="text"] {
            padding: 10px 15px;
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-base);
            font-size: 1em;
            color: var(--color-dark);
            background-color: var(--color-light);
            transition: border-color var(--fast-transition), box-shadow var(--fast-transition);
        }
        .filter-group select:focus, .filter-group input[type="text"]:focus {
            border-color: var(--primary-highlight-color);
            box-shadow: 0 0 0 3px rgba(26, 90, 64, 0.2);
            outline: none;
        }
        .filter-group button {
            background-color: var(--primary-highlight-color);
            color: var(--color-white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius-base);
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color var(--fast-transition), transform var(--fast-transition);
        }
        .filter-group button:hover {
            background-color: var(--primary-highlight-hover);
            transform: translateY(-2px);
        }


        /* HOSPEDAJES CAROUSEL (simulated cloud effect) */
        .hospedajes-carousel-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 120px; /* Space for navigation arrows */
            overflow: hidden; /* Hide overflowing cards */
            margin-bottom: 80px;
        }

        .hospedajes-carousel {
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1200px; /* Create 3D perspective */
            gap: 0px; /* No gap, cards will overlap based on transform */
            transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 480px; /* Ajustada para tarjetas más pequeñas */
        }

        .hospedaje-card-carousel {
            background-color: var(--card-bg);
            border-radius: var(--border-radius-base);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.6s cubic-bezier(0.25, 0.8, 0.25, 1), opacity 0.6s ease-in-out, filter 0.6s ease-in-out;
            position: absolute; /* Position absolutely for stacking/carousel effect */
            width: 300px; /* Reducido para que sea más pequeño */
            height: auto;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7) translateX(0) rotateY(0); /* Default transformed */
            opacity: 0.5;
            filter: blur(2px);
            cursor: pointer;
            backface-visibility: hidden; /* Smooth 3D */
            transform-origin: center center;
            border: 1px solid rgba(0, 0, 0, 0.05);
            max-width: 90%; /* Prevent horizontal overflow on small screens */
        }

        .hospedaje-card-carousel.active {
            transform: translate(-50%, -50%) scale(1) translateX(0) rotateY(0);
            opacity: 1;
            filter: blur(0);
            box-shadow: var(--card-shadow-hover);
            z-index: 20; /* Ensure active card is on top */
        }

        .hospedaje-card-carousel.prev {
            transform: translate(-50%, -50%) scale(0.8) translateX(-320px) rotateY(15deg); /* Ajustado translateX */
            opacity: 0.7;
            filter: blur(1px);
            z-index: 10;
        }

        .hospedaje-card-carousel.next {
            transform: translate(-50%, -50%) scale(0.8) translateX(320px) rotateY(-15deg); /* Ajustado translateX */
            opacity: 0.7;
            filter: blur(1px);
            z-index: 10;
        }

        .hospedaje-card-carousel.hide-left {
            transform: translate(-50%, -50%) scale(0.6) translateX(-640px) rotateY(25deg); /* Ajustado translateX */
            opacity: 0;
            filter: blur(3px);
            z-index: 5;
        }

        .hospedaje-card-carousel.hide-right {
            transform: translate(-50%, -50%) scale(0.6) translateX(640px) rotateY(-25deg); /* Ajustado translateX */
            opacity: 0;
            filter: blur(3px);
            z-index: 5;
        }

        .hospedaje-card-carousel img {
            width: 100%;
            height: 200px; /* Altura de imagen reducida */
            object-fit: cover;
            border-bottom: 1px solid #eee;
            transition: transform 0.3s ease-in-out;
        }

        .hospedaje-card-carousel:hover img {
            transform: scale(1.03); /* Slight zoom on hover */
        }

        .hospedaje-card-content {
            padding: 20px; /* Menor padding */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .hospedaje-card-carousel h3 {
            font-size: 1.8em; /* Título más pequeño */
            color: var(--primary-highlight-color);
            margin-top: 0;
            margin-bottom: 10px; /* Menor margen */
            font-family: var(--font-headings);
            line-height: 1.2;
            text-align: center;
        }

        .hospedaje-card-carousel p {
            font-size: 0.9em; /* Texto más pequeño */
            color: var(--text-light-grey);
            margin-bottom: 8px; /* Menor margen */
            line-height: 1.4;
        }

        .hospedaje-card-carousel .stars {
            color: gold;
            margin-bottom: 10px; /* Menor margen */
            font-size: 1.1em; /* Estrellas más pequeñas */
            text-align: center;
        }
        .hospedaje-card-carousel .stars i {
            margin-right: 2px;
        }

        .hospedaje-card-carousel .price {
            font-size: 1.6em; /* Precio más pequeño */
            color: var(--secondary-highlight-color);
            font-weight: bold;
            margin-top: auto;
            margin-bottom: 15px; /* Menor margen */
            text-align: center;
            border-top: 2px dashed var(--color-grey-light);
            padding-top: 15px; /* Menor padding */
            display: flex;
            justify-content: center;
            align-items: baseline;
            gap: 3px;
        }
        .hospedaje-card-carousel .price span {
            font-size: 0.7em; /* For currency symbol */
            vertical-align: super;
        }

        .details-button {
            display: block;
            background-color: var(--primary-highlight-color);
            color: white;
            padding: 12px 20px; /* Menor padding */
            border-radius: var(--border-radius-base);
            text-decoration: none;
            text-align: center;
            font-size: 1.1em; /* Texto de botón más pequeño */
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .details-button:hover {
            background-color: var(--primary-highlight-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 80, 71, 0.8);
            color: var(--color-white);
            border: none;
            padding: 15px 10px; /* Menor padding */
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.8em; /* Íconos más pequeños */
            z-index: 30;
            transition: background-color var(--fast-transition), transform var(--fast-transition);
            box-shadow: var(--box-shadow-light);
        }
        .carousel-nav-btn:hover {
            background-color: var(--color-petrol-green);
            transform: translateY(-50%) scale(1.05);
        }
        .carousel-nav-btn.prev-btn { left: 5%; }
        .carousel-nav-btn.next-btn { right: 5%; }

        /* No results message */
        .no-hospedajes {
            text-align: center;
            font-size: 1.4em;
            color: var(--text-light-grey);
            padding: 50px;
            background-color: var(--color-white);
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-base);
            max-width: 800px;
            margin: 50px auto;
        }
        .no-hospedajes i {
            font-size: 2em;
            color: var(--color-warning);
            margin-bottom: 20px;
        }

        /* ======================================= */
        /* HOTELES ALIADOS SECTION (MODIFICADO)    */
        /* ======================================= */
        .partners-section {
            padding: 80px 0;
            background: linear-gradient(to bottom, var(--color-petrol-green) 0%, #003a33 100%); /* Degradado */
            color: var(--color-white);
            position: relative;
            overflow: hidden;
            margin-top: 80px;
        }
        .partners-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/cubes.png') repeat; /* Textura sutil */
            opacity: 0.1;
            z-index: 1;
        }
        .partners-section .container {
            position: relative;
            z-index: 2;
        }
        .partners-section .section-title {
            color: var(--color-white); /* Título blanco */
        }
        .partners-section .section-title::after {
            background-color: var(--color-accent-blue); /* Underline azul acento */
        }
        .partners-section .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Modificaciones para la grilla de aliados */
        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Mostrar 3 o 4 por fila en pantallas grandes */
            gap: 50px; /* Mayor separación entre tarjetas */
            margin-top: 60px;
            justify-items: center;
            align-items: stretch; /* Estirar tarjetas para que tengan la misma altura */
        }

        .partner-card {
            background-color: rgba(255, 255, 255, 0.08); /* Fondo semi-transparente */
            backdrop-filter: blur(5px); /* Efecto de cristal */
            border-radius: var(--border-radius-base);
            padding: 35px; /* Mayor padding */
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease-out, background-color 0.4s ease, box-shadow 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%; /* Ocupa el ancho disponible en la columna */
            max-width: 350px; /* Limitar ancho máximo para un look uniforme */
            cursor: pointer;
            display: flex; /* Flexbox para centrar contenido */
            flex-direction: column;
            justify-content: space-between; /* Espacio entre logo, texto y botón */
        }
        .partner-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(45deg);
            transition: transform 0.8s ease-out;
            opacity: 0;
            z-index: -1; /* Para que no cubra el contenido */
        }
        .partner-card:hover::before {
            transform: rotate(0deg);
            opacity: 1;
        }

        .partner-card:hover {
            transform: translateY(-12px) scale(1.04); /* Elevación más pronunciada */
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45); /* Sombra más intensa */
        }

        .partner-card img {
            max-width: 180px; /* Tamaño del logo AUMENTADO */
            height: 80px; /* Altura fija para uniformidad, manteniendo aspect ratio */
            object-fit: contain; /* Para que el logo no se distorsione */
            margin: 0 auto 25px auto; /* Centrado y más margen inferior */
            filter: brightness(0) invert(1) opacity(0.85); /* Logos blancos y sutiles */
            transition: filter 0.4s ease-out;
        }
        .partner-card:hover img {
            filter: brightness(1) invert(0) opacity(1); /* Logo a color original o más vibrante */
        }

        .partner-card h4 {
            font-size: 1.6em; /* Título AUMENTADO */
            color: var(--color-white);
            margin-bottom: 15px;
            font-weight: 700; /* Más negrita */
            font-family: var(--font-headings);
        }
        .partner-card p {
            font-size: 1.05em; /* Texto AUMENTADO */
            color: rgba(255, 255, 255, 0.75); /* Ligeramente más legible */
            line-height: 1.5;
            margin-bottom: 20px; /* Más margen */
            min-height: 70px; /* Altura mínima para descripción */
            display: -webkit-box;
            -webkit-line-clamp: 4; /* Limita a 4 líneas */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .partner-card .read-more {
            color: var(--color-accent-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 1em;
            margin-top: auto; /* Empuja el botón hacia abajo */
            display: inline-block;
            padding: 8px 15px;
            border: 2px solid var(--color-accent-blue);
            border-radius: var(--border-radius-base);
            transition: background-color var(--fast-transition), color var(--fast-transition), transform var(--fast-transition);
        }
        .partner-card .read-more:hover {
            color: var(--color-white);
            background-color: var(--color-accent-blue);
            transform: translateY(-2px);
        }

        /* Partner Modal (Pop-up for details) - Sin cambios, mantiene el mismo estilo */
        .partner-modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 10000; /* On top of everything */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7); /* Dark overlay */
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity var(--fast-transition);
        }
        .partner-modal.show {
            display: flex;
            opacity: 1;
        }
        .partner-modal-content {
            background-color: var(--color-white);
            margin: auto;
            padding: 30px;
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-base);
            width: 90%;
            max-width: 600px;
            position: relative;
            transform: translateY(20px);
            opacity: 0;
            animation: slideInUp 0.3s forwards ease-out;
        }
        @keyframes slideInUp {
            to { transform: translateY(0); opacity: 1; }
        }
        .partner-modal.show .partner-modal-content {
            animation: slideInUp 0.3s forwards ease-out;
        }
        .partner-modal .close-button {
            color: var(--color-dark);
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color var(--fast-transition);
        }
        .partner-modal .close-button:hover,
        .partner-modal .close-button:focus {
            color: var(--color-danger);
            text-decoration: none;
        }
        .partner-modal-content h3 {
            color: var(--primary-highlight-color);
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 2em;
            text-align: center;
        }
        .partner-modal-content img {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 180px;
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-light);
        }
        .partner-modal-content p {
            color: var(--color-dark);
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: center;
        }
        .partner-modal-content .modal-link {
            display: block;
            text-align: center;
            background-color: var(--color-accent-blue);
            color: var(--color-white);
            padding: 12px 25px;
            border-radius: var(--border-radius-base);
            text-decoration: none;
            font-weight: 600;
            transition: background-color var(--fast-transition);
        }
        .partner-modal-content .modal-link:hover {
            background-color: #008c9e; /* Darker accent blue */
        }

        /* ======================================= */
        /* FOOTER STYLES                           */
        /* ======================================= */
        .footer {
            background-color: var(--color-petrol-green);
            color: var(--color-white);
            padding: 50px 20px; /* More padding */
            text-align: center;
            font-size: 15px;
            margin-top: 80px; /* Add margin to separate from content */
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Slightly wider min-width */
            gap: 40px; /* More gap */
            text-align: left;
            margin-bottom: 40px; /* More margin */
        }

        .footer-section h3 {
            font-size: 19px; /* Slightly larger */
            margin-bottom: 20px; /* More space */
            color: rgb(110, 192, 182); /* A lighter, more vibrant green for footer headings */
            position: relative;
        }
        .footer-section h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 40px; /* Short underline */
            height: 3px;
            background-color: var(--color-accent-blue);
            border-radius: 2px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 12px; /* More space */
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.85); /* Slightly less transparent */
            text-decoration: none;
            transition: color 0.3s ease, transform 0.2s ease;
            display: inline-block;
        }

        .footer-section ul li a:hover {
            color: var(--color-white);
            transform: translateX(5px); /* Slight slide on hover */
        }

        .footer-social-icons {
            display: flex;
            gap: 20px; /* More space */
            justify-content: flex-start; /* Align left with text */
            margin-top: 20px;
        }

        .footer-social-icons a {
            color: var(--color-white);
            font-size: 26px; /* Larger icons */
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .footer-social-icons a:hover {
            color: var(--color-accent-blue); /* Accent color on hover */
            transform: translateY(-5px) rotate(5deg); /* Bounce and slight rotate */
        }

        .footer-bottom-text {
            border-top: 1px solid rgba(255, 255, 255, 0.25); /* Thicker, less transparent border */
            padding-top: 25px; /* More padding */
            margin-top: 30px; /* More margin */
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* ======================================= */
        /* RESPONSIVE DESIGN             */
        /* ======================================= */
        @media (max-width: 1200px) {
            .hospedaje-card-carousel { width: 280px; } /* Más pequeño */
            .hospedaje-card-carousel.prev { transform: translate(-50%, -50%) scale(0.7) translateX(-280px) rotateY(15deg); }
            .hospedaje-card-carousel.next { transform: translate(-50%, -50%) scale(0.7) translateX(280px) rotateY(-15deg); }
            .hospedaje-card-carousel.hide-left { transform: translate(-50%, -50%) scale(0.5) translateX(-560px) rotateY(25deg); }
            .hospedaje-card-carousel.hide-right { transform: translate(-50%, -50%) scale(0.5) translateX(560px) rotateY(-25deg); }
            .hospedaje-card-carousel img { height: 180px; } /* Imagen más pequeña */
            .hospedaje-card-carousel h3 { font-size: 1.6em; }
            .hospedaje-card-carousel p { font-size: 0.85em; }
            .hospedaje-card-carousel .price { font-size: 1.5em; }
            .details-button { padding: 10px 18px; font-size: 1em; }
            /* Partners */
            .partners-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* 2 a 3 por fila */
            }
            .partner-card {
                max-width: 300px;
                padding: 30px;
            }
            .partner-card img { max-width: 150px; }
            .partner-card h4 { font-size: 1.5em; }
            .partner-card p { font-size: 1em; }
        }

        @media (max-width: 1024px) {
            .nav-menu {
                gap: 0.8rem;
                justify-content: space-around;
            }
            .nav-item {
                padding: 0.6rem 1rem;
            }
            .nav-icon {
                font-size: 18px;
            }
            .nav-text {
                font-size: 12px;
            }
            .hospedaje-card-carousel { width: 260px; height: auto; } /* Más pequeño aún */
            .hospedaje-card-carousel.prev { transform: translate(-50%, -50%) scale(0.6) translateX(-220px) rotateY(10deg); } /* Ajustado */
            .hospedaje-card-carousel.next { transform: translate(-50%, -50%) scale(0.6) translateX(220px) rotateY(-10deg); } /* Ajustado */
            .carousel-nav-btn { font-size: 1.6em; padding: 12px 8px; } /* Botones más pequeños */
            /* Partners */
            .partners-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* 2 por fila, más grandes */
                gap: 40px;
            }
            .partner-card {
                max-width: 280px;
                padding: 25px;
            }
            .partner-card img { max-width: 130px; }
            .partner-card h4 { font-size: 1.4em; }
            .partner-card p { font-size: 0.95em; }

            .section-header .section-title { font-size: 3rem; }
            .section-header .section-subtitle { font-size: 1.1rem; }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: center;
                padding: 0 1rem;
            }
            .logo-section, .user-section, .nav-menu {
                width: 100%;
                margin-bottom: 15px;
                justify-content: center;
            }
            .nav-menu {
                flex-wrap: wrap; /* Allow wrapping of nav items on small screens */
                gap: 0.5rem;
            }
            .nav-item {
                flex-basis: auto; /* Allow nav items to shrink */
                padding: 0.5rem 0.8rem;
            }
            .currency-selector, .header-right {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
            .user-section {
                flex-direction: column;
                gap: 10px;
            }
            .page-title {
                font-size: 2.8em;
            }
            .hospedajes-carousel-wrapper {
                padding-bottom: 60px;
            }
            .hospedajes-carousel {
                min-height: 450px; /* Ajustada para el móvil */
            }
            .hospedaje-card-carousel {
                width: 90%; /* Max out width */
                height: auto;
                transform: translate(-50%, -50%) scale(1) translateX(0) rotateY(0) !important; /* Force active card only */
                opacity: 1 !important;
                filter: blur(0) !important;
                position: relative; /* Change to relative for single column */
                margin-bottom: 40px; /* Space between cards */
            }
            .hospedaje-card-carousel.prev, .hospedaje-card-carousel.next,
            .hospedaje-card-carousel.hide-left, .hospedaje-card-carousel.hide-right {
                display: none; /* Hide other cards on small screens */
            }
            .carousel-nav-btn {
                display: none; /* Hide nav buttons on small screens */
            }
            .filters-bar {
                flex-direction: column;
                gap: 15px;
            }
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .footer-section ul {
                padding-left: 0;
            }
            .footer-social-icons {
                justify-content: center;
            }
            .footer-section h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            /* Partners */
            .partners-grid {
                grid-template-columns: 1fr; /* Una columna en móviles */
                gap: 30px;
            }
            .partner-card {
                max-width: 320px; /* Un poco más de ancho en móvil */
                padding: 25px;
            }
            .partner-card img { max-width: 140px; }
            .partner-card h4 { font-size: 1.5em; }
            .partner-card p { font-size: 1em; }
        }

        @media (max-width: 480px) {
            .section-header .section-title { font-size: 2.2rem; }
            .section-header .section-subtitle { font-size: 1em; }
            .hospedaje-card-content { padding: 15px; } /* Menor padding */
            .hospedaje-card-carousel h3 { font-size: 1.5em; margin-bottom: 8px; } /* Más pequeño */
            .hospedaje-card-carousel p { font-size: 0.8em; margin-bottom: 6px; } /* Más pequeño */
            .hospedaje-card-carousel .stars { font-size: 1em; margin-bottom: 8px; } /* Más pequeño */
            .hospedaje-card-carousel .price { font-size: 1.4em; padding-top: 10px; margin-bottom: 10px; } /* Más pequeño */
            .details-button { padding: 8px 15px; font-size: 1em; } /* Más pequeño */
            .partners-section { padding: 40px 0; }
            .partners-grid { gap: 25px; }
            .partner-card { padding: 15px; }
            .partner-modal-content { padding: 20px; }
            .footer-content { gap: 25px; }
            /* Partners */
            .partner-card img { max-width: 120px; } /* Ajuste final para logos */
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content container">
            <div class="logo-section">
                <div class="logo-img"><img src="../Images/logomapache-removebg-preview.png" alt="MangueAR Logo"></div>
                <div>
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
                <div class="header-right">
                    <div class="header-icon" id="favorites-link">
                        <i class="fas fa-heart"></i>
                        <span class="icon-count" id="favorites-count">0</span>
                    </div>
                    <div class="header-icon" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="icon-count" id="cart-count">0</span>
                    </div>
                    <div class="profile-menu">
                        <img src="../Images/perfil_usuario.png" id="profile-picture-trigger" class="profile-picture" alt="Profile Picture">
                        <div class="profile-dropdown" id="profile-dropdown-menu">
                            <a href="#"><i class="fas fa-globe"></i> Idioma</a>
                            <a href="#"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="#"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                            <a href="#" id="change-profile-image-btn"><i class="fas fa-image"></i> Cambiar Imagen</a>
                            <a href="#"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="hospedajes-section">
        <div class="section-header">
            <h1 class="section-title">Encuentra Tu Aventura</h1>
            <p class="section-subtitle">Descubre la diversidad de nuestros hospedajes, desde lujosos hoteles hasta acogedoras cabañas. Tu estadía perfecta te espera.</p>
        </div>

        <?php if ($message): // Muestra mensajes si existen ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="filters-bar">
            <div class="filter-group">
                <label for="location-filter"><i class="fas fa-map-marker-alt"></i> Ubicación:</label>
                <input type="text" id="location-filter" placeholder="Ej. Bariloche, CABA">
            </div>
            <div class="filter-group">
                <label for="category-filter"><i class="fas fa-tag"></i> Categoría:</label>
                <select id="category-filter">
                    <option value="">Todas</option>
                    <option value="hotel">Hotel</option>
                    <option value="departamento">Departamento</option>
                    <option value="cabaña">Cabaña</option>
                    <option value="hostel">Hostel</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="stars-filter"><i class="fas fa-star"></i> Estrellas:</label>
                <select id="stars-filter">
                    <option value="">Todas</option>
                    <option value="5">5 Estrellas</option>
                    <option value="4">4 Estrellas</option>
                    <option value="3">3 Estrellas</option>
                    <option value="2">2 Estrellas</option>
                    <option value="1">1 Estrella</option>
                </select>
            </div>
            <button id="apply-filters-btn"><i class="fas fa-search"></i> Buscar</button>
        </div>


        <div class="hospedajes-carousel-wrapper">
            <?php if (!empty($hospedajes)): ?>
                <div class="hospedajes-carousel" id="hospedajesCarousel">
                    <?php foreach ($hospedajes as $index => $hospedaje): ?>
                        <div class="hospedaje-card-carousel" data-index="<?php echo $index; ?>">
                            <img src="<?php echo htmlspecialchars($hospedaje['imagen_url'] ?? 'https://via.placeholder.com/600x400?text=Sin+Imagen'); ?>" alt="Imagen de <?php echo htmlspecialchars($hospedaje['nombre']); ?>">
                            <div class="hospedaje-card-content">
                                <h3><?php echo htmlspecialchars($hospedaje['nombre']); ?></h3>
                                <p><i class="fas fa-map-marker-alt"></i> <strong>Ubicación:</strong> <?php echo htmlspecialchars($hospedaje['ubicacion']); ?></p>
                                <p><i class="fas fa-hotel"></i> <strong>Categoría:</strong> <?php echo htmlspecialchars(ucfirst($hospedaje['categoria'])); ?></p>
                                <?php if ($hospedaje['estrellas']): ?>
                                    <p class="stars">
                                        <?php for ($i = 0; $i < $hospedaje['estrellas']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($hospedaje['servicios']): ?>
                                    <p><i class="fas fa-concierge-bell"></i> <strong>Servicios:</strong> <?php echo htmlspecialchars($hospedaje['servicios']); ?></p>
                                <?php endif; ?>
                                <p><?php echo htmlspecialchars(substr($hospedaje['descripcion'], 0, 120)); ?>...</p>
                                <div class="price"><span>$</span><?php echo number_format($hospedaje['precio_por_noche'], 2); ?> / noche</div>
                                <a href="detalle_hospedaje.php?id=<?php echo htmlspecialchars($hospedaje['id']); ?>" class="details-button">Explorar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-nav-btn prev-btn" id="prevHospedaje"><i class="fas fa-chevron-left"></i></button>
                <button class="carousel-nav-btn next-btn" id="nextHospedaje"><i class="fas fa-chevron-right"></i></button>
            <?php else: ?>
                <div class="no-hospedajes">
                    <i class="fas fa-frown-open"></i>
                    <p>No hay hospedajes disponibles en este momento. ¡Vuelve pronto!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="partners-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nuestros Aliados Estratégicos</h2>
                <p class="section-subtitle">Trabajamos codo a codo con los mejores hoteles y cadenas para ofrecerte experiencias inolvidables y las tarifas más competitivas. Conoce a quienes hacen posible tus sueños de viaje.</p>
            </div>

            <div class="partners-grid">
                <?php foreach ($hoteles_aliados as $partner): ?>
                    <div class="partner-card"
                         data-name="<?php echo htmlspecialchars($partner['nombre']); ?>"
                         data-logo="<?php echo htmlspecialchars($partner['logo_url']); ?>"
                         data-description="<?php echo htmlspecialchars($partner['descripcion']); ?>"
                         data-url="<?php echo htmlspecialchars($partner['web_url']); ?>">
                        <img src="<?php echo htmlspecialchars($partner['logo_url']); ?>" alt="Logo de <?php echo htmlspecialchars($partner['nombre']); ?>">
                        <h4><?php echo htmlspecialchars($partner['nombre']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($partner['descripcion'], 0, 120)); ?>...</p>
                        <a href="#" class="read-more partner-modal-trigger">Ver más <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div id="partnerDetailModal" class="partner-modal">
        <div class="partner-modal-content">
            <span class="close-button">&times;</span>
            <img id="modalPartnerLogo" src="" alt="Partner Logo">
            <h3 id="modalPartnerName"></h3>
            <p id="modalPartnerDescription"></p>
            <a id="modalPartnerLink" href="#" target="_blank" class="modal-link">Visitar Sitio Web <i class="fas fa-external-link-alt"></i></a>
        </div>
    </div>


    <footer class="footer">
        <div class="footer-content container">
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
            &copy; <?php echo date("Y"); ?> MangueAR. Todos los derechos reservados.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header Scroll Effect
            const header = document.querySelector('.header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Profile Dropdown Logic
            const profilePictureTrigger = document.getElementById('profile-picture-trigger');
            const profileDropdownMenu = document.getElementById('profile-dropdown-menu');

            if (profilePictureTrigger && profileDropdownMenu) {
                profilePictureTrigger.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileDropdownMenu.classList.toggle('active');
                });

                document.addEventListener('click', function(event) {
                    if (!profileDropdownMenu.contains(event.target) && !profilePictureTrigger.contains(event.target)) {
                        profileDropdownMenu.classList.remove('active');
                    }
                });
            }

            // Hospedajes Carousel Logic (Simulated 3D cloud)
            const hospedajesCarousel = document.getElementById('hospedajesCarousel');
            const cards = Array.from(hospedajesCarousel.getElementsByClassName('hospedaje-card-carousel'));
            let currentIndex = 0;

            function updateCarouselClasses() {
                const numCards = cards.length;
                if (numCards === 0) return;

                cards.forEach((card, i) => {
                    card.classList.remove('active', 'prev', 'next', 'hide-left', 'hide-right');
                    let classToAdd = '';

                    // Determine the card's position relative to the active card
                    const diff = (i - currentIndex + numCards) % numCards;
                    if (diff === 0) {
                        classToAdd = 'active';
                    } else if (diff === 1 || (diff === 1 - numCards && numCards > 2)) { // Handles wrapping for 'next'
                        classToAdd = 'next';
                    } else if (diff === numCards - 1 || (diff === -1 && numCards > 2)) { // Handles wrapping for 'prev'
                        classToAdd = 'prev';
                    } else if (diff === 2 || (diff === 2 - numCards && numCards > 3)) { // Handles wrapping for 'hide-right'
                        classToAdd = 'hide-right';
                    } else if (diff === numCards - 2 || (diff === -2 && numCards > 3)) { // Handles wrapping for 'hide-left'
                        classToAdd = 'hide-left';
                    } else {
                        // For cards far away, ensure they are hidden and out of the flow
                        card.style.display = 'none';
                    }

                    if (classToAdd) {
                        card.classList.add(classToAdd);
                        card.style.display = 'flex'; // Ensure active/nearby cards are displayed
                    }
                });
            }

            // Initial setup
            if (cards.length > 0) {
                updateCarouselClasses();
            }

            document.getElementById('prevHospedaje')?.addEventListener('click', () => {
                if (cards.length > 0) {
                    currentIndex = (currentIndex - 1 + cards.length) % cards.length;
                    updateCarouselClasses();
                }
            });

            document.getElementById('nextHospedaje')?.addEventListener('click', () => {
                if (cards.length > 0) {
                    currentIndex = (currentIndex + 1) % cards.length;
                    updateCarouselClasses();
                }
            });

            // Partner Modal Logic
            const partnerModal = document.getElementById('partnerDetailModal');
            const closeButton = partnerModal.querySelector('.close-button');
            const modalPartnerName = document.getElementById('modalPartnerName');
            const modalPartnerLogo = document.getElementById('modalPartnerLogo');
            const modalPartnerDescription = document.getElementById('modalPartnerDescription');
            const modalPartnerLink = document.getElementById('modalPartnerLink');
            const partnerTriggers = document.querySelectorAll('.partner-modal-trigger');

            partnerTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    const card = this.closest('.partner-card');
                    modalPartnerName.textContent = card.dataset.name;
                    modalPartnerLogo.src = card.dataset.logo;
                    modalPartnerLogo.alt = `Logo de ${card.dataset.name}`;
                    modalPartnerDescription.textContent = card.dataset.description;
                    modalPartnerLink.href = card.dataset.url;

                    partnerModal.classList.add('show');
                });
            });

            closeButton.addEventListener('click', () => {
                partnerModal.classList.remove('show');
            });

            window.addEventListener('click', (event) => {
                if (event.target === partnerModal) {
                    partnerModal.classList.remove('show');
                }
            });

            // Filter functionality (basic example - this would typically involve AJAX)
            document.getElementById('apply-filters-btn')?.addEventListener('click', () => {
                const location = document.getElementById('location-filter').value;
                const category = document.getElementById('category-filter').value;
                const stars = document.getElementById('stars-filter').value;

                console.log('Filtros aplicados:', { location, category, stars });
                alert('La función de filtro es solo una demostración de UI. La lógica real para filtrar los hospedajes requeriría una nueva solicitud PHP/AJAX al servidor o filtrado del lado del cliente si todos los datos están presentes.');
                // Here, you would typically make an AJAX request to your PHP script
                // to fetch filtered results and then dynamically update the carousel.
            });
        });
    </script>
</body>
</html>