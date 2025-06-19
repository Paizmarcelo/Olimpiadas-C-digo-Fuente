<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManqueAR - Vuelos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --color-primary: #007bff; /* Azul vibrante */
            --color-secondary: #6c757d; /* Gris secundario */
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

        body {
            font-family: var(--font-family-base);
            margin: 0;
            padding: 0;
            background-color: var(--color-light);
            color: var(--color-dark);
            line-height: var(--line-height-base);
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

/* Hamburger para móvil */
.hamburger {
    display: none; /* Oculto por defecto en desktop */
    flex-direction: column;
    justify-content: space-between;
    width: 30px;
    height: 20px;
    cursor: pointer;
    z-index: 1001; /* Asegura que esté por encima del menú */
}

.hamburger .bar {
    height: 3px;
    width: 100%;
    background-color: var(--color-black);
    border-radius: 10px;
    transition: all 0.3s ease-in-out;
}

.hamburger.active .bar:nth-child(2) {
    opacity: 0;
}

.hamburger.active .bar:nth-child(1) {
    transform: translateY(8.5px) rotate(45deg);
}

.hamburger.active .bar:nth-child(3) {
    transform: translateY(-8.5px) rotate(-45deg);
}

/* Iconos de la derecha del header */
.header-right {
    display: flex;
    align-items: center;
    gap: 20px; /* Espacio entre iconos */
}

.header-icon {
    position: relative;
    font-size: 1.3rem;
    color: white;
    cursor: pointer;
    transition: color var(--transition-speed), transform var(--transition-speed);
}

.header-icon:hover {
    color: rgb(31, 104, 206);
    transform: scale(1.1);
}

.icon-count {
    position: absolute;
    top: -8px;
    right: -8px;
    color: var(--color-white);
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 50%;
    padding: 1px 2px;
    min-width: 17px;
    text-align: center;
    line-height: 1;
    border: 1px solid var(--color-white); /* Borde blanco para mayor visibilidad */
}

.profile-menu {
    position: relative;
    cursor: pointer;
}

.profile-picture {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--color-primary);
    transition: transform var(--transition-speed);
}

.profile-picture:hover {
    transform: scale(1.05);
}

.profile-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 10px); /* Debajo de la imagen */
    right: 0;
    background-color: var(--color-white);
    box-shadow: var(--box-shadow-light);
    border-radius: var(--border-radius-base);
    min-width: 150px;
    overflow: hidden;
    z-index: 999;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity var(--transition-speed), transform var(--transition-speed);
}

.profile-dropdown.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.profile-dropdown a {
    display: block;
    padding: 10px 15px;
    color: var(--color-black);
    font-size: 0.95rem;
    transition: background-color var(--transition-speed);
}

.profile-dropdown a:hover {
    background-color: var(--color-grey-light);
    color: var(--color-primary);
}

        /* Hero Section */
        .hero {
            background-image: url('../Images/pexels-ahmetyuksek-30214899.jpg'); /* Placeholder background image */
            background-size: cover;
            background-position: center;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            text-align: center;
            position: relative;
        }

        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.4);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .hero-content {
            z-index: 1;
            padding: var(--spacing-md);
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: var(--spacing-sm);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-lg);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Flight Search Form */
        .flight-search-container {
            background-color: var(--color-white);
            padding: var(--spacing-lg);
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-base);
            max-width: 900px;
            width: 100%;
            margin: -80px auto var(--spacing-lg); /* Adjust margin to overlap with hero */
            position: relative;
            z-index: 2;
        }

        .flight-type-selector {
            display: flex;
            justify-content: center;
            margin-bottom: var(--spacing-md);
            border-bottom: 1px solid var(--color-border);
            padding-bottom: var(--spacing-sm);
        }

        .flight-type-selector button {
            background-color: transparent;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            color: var(--color-dark);
            border-radius: var(--border-radius-base);
            transition: background-color var(--transition-speed), color var(--transition-speed);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flight-type-selector button.active {
            background-color: var(--color-primary);
            color: var(--color-white);
        }

        .flight-type-selector button:hover:not(.active) {
            background-color: var(--color-grey-light);
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-md);
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.9rem;
            color: var(--color-dark);
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px 15px;
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-base);
            font-size: 1rem;
            color: var(--color-dark);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group .input-icon {
            position: relative;
        }

        .form-group .input-icon input {
            padding-right: 40px;
        }

        .form-group .input-icon .icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-grey-dark);
        }

        .add-segment-btn {
            background-color: var(--color-info);
            color: var(--color-white);
            border: none;
            padding: 10px 15px;
            border-radius: var(--border-radius-base);
            cursor: pointer;
            font-size: 1rem;
            transition: background-color var(--transition-speed);
            margin-top: var(--spacing-md);
        }

        .add-segment-btn:hover {
            background-color: #1391a7;
        }

        .search-flights-btn {
            grid-column: 1 / -1; /* Spans all columns */
            background-color: var(--color-petrol-green);
            color: var(--color-white);
            border: none;
            padding: 15px 30px;
            border-radius: var(--border-radius-base);
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color var(--transition-speed);
            margin-top: var(--spacing-md);
        }

        .search-flights-btn:hover {
            background-color:  #005047;
        }

        /* Flight Listing Section */
        .flights-listing {
            padding: var(--spacing-lg) 0;
            max-width: 1200px;
            margin: var(--spacing-md) auto;
        }

        .flights-listing h2 {
            text-align: center;
            font-size: 2.5rem;
            color: var(--color-petrol-green);
            margin-bottom: var(--spacing-lg);
            position: relative;
            padding-bottom: 15px;
        }

        .flights-listing h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--color-primary);
            margin: 10px auto 0;
            border-radius: 2px;
        }

        .flight-card-container {
            display: grid;
            grid-template-columns: 1fr; /* Single column by default */
            gap: var(--spacing-md);
        }

        /* NEW FLIGHT CARD STYLES */
       /* Novedad: Contenedor para el gradiente de superposición */
        
        /* Flight Listing Section */
     /* Hero Section (sin cambios) */
        /* ... */

        /* Flight Search Form (sin cambios) */
        /* ... */

        /* Flight Listing Section (AJUSTADO: para limitar el ancho y centrar las tarjetas) */
        .flights-listing {
            padding: var(--spacing-lg) 0;
            max-width: 1000px; /* ANCHO MÁXIMO para las tarjetas (ajusta si quieres que sea más pequeño) */
            margin: var(--spacing-md) auto; /* Centra el contenedor */
        }

        .flights-listing h2 {
            text-align: center;
            font-size: 2.5rem;
            color: var(--color-petrol-green);
            margin-bottom: var(--spacing-lg);
            position: relative;
            padding-bottom: 15px;
        }

        .flights-listing h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--color-primary);
            margin: 10px auto 0;
            border-radius: 2px;
        }

        .flight-card-container {
            display: grid;
            grid-template-columns: 1fr; /* Una columna por defecto */
            gap: var(--spacing-md);
        }

        /* Contenedor para el gradiente de superposición */
        .image-gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Asegura que no bloquee interacciones */
            z-index: 1; /* Estará por encima de las imágenes pero debajo del contenido principal */
        }

        .image-gradient-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%; /* Ocupa la mitad izquierda */
            height: 100%;
            /* Gradiente de transparente a blanco en la derecha de la mitad izquierda */
            background: linear-gradient(to right, rgba(255,255,255,0) 70%, var(--color-white) 100%);
        }

        .image-gradient-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%; /* Ocupa la mitad derecha */
            height: 100%;
            /* Gradiente de transparente a blanco en la izquierda de la mitad derecha */
            background: linear-gradient(to left, rgba(255,255,255,0) 70%, var(--color-white) 100%);
        }

        /* REESTRUCTURACIÓN DE FLIGHT CARD STYLES */
        .flight-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius-base);
            box-shadow: var(--box-shadow-light);
            display: flex;
            align-items: stretch;
            padding: var(--spacing-md); /* Añade un poco de padding interno si lo deseas */
            position: relative;
            overflow: hidden;
            border: 1px solid var(--color-border);
            height: 250px;
            z-index: 0; /* Por defecto, para permitir que se superpongan cosas con z-index más alto */
        }

        /* Contenedor de imágenes, ahora con imágenes individuales */
        .flight-card-images {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            z-index: 0; /* Asegura que las imágenes estén debajo del overlay y el contenido */
        }

        .flight-card-images img {
            flex: 1; /* Cada imagen ocupa el 50% */
            width: 50%; /* Asegura el 50% */
            height: 100%;
            object-fit: cover; /* Ajusta la imagen sin distorsionarla */
            display: block; /* Elimina espacio extra debajo de las imágenes */
        }

        /* El contenido principal, por encima de las imágenes y el gradiente */
        .flight-card-content {
            display: flex;
            flex-grow: 1;
            position: relative;
            z-index: 2; /* MUY IMPORTANTE: Asegura que el contenido esté POR ENCIMA de todo lo demás */
            justify-content: space-between; /* Distribuye el contenido en el centro */
            /* Añadimos un pequeño fondo semitransparente al contenido para que resalte más */
            background-color: rgba(255, 255, 255, 0.2); /* Un ligero fondo blanco transparente */
            border-radius: var(--border-radius-base); /* Para que no se vea cortado en los bordes */
            padding: var(--spacing-sm) 0; /* Un poco de padding para que el contenido no pegue a los bordes */
        }


        .flight-card-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 var(--spacing-sm);
            
        }

        .flight-card-info.origin {
                  font-size: 2rem;
             text-shadow: 5px 5px 9px rgba(0, 0, 0, 1);
            align-items: flex-start;
            text-align: left;
            padding-left: var(--spacing-md);
        }

        .flight-card-info.destination {
              font-size: 2rem;
             text-shadow: 5px 5px 9px rgba(0, 0, 0, 1);
            align-items: flex-end;
            text-align: right;
            padding-right: var(--spacing-md);
     
           
        }

        .flight-card-city {
            font-size: 2rem;
             text-shadow: 5px 5px 9px rgba(0, 0, 0, 1);
            font-weight: bold;
            color: white; /* Color más oscuro para mejor contraste */
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.4); /* Sombra más pronunciada */
             background-color: rgba(35, 34, 34, 0.69);
             border-radius:10px;
        }

        .flight-card-description {
            font-size: 0.9rem;
            color: white; /* Color más oscuro para mejor contraste */
            margin-bottom: var(--spacing-sm);
              background-color: rgba(0, 0, 0, 0.14);
            max-width: 250px;
            text-shadow: 0.5px 0.5px 2px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
        }

        .flight-card-price {
            font-size: 2.2rem;
            font-weight: bold;
             background-color: #005047;
            color: rgb(255, 255, 255);
            text-shadow: 1px 1px 3px rgba(0,0,0,0.4); /* Sombra más pronunciada */
            padding:3px 9px;
            border-radius: 20px;
        }

        /* El camino del avión con el avión y la duración */
        .flight-card-airplane-path {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 0 var(--spacing-md);
            z-index: 3; /* Más alto que el contenido para que el avión esté 'en el aire' */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            width: 100%;
            height: 100%;
        }

        .flight-airplane-icon {
            font-size: 2rem;
            color: var(--color-white);
            background-color: #005047; /* Fondo más oscuro para el avión */
            border-radius: 50%;
            padding: 10px;
            transform: rotate(90deg);
            animation: fly-across 10s linear infinite;
            filter: drop-shadow(0 0 8px rgba(0,0,0,0.7)); /* Sombra más grande para el avión */
        }

        @keyframes fly-across {
            0% { transform: translateX(-500%) rotate(0deg); }
            10% { transform: translateX(-400%) rotate(15deg); }
           20% { transform: translateX(-300%) rotate(0deg); }
             30% { transform: translateX(-200%) rotate(-15deg); }
              40% { transform: translateX(-100%) rotate(0deg); }
               50% { transform: translateX(0%) rotate(0deg); }
            60% { transform: translateX(100%) rotate(-15deg); }
             70% { transform: translateX(200%) rotate(15deg); }
              80% { transform: translateX(300%) rotate(-15deg); }
              90% { transform: translateX(400%) rotate(15deg); }
                100% { transform: translateX(500%) rotate(0deg); }
        }

        .flight-duration-display {
            background-color: rgba(255, 255, 255, 0.2); /* Fondo casi blanco para la duración */
            padding: 5px 12px; /* Un poco más de padding */
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--color-dark);
            white-space: nowrap;
            box-shadow: var(--box-shadow-light);
            margin-top: var(--spacing-sm);
        }

        .flight-card-actions {
            display: flex;
            gap: 10px;
            margin-top: var(--spacing-md);
            z-index: 4; /* Asegurar que los botones estén por encima de todo */
        }

        .flight-card-actions .btn {
            margin-top:80px;
            padding: 10px 15px;
            border-radius: var(--border-radius-base);
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        .flight-card-actions .btn-favorite {
            background-color: rgba(255, 255, 255, 0.4); /* Fondo semitransparente para el botón */
            border: 1px solid var(--color-secondary);
            color:  rgba(255, 0, 0, 0.81);
        }

        .flight-card-actions .btn-reserve {
            background-color:  #005047;
            border: 1px solidrgb(2, 48, 42);
            color: var(--color-white);
        }

        /* Media Queries (revisados para que no afecten negativamente) */
        @media (max-width: 992px) {
            /* ... (mantener media queries existentes y ajustarlos si es necesario) ... */
            .flights-listing {
                max-width: 90%; /* Ajustar el ancho en pantallas medianas */
            }

            .flight-card {
                height: auto;
                flex-direction: column;
                text-align: center;
            }

            .flight-card-images {
                position: relative;
                height: 200px;
            }

            .image-gradient-overlay {
                display: none; /* Ocultar el gradiente en móviles para simplificar */
            }

            .flight-card-content {
                flex-direction: column;
                gap: var(--spacing-md);
                padding: var(--spacing-md);
                z-index: 1;
                background-color: var(--color-white); /* En móvil, quizás un fondo sólido es mejor */
            }

            .flight-card-info.origin,
            .flight-card-info.destination {
                align-items: center;
                text-align: center;
                padding: 0;
            }

            .flight-card-airplane-path {
                position: static;
                transform: none;
                flex-direction: row;
                gap: 10px;
                margin-top: var(--spacing-md);
                width: auto;
                height: auto;
                justify-content: center;
            }

            .flight-airplane-icon {
                transform: rotate(0deg);
                animation: none;
                margin-bottom: 0;
            }

            .flight-duration-display {
                margin-top: 0;
            }
        }

        @media (max-width: 576px) {
            .flights-listing {
                max-width: 95%; /* Un poco más de ancho en móviles muy pequeños */
            }
            /* ... (rest of mobile specific adjustments) ... */
        }

        @media (max-width: 576px) {
            .hero {
                height: 350px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .flight-search-container {
                margin-top: -40px;
                padding: var(--spacing-sm);
            }

            .flight-type-selector button {
                font-size: 0.85rem;
                padding: 6px 10px;
            }

            .form-group input,
            .form-group select {
                padding: 8px 10px;
                font-size: 0.9rem;
            }

            .add-segment-btn,
            .search-flights-btn {
                font-size: 0.9rem;
                padding: 10px 15px;
            }

            .flights-listing h2 {
                font-size: 2rem;
            }

            .flight-card-city {
                font-size: 1.5rem;
            }

            .flight-card-price {
                font-size: 1.8rem;
            }

            .flight-card-actions {
                flex-direction: column;
                gap: 5px;
            }
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
    </style>
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
                    <div class="header-icon" id="favorites-link">
                        <i class="fas fa-heart"></i>
                        <span class="icon-count" id="favorites-count">0</span>
                    </div>
                    <div class="header-icon" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="icon-count" id="cart-count">0</span>
                    </div>
                    <div class="profile-menu">
                        <img src="../Images/perfil_usuario.png" id="profile-picture-trigger" class="profile-picture">
                        <div class="profile-dropdown" id="profile-dropdown-menu">
                            <a href="#">Idioma</a>
                            <a href="#">Ajustes</a>
                            <a href="#">Mi Perfil</a>
                            <a href="#" id="change-profile-image-btn">Cambiar Imagen</a>
                            <a href="#">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Encuentra tu próximo vuelo</h1>
                <p>Busca entre miles de destinos y las mejores ofertas.</p>
            </div>
        </section>

        <div id="cart-modal-overlay" style="
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        ">
            <div class="cart-modal-content" style="
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%; /* Could be responsive */
                max-width: 600px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                position: relative;
            ">
                <span class="close-modal-btn" style="
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                    position: absolute;
                    top: 10px;
                    right: 20px;
                ">&times;</span>
                <h2 style="text-align: center; margin-bottom: 20px; color: var(--color-petrol-green);">Tu Carrito de Compras</h2>
                <div id="cart-items-list" style="
                    max-height: 400px;
                    overflow-y: auto;
                    border-bottom: 1px solid var(--color-border);
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                ">
                    </div>
                <div class="cart-total" style="
                    display: flex;
                    justify-content: space-between;
                    font-size: 1.2rem;
                    font-weight: bold;
                    margin-bottom: 20px;
                ">
                    <span>Total:</span>
                    <span id="cart-total">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; gap: 10px;">
                    <button class="btn btn-secondary" id="clear-cart-btn" style="
                        flex: 1;
                        padding: 10px 15px;
                        background-color: var(--color-secondary);
                        color: white;
                        border: none;
                        border-radius: var(--border-radius-base);
                        font-size: 1rem;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    ">Vaciar Carrito</button>
                    <button class="btn btn-primary" id="continue-shopping-btn" style="
                        flex: 1;
                        padding: 10px 15px;
                        background-color: var(--color-primary);
                        color: white;
                        border: none;
                        border-radius: var(--border-radius-base);
                        font-size: 1rem;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    ">Seguir Comprando</button>
                </div>
                <button class="btn btn-success" style="
                    display: block;
                    width: 100%;
                    padding: 10px 15px;
                    background-color: var(--color-success);
                    color: white;
                    border: none;
                    border-radius: var(--border-radius-base);
                    font-size: 1rem;
                    cursor: pointer;
                    transition: background-color 0.3s;
                    margin-top: 10px;
                ">Proceder al Pago</button>
            </div>
        </div>

        <div id="favorites-modal-overlay" style="
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        ">
            <div class="favorites-modal-content" style="
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%; /* Could be responsive */
                max-width: 600px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                position: relative;
            ">
                <span class="close-modal-btn close-favorites-modal-btn" style="
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                    position: absolute;
                    top: 10px;
                    right: 20px;
                ">&times;</span>
                <h2 style="text-align: center; margin-bottom: 20px; color: var(--color-petrol-green);">Tus Vuelos Favoritos</h2>
                <div id="favorites-items-list" style="
                    max-height: 400px;
                    overflow-y: auto;
                    border-bottom: 1px solid var(--color-border);
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                ">
                    </div>
                <div style="display: flex; justify-content: space-between; gap: 10px;">
                    <button class="btn btn-secondary" id="clear-favorites-btn" style="
                        flex: 1;
                        padding: 10px 15px;
                        background-color: var(--color-secondary);
                        color: white;
                        border: none;
                        border-radius: var(--border-radius-base);
                        font-size: 1rem;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    ">Vaciar Favoritos</button>
                    <button class="btn btn-primary" id="continue-viewing-btn" style="
                        flex: 1;
                        padding: 10px 15px;
                        background-color: var(--color-primary);
                        color: white;
                        border: none;
                        border-radius: var(--border-radius-base);
                        font-size: 1rem;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    ">Seguir Viendo</button>
                </div>
            </div>
        </div>

        <div class="flights-listing">
            <h2>Vuelos Populares</h2>
            <div class="flight-card-container">
                <div class="flight-card"
                     data-id="flight-1"
                     data-name="Buenos Aires a París"
                     data-price="899.00"
                     data-image-origin="../Images/argentinaobelico.jpg"
                     data-image-destination="../Images/franciatorre.jpg"
                     data-description="La vibrante capital argentina, famosa por el tango y su rica cultura gastronómica. | La icónica ciudad de la luz, hogar de la Torre Eiffel y una rica historia.">
                    <div class="flight-card-images">
                        <img src="../Images/argentinaobelico.jpg" alt="Origen Buenos Aires">
                        <img src="../Images/franciatorre.jpg" alt="Destino París">
                    </div>

                    <div class="image-gradient-overlay"></div>

                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Buenos Aires</div>
                            <p class="flight-card-description">La vibrante capital argentina, famosa por el tango y su rica cultura gastronómica.</p>
                            <div class="flight-card-price">$899</div>
                        </div>

                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">14h 30min</div>
                        </div>

                        <div class="flight-card-info destination">
                            <div class="flight-card-city">París</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-1"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-1"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flight-card"
                     data-id="flight-2"
                     data-name="Madrid a Tokio"
                     data-price="1299.00"
                     data-image-origin="../Images/pexels-fabian-lozano-2152897796-32469373.jpg"
                     data-image-destination="../Images/pexels-njeromin-16572489.jpg"
                     data-description="El corazón de España, con sus museos mundialmente famosos y vida nocturna inigualable. | La metrópolis futurista de Japón, donde la tradición se encuentra con la tecnología.">
                    <div class="flight-card-images">
                        <img src="../Images/pexels-fabian-lozano-2152897796-32469373.jpg" alt="Origen Madrid">
                        <img src="../Images/pexels-njeromin-16572489.jpg" alt="Destino Tokio">
                    </div>

                    <div class="image-gradient-overlay"></div>

                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Madrid</div>
                            <p class="flight-card-description">El corazón de España, con sus museos mundialmente famosos y vida nocturna inigualable.</p>
                            <div class="flight-card-price">$1,299</div>
                        </div>

                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">16h 45min</div>
                        </div>

                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Tokio</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-2"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-2"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flight-card"
                     data-id="flight-3"
                     data-name="Nueva York a Londres"
                     data-price="750.00"
                     data-image-origin="../Images/pexels-dantemunozphoto-28821762.jpg"
                     data-image-destination="../Images/pexels-m-emre_celik-2054744248-32496666.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/pexels-dantemunozphoto-28821762.jpg" alt="Origen Nueva York">
                        <img src="../Images/pexels-m-emre_celik-2054744248-32496666.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-3"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-3"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="flight-card"
                     data-id="flight-4"
                     data-name="Nueva York a Londres (segundo)"
                     data-price="750.00"
                     data-image-origin="../Images/colombo.jpg"
                     data-image-destination="../Images/disney.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/colombo.jpg" alt="Origen Nueva York">
                        <img src="../Images/disney.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-4"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-4"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="flight-card"
                     data-id="flight-5"
                     data-name="Nueva York a Londres (tercero)"
                     data-price="750.00"
                     data-image-origin="../Images/africa.jpg"
                     data-image-destination="../Images/india.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/africa.jpg" alt="Origen Nueva York">
                        <img src="../Images/india.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-5"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-5"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="flight-card"
                     data-id="flight-6"
                     data-name="Nueva York a Londres (cuarto)"
                     data-price="750.00"
                     data-image-origin="../Images/javon.jpg"
                     data-image-destination="../Images/mozart.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/javon.jpg" alt="Origen Nueva York">
                        <img src="../Images/mozart.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-6"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-6"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="flight-card"
                     data-id="flight-7"
                     data-name="Nueva York a Londres (quinto)"
                     data-price="750.00"
                     data-image-origin="../Images/limaperu.jpg"
                     data-image-destination="../Images/unidos.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/limaperu.jpg" alt="Origen Nueva York">
                        <img src="../Images/unidos.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-7"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-7"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="flight-card"
                     data-id="flight-8"
                     data-name="Nueva York a Londres (sexto)"
                     data-price="750.00"
                     data-image-origin="../Images/pexels-fabian-lozano-2152897796-32469373.jpg"
                     data-image-destination="../Images/pexels-pho-tomass-883344227-32490398.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/pexels-fabian-lozano-2152897796-32469373.jpg" alt="Origen Nueva York">
                        <img src="../Images/pexels-pho-tomass-883344227-32490398.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-8"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-8"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="flight-card"
                     data-id="flight-9"
                     data-name="Nueva York a Londres (septimo)"
                     data-price="750.00"
                     data-image-origin="../Images/pexels-pho-tomass-883344227-32490398.jpg"
                     data-image-destination="../Images/pexels-asadphoto-1450360.jpg"
                     data-description="La ciudad que nunca duerme, un crisol de culturas y oportunidades. | La capital histórica de Inglaterra, famosa por su rica herencia y lugares emblemáticos.">
                    <div class="flight-card-images">
                        <img src="../Images/pexels-pho-tomass-883344227-32490398.jpg" alt="Origen Nueva York">
                        <img src="../Images/pexels-asadphoto-1450360.jpg" alt="Destino Londres">
                    </div>
                    <div class="image-gradient-overlay"></div>
                    <div class="flight-card-content">
                        <div class="flight-card-info origin">
                            <div class="flight-card-city">Nueva York</div>
                            <p class="flight-card-description">La ciudad que nunca duerme, un crisol de culturas y oportunidades.</p>
                            <div class="flight-card-price">$750</div>
                        </div>
                        <div class="flight-card-airplane-path">
                            <i class="fas fa-plane flight-airplane-icon"></i>
                            <div class="flight-duration-display">7h 00min</div>
                        </div>
                        <div class="flight-card-info destination">
                            <div class="flight-card-city">Londres</div>
                            <div class="flight-card-actions">
                                <button class="btn btn-favorite" data-id="flight-9"><i class="far fa-heart"></i> Favoritos</button>
                                <button class="btn btn-reserve add-to-cart-btn" data-id="flight-9"><i class="fas fa-bookmark"></i> Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
   <script src="vuelos_usuario.js"></script>
</body>
</html>