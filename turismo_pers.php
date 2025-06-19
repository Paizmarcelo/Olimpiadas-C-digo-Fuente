<?php
// --- BLOQUE PHP PARA SIMULAR EL BACKEND DE STRIPE ---

// Esto se ejecuta solo cuando el JavaScript envía los datos del viaje.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recibir los datos del viaje que envió el JavaScript.
    $datosViaje = json_decode(file_get_contents('php://input'), true);

    // 2. VALIDACIÓN (En un caso real, se validaría cada dato aquí).
    // Implement robust server-side validation for all received data (e.g., destination, dates, prices)
    // to prevent tampering from the client side.
    if (!isset($datosViaje['config']) || !isset($datosViaje['precios'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
        exit;
    }

    $config = $datosViaje['config'];
    $precios = $datosViaje['precios'];

    // Basic validation example for total price
    if (!isset($precios['total']) || !is_numeric($precios['total']) || $precios['total'] <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid total price.']);
        exit;
    }

    // Convert total price to cents (Stripe expects the amount in the smallest currency unit)
    $amountInCents = round($precios['total'] * 100);

    // 3. LÓGICA DE STRIPE (Real)
    require 'vendor/autoload.php'; // Make sure this path is correct relative to your turismo_pers.php

    // Set your secret key. Replace with your actual secret key.
    \Stripe\Stripe::setApiKey('sk_test_51RZUQGPFcLpCsDmjV4Kj4RtwdJRMOY5Hh2WQzylWa0ehwGtcOV5vUNN1j3fdwblJYlxmckESihyyINB5PLJ6maaV00ZfD9ZCof'); //

    try {
        $checkout_session = \Stripe\Checkout\Session::create([ //
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // Use your desired currency
                    'product_data' => [ //
                        'name' => 'Viaje Personalizado a ' . ($config['destino'] ?? 'Tu Destino'), //
                        'description' => 'Viaje para ' . ($config['personas'] ?? 0) . ' personas (' . ($config['tipoViaje'] ?? 'Económico') . ')', //
                    ],
                    'unit_amount' => $amountInCents, //
                ],
                'quantity' => 1, //
            ]],
            'mode' => 'payment', //
            'success_url' => 'http://localhost/MangueARN/turismo_pers.php?success=true', // Replace with your actual success URL
            'cancel_url' => 'http://localhost/MangueARN/turismo_pers.php?canceled=true',   // Replace with your actual cancel URL
        ]);

        // 4. Devolver una respuesta al JavaScript con la URL de la sesión de Stripe
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Listo para redirigir a Stripe.',
            'checkoutUrl' => $checkout_session->url // This is the URL Stripe provides
        ]);
        exit;
    } catch (\Stripe\Exception\ApiErrorException $e) { //
        // Handle API errors
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); //
        exit;
    } catch (Exception $e) { //
        // Handle other errors
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']); //
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turismo Personalizado - Crea tu Viaje Ideal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Montserrat:wght@500;700;900&display=swap" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
     /* --- ESTILOS GENERALES Y RESETEO --- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    /* Paleta de colores */
    --color-primary-deep-petrol: #005F60; /* Verde Petróleo Profundo */
    --color-secondary-deep-blue: #0A2E36; /* Azul Profundo */
    --color-white: #FFFFFF;
    --color-light-gray: #F8F9FA;
    --color-medium-gray: #ECEFF1; /* Nuevo para fondos de cards */
    --color-dark-text: #2c3e50; /* Un gris oscuro para texto */
    --color-accent-gold: #D4AF37; /* Dorado/Bronce para acentos */

    /* Fuentes */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Lato', sans-serif;

    /* Sombras */
    --shadow-light: 0 5px 15px rgba(0,0,0,0.08);
    --shadow-medium: 0 10px 30px rgba(0,0,0,0.12);
    --shadow-dark: 0 15px 40px rgba(0,0,0,0.15);
}


body {
    font-family: var(--font-body);
    background: var(--color-light-gray); /* Fondo general más claro */
    min-height: 100vh;
    color: var(--color-dark-text);
    line-height: 1.6; /* Mejora la legibilidad */
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* --- HEADER Y HERO SECTION --- */


.video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -2; /* Detrás de todo */
}

#hero-video {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Asegura que el video cubra todo el espacio */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 95, 96, 0.7) 0%, rgba(10, 46, 54, 0.8) 100%);
    /* Degradado de los colores principales para oscurecer el video y mejorar el contraste del texto */
    z-index: -1;
}

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

.hero-content {
    text-align: center;
    padding: 0 20px;
    z-index: 5;
    margin-bottom: 10vh; /* Empuja el contenido hacia arriba */
}

.hero-content h1 {
    font-family: var(--font-heading);
    font-size: 4.5rem; /* Título muy grande */
    font-weight: 900;
    margin-bottom: 20px;
    text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
}

.hero-content p {
    font-family: var(--font-body);
    font-size: 1.5rem;
    margin-bottom: 40px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    opacity: 0.9;
}

.btn-hero {
    display: inline-block;
    background: var(--color-accent-gold);
    color: var(--color-secondary-deep-blue); /* Texto oscuro en el botón dorado */
    padding: 18px 40px;
    border-radius: 50px; /* Bordes más redondeados */
    font-size: 1.2rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-medium);
}

.btn-hero:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4); /* Sombra más pronunciada al hover */
    background: #FFD700; /* Un dorado ligeramente más claro al hover */
}

.btn-hero i {
    margin-left: 10px;
}

/* Hamburger Menu (Mobile) */
.hamburger-menu {
    display: none; /* Hidden by default */
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--color-white);
    cursor: pointer;
    z-index: 20;
}


/* --- CONFIGURADOR DE VIAJE --- */
.configurator {
    background: var(--color-white);
    border-radius: 25px; /* Bordes más suaves */
    padding: 40px; /* Más padding */
    box-shadow: var(--shadow-dark); /* Sombra más pronunciada */
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px; /* Más espacio entre columnas */
    margin-top: -150px; /* Se superpone con el hero para un efecto visual */
    position: relative; /* Para z-index */
    z-index: 1; /* Asegura que esté sobre el hero cuando se superpone */
}

.steps {
    display: flex;
    flex-direction: column;
    gap: 30px; /* Más espacio entre pasos */
}

.step {
    background: var(--color-medium-gray); /* Fondo de paso más claro */
    border-radius: 20px; /* Más redondeado */
    padding: 30px; /* Más padding */
    border-left: 6px solid var(--color-primary-deep-petrol); /* Borde más grueso y color principal */
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light); /* Sombra suave */
}

.step:hover {
    transform: translateY(-5px); /* Efecto de elevación más pronunciado */
    box-shadow: 0 15px 30px rgba(0, 95, 96, 0.15); /* Sombra con color del borde */
}

.step h3 {
    font-family: var(--font-heading);
    color: var(--color-primary-deep-petrol);
    margin-bottom: 20px; /* Más espacio debajo del título */
    font-size: 1.6rem;
    border-bottom: 1px solid rgba(0, 95, 96, 0.2); /* Línea sutil */
    padding-bottom: 10px;
}

.form-group {
    margin-bottom: 20px; /* Más espacio entre grupos de formulario */
}

.form-group label {
    display: block;
    margin-bottom: 8px; /* Más espacio */
    font-weight: 700; /* Más bold */
    color: var(--color-secondary-deep-blue); /* Color de label */
    font-size: 1.05rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 15px; /* Más padding */
    border: 2px solid var(--color-light-gray); /* Borde más sutil */
    border-radius: 10px; /* Más redondeado */
    font-size: 1.1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background-color: var(--color-white);
    color: var(--color-dark-text);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary-deep-petrol); /* Borde al enfocar */
    box-shadow: 0 0 0 4px rgba(0, 95, 96, 0.2); /* Sombra de enfoque sutil */
}

.checkbox-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Más ancho */
    gap: 15px; /* Más espacio */
    margin-top: 15px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 12px; /* Más espacio */
    padding: 15px; /* Más padding */
    background: var(--color-white);
    border-radius: 10px;
    border: 2px solid var(--color-light-gray);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Sombra muy suave */
}

.checkbox-item:hover {
    border-color: var(--color-primary-deep-petrol);
    background: var(--color-light-gray); /* Fondo sutil al hover */
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 95, 96, 0.1);
}

.checkbox-item input[type="checkbox"] {
    /* Oculta el checkbox nativo y lo estilizamos con el contenedor */
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.checkbox-item.selected {
    border-color: var(--color-primary-deep-petrol);
    background: rgba(0, 95, 96, 0.1); /* Fondo más tenue para seleccionado */
    box-shadow: 0 5px 15px rgba(0, 95, 96, 0.2);
}
/* Icono de verificación para checkbox seleccionado (pseudo-elemento) */
.checkbox-item.selected::after {
    content: '\f00c'; /* Font Awesome check icon */
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: var(--color-primary-deep-petrol);
    font-size: 1.2rem;
    margin-left: auto; /* Empuja el icono al final */
}


.guias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.guia-card {
    background: var(--color-white);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    border: 2px solid var(--color-light-gray);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light);
}

.guia-card:hover {
    border-color: var(--color-primary-deep-petrol);
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.guia-card.selected {
    border-color: var(--color-primary-deep-petrol);
    background: rgba(0, 95, 96, 0.1);
    box-shadow: 0 8px 20px rgba(0, 95, 96, 0.2);
}

.guia-avatar {
    width: 70px; /* Más grande */
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(45deg, var(--color-primary-deep-petrol), var(--color-secondary-deep-blue)); /* Degradado de tus colores */
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white);
    font-size: 2rem; /* Icono más grande */
    margin: 0 auto 15px; /* Más espacio */
    box-shadow: 0 5px 15px rgba(0,0,0,0.2); /* Sombra para profundidad */
}
.guia-card h4 {
    font-family: var(--font-heading);
    margin-bottom: 5px;
    color: var(--color-secondary-deep-blue);
}
.guia-card p {
    font-size: 0.9rem;
    color: #607d8b; /* Gris azulado */
    margin-bottom: 10px;
}
.guia-card small {
    font-weight: bold;
    color: var(--color-accent-gold);
}


.ruta-builder {
    margin-top: 15px;
}

.actividad-item {
    background: var(--color-white);
    border-radius: 10px;
    padding: 18px; /* Más padding */
    margin-bottom: 12px; /* Más espacio */
    border: 2px solid var(--color-light-gray);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.actividad-item:hover {
    border-color: var(--color-primary-deep-petrol);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 95, 96, 0.1);
}

.actividad-item.selected {
    border-color: var(--color-primary-deep-petrol);
    background: rgba(0, 95, 96, 0.1);
    box-shadow: 0 5px 15px rgba(0, 95, 96, 0.2);
}

.actividad-info {
    flex: 1;
}

.actividad-info h4 {
    font-family: var(--font-heading);
    font-size: 1.15rem;
    color: var(--color-secondary-deep-blue);
    margin-bottom: 5px;
}
.actividad-info h4 i {
    margin-right: 10px;
    color: var(--color-primary-deep-petrol);
}
.actividad-info p {
    font-size: 0.9rem;
    color: #607d8b;
}

.actividad-precio {
    font-weight: 700;
    color: var(--color-accent-gold);
    font-size: 1.1rem;
    margin-left: 15px;
}


/* --- RESUMEN (SUMMARY) --- */
.summary {
    background: var(--color-white);
    border: 1px solid var(--color-medium-gray);
    border-radius: 20px; /* Más redondeado */
    padding: 30px; /* Más padding */
    color: var(--color-dark-text);
    position: sticky;
    top: 30px; /* Baja un poco el sticky */
    height: fit-content;
    box-shadow: var(--shadow-medium); /* Sombra más notable */
}

.summary h3 {
    font-family: var(--font-heading);
    color: var(--color-secondary-deep-blue);
    margin-bottom: 25px; /* Más espacio */
    font-size: 1.8rem;
    border-bottom: 2px solid var(--color-medium-gray); /* Línea más marcada */
    padding-bottom: 15px;
}

.price-display {
    background: linear-gradient(135deg, var(--color-primary-deep-petrol) 0%, var(--color-secondary-deep-blue) 100%); /* Degradado de tus colores principales */
    color: var(--color-white);
    border-radius: 15px; /* Más redondeado */
    padding: 25px; /* Más padding */
    margin: 25px 0;
    text-align: center;
    box-shadow: var(--shadow-medium);
}

.price-display .price {
    font-family: var(--font-heading);
    font-size: 3.5rem; /* Precio más grande */
    font-weight: 900;
    margin-bottom: 8px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.price-breakdown {
    background: var(--color-light-gray); /* Fondo de desglose más claro */
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.price-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 1rem;
    padding-bottom: 5px;
    border-bottom: 1px dashed rgba(0,0,0,0.1); /* Línea punteada sutil */
}
.price-item:last-child {
    margin-bottom: 0;
    border-bottom: none;
}
.price-item span:first-child{
    color: #555;
}
.price-item span:last-child{
    font-weight: 700;
    color: var(--color-secondary-deep-blue);
}

#resumen-seleccion{
    font-size: 0.95rem;
    color: #666;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--color-medium-gray);
}
#resumen-seleccion h4{
    font-family: var(--font-heading);
    color: var(--color-secondary-deep-blue);
    margin-bottom: 12px;
    font-size: 1.1rem;
}
#resumen-seleccion div{
    margin-bottom: 8px;
}

.btn-primary {
    background: var(--color-accent-gold); /* Botón de acento dorado */
    color: var(--color-secondary-deep-blue); /* Texto azul oscuro */
    border: none;
    padding: 18px 35px; /* Más padding */
    border-radius: 30px; /* Más redondeado */
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s ease;
    margin-top: 20px; /* Más espacio */
    box-shadow: var(--shadow-medium);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4);
    background: #FFD700; /* Un dorado ligeramente más claro al hover */
}

.btn-primary:disabled {
    background: var(--color-medium-gray);
    color: #999;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}


.fade-in {
    animation: fadeIn 0.8s ease-out forwards; /* Más largo y con forwards */
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px); } /* Empieza más abajo */
    to { opacity: 1; transform: translateY(0); }
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
/* --- RESPONSIVE --- */
@media (max-width: 992px) {
    .hero-content h1 {
        font-size: 3.5rem;
    }
    .hero-content p {
        font-size: 1.3rem;
    }
    .configurator {
        grid-template-columns: 1fr;
        padding: 30px;
        gap: 30px;
    }
    .summary {
        position: relative; /* Elimina sticky en pantallas medianas */
        top: auto;
    }
}

@media (max-width: 768px) {
    .main-header {
        height: 70vh; /* Menos alto en móviles */
        margin-bottom: 40px;
    }
    .header-nav {
        display: none; /* Oculta la navegación normal */
        flex-direction: column;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(10, 46, 54, 0.95); /* Fondo oscuro semitransparente */
        justify-content: center;
        align-items: center;
        gap: 30px;
        z-index: 15; /* Asegura que esté encima de todo */
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .header-nav.nav-open {
        display: flex;
        opacity: 1;
        pointer-events: all;
    }
    .header-nav a {
        font-size: 1.8rem; /* Enlaces grandes en móvil */
        margin: 15px 0;
    }
    .hamburger-menu {
        display: block; /* Muestra el menú hamburguesa */
    }

    .hero-content h1 {
        font-size: 2.5rem;
    }
    .hero-content p {
        font-size: 1rem;
        margin-bottom: 30px;
    }
    .btn-hero {
        padding: 15px 30px;
        font-size: 1rem;
    }

    .configurator {
        padding: 20px;
        gap: 20px;
        margin-top: -100px; /* Ajuste para superposición móvil */
    }
    .step {
        padding: 20px;
    }
    .step h3 {
        font-size: 1.4rem;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        font-size: 0.95rem;
        padding: 12px;
    }
    .checkbox-group, .guias-grid, .ruta-builder {
        grid-template-columns: 1fr; /* Una columna en móvil */
    }
    .guia-card, .actividad-item {
        padding: 15px;
    }
    .summary {
        padding: 20px;
    }
    .price-display .price {
        font-size: 2.8rem;
    }
    .btn-primary {
        padding: 15px 25px;
        font-size: 1.05rem;
    }
    .footer-content {
        grid-template-columns: 1fr;
        gap: 25px;
        text-align: center;
    }
    .footer-section h4::after {
        left: 50%;
        transform: translateX(-50%); /* Centra la línea */
    }
    .social-links {
        text-align: center;
    }
    .social-links a {
        margin: 0 10px;
    }
}

@media (max-width: 480px) {
    .main-header {
        height: 60vh;
    }
    .hero-content h1 {
        font-size: 2rem;
    }
    .hero-content p {
        font-size: 0.9rem;
    }
    .header-logo {
        font-size: 1.8rem;
    }
    .configurator {
        margin-top: -80px;
    }
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

          <main class="main-content">
        <section class="hero">
            <div class="hero-content">
                <h1>Diseña Tu Viaje Ideal</h1>
                <p>Crea una aventura que se adapte perfectamente a tus deseos.</p>
                <a href="#custom-trip-form" class="btn btn-primary scroll-link">Empezar a Diseñar</a>
            </div>
        </section>
        
   
    <div class="container" id="configurator-section">
        <div class="configurator fade-in">
            <div class="steps">
                <div class="step">
                    <h3>📍 Destino y Fechas</h3>
                    <div class="form-group">
                        <label for="destino">Destino</label>
                        <select id="destino" onchange="updatePrice()">
                            <option value="">Selecciona un destino</option>
                            <option value="bariloche" data-precio="500">Bariloche</option>
                            <option value="mendoza" data-precio="450">Mendoza</option>
                            <option value="salta" data-precio="600">Salta</option>
                            <option value="iguazu" data-precio="700">Cataratas del Iguazú</option>
                            <option value="ushuaia" data-precio="900">Ushuaia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha-ida">Fecha de ida</label>
                        <input type="date" id="fecha-ida" onchange="updatePrice()">
                    </div>
                    <div class="form-group">
                        <label for="fecha-vuelta">Fecha de vuelta</label>
                        <input type="date" id="fecha-vuelta" onchange="updatePrice()">
                    </div>
                </div>

                <div class="step">
                    <h3>👥 Información de Viajeros</h3>
                    <div class="form-group">
                        <label for="personas">Número de personas</label>
                        <input type="number" id="personas" min="1" max="10" value="2" onchange="updatePrice()">
                    </div>
                    <div class="form-group">
                        <label for="tipo-viaje">Tipo de viaje</label>
                        <select id="tipo-viaje" onchange="updatePrice()">
                            <option value="economico" data-multiplicador="1">Económico</option>
                            <option value="estandar" data-multiplicador="1.5">Estándar</option>
                            <option value="vip" data-multiplicador="2.5">VIP</option>
                        </select>
                    </div>
                </div>

                <div class="step">
                    <h3>🏨 Hospedaje</h3>
                    <div class="form-group">
                        <label for="hospedaje">Tipo de hospedaje</label>
                        <select id="hospedaje" onchange="updatePrice()">
                            <option value="">Selecciona hospedaje</option>
                            <option value="hostel" data-precio="80">Hostel (por noche)</option>
                            <option value="hotel3" data-precio="150">Hotel 3 estrellas (por noche)</option>
                            <option value="hotel4" data-precio="250">Hotel 4 estrellas (por noche)</option>
                            <option value="hotel5" data-precio="400">Hotel 5 estrellas (por noche)</option>
                            <option value="apart" data-precio="200">Apartamento (por noche)</option>
                        </select>
                    </div>
                </div>

                <div class="step">
                    <h3>🎯 Guía Turístico</h3>
                    <p>Selecciona el guía que te acompañará en tu aventura:</p>
                    <div class="guias-grid">
                        <div class="guia-card" onclick="selectGuia(this, 'maria', 100)">
                            <div class="guia-avatar"><i class="fas fa-user-tie"></i></div> <h4>María González</h4>
                            <p>Especialista en aventura</p>
                            <small>$100/día</small>
                        </div>
                        <div class="guia-card" onclick="selectGuia(this, 'carlos', 120)">
                            <div class="guia-avatar"><i class="fas fa-user-tie"></i></div>
                            <h4>Carlos Ruiz</h4>
                            <p>Experto en historia</p>
                            <small>$120/día</small>
                        </div>
                        <div class="guia-card" onclick="selectGuia(this, 'ana', 90)">
                            <div class="guia-avatar"><i class="fas fa-user-tie"></i></div>
                            <h4>Ana Martín</h4>
                            <p>Guía gastronómica</p>
                            <small>$90/día</small>
                        </div>
                        <div class="guia-card" onclick="selectGuia(this, 'diego', 110)">
                            <div class="guia-avatar"><i class="fas fa-user-tie"></i></div>
                            <h4>Diego López</h4>
                            <p>Especialista en naturaleza</p>
                            <small>$110/día</small>
                        </div>
                    </div>
                </div>

                <div class="step">
                    <h3>🗺️ Ruta Personalizada</h3>
                    <p>Selecciona las actividades que más te gusten para tu viaje:</p>
                    <div class="ruta-builder">
                        <div class="actividad-item" onclick="toggleActividad(this, 'city-tour', 50)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-city"></i> City Tour</h4>
                                <p>Recorre los principales puntos de interés de la ciudad</p>
                            </div>
                            <div class="actividad-precio">$50</div>
                        </div>
                        <div class="actividad-item" onclick="toggleActividad(this, 'aventura', 120)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-mountain"></i> Aventura Extrema</h4>
                                <p>Actividades de adrenalina y deportes extremos</p>
                            </div>
                            <div class="actividad-precio">$120</div>
                        </div>
                        <div class="actividad-item" onclick="toggleActividad(this, 'gastronomia', 80)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-wine-glass-alt"></i> Tour Gastronómico</h4>
                                <p>Degusta la cocina local y productos regionales</p>
                            </div>
                            <div class="actividad-precio">$80</div>
                        </div>
                        <div class="actividad-item" onclick="toggleActividad(this, 'naturaleza', 90)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-tree"></i> Naturaleza y Paisajes</h4>
                                <p>Explora parques naturales y miradores</p>
                            </div>
                            <div class="actividad-precio">$90</div>
                        </div>
                        <div class="actividad-item" onclick="toggleActividad(this, 'cultura', 60)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-palette"></i> Experiencia Cultural</h4>
                                <p>Museos, arte local y tradiciones</p>
                            </div>
                            <div class="actividad-precio">$60</div>
                        </div>
                        <div class="actividad-item" onclick="toggleActividad(this, 'relax', 70)">
                            <div class="actividad-info">
                                <h4><i class="fas fa-spa"></i> Relax y Bienestar</h4>
                                <p>Spa, termas y actividades relajantes</p>
                            </div>
                            <div class="actividad-precio">$70</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary">
                <h3>💰 Resumen del Viaje</h3>
                
                <div class="price-display">
                    <div class="price" id="precio-total">$0</div>
                    <div>Precio total</div>
                </div>

                <div class="price-breakdown">
                    <div class="price-item">
                        <span>Destino base:</span>
                        <span id="precio-destino">$0</span>
                    </div>
                    <div class="price-item">
                        <span>Hospedaje:</span>
                        <span id="precio-hospedaje">$0</span>
                    </div>
                    <div class="price-item">
                        <span>Guía turístico:</span>
                        <span id="precio-guia">$0</span>
                    </div>
                    <div class="price-item">
                        <span>Actividades:</span>
                        <span id="precio-actividades">$0</span>
                    </div>
                    <div class="price-item">
                        <span>Multiplicador tipo:</span>
                        <span id="multiplicador-tipo">x1</span>
                    </div>
                </div>

                <div id="resumen-seleccion">
                    <h4>Tu selección:</h4>
                    <div id="destino-seleccionado">Destino: No seleccionado</div>
                    <div id="personas-seleccionadas">Personas: 2</div>
                    <div id="tipo-seleccionado">Tipo: Económico</div>
                    <div id="hospedaje-seleccionado">Hospedaje: No seleccionado</div>
                    <div id="guia-seleccionado">Guía: No seleccionado</div>
                    <div id="actividades-seleccionadas">Actividades: Ninguna</div>
                </div>

                <button class="btn-primary" onclick="procesarReserva()" id="btn-reservar" disabled>
                    Reservar Viaje
                </button>
                <button class="btn-primary" onclick="comprarViaje()" id="btn-comprar" disabled>
                    Comprar Ahora
                </button>
            </div>
        </div>
    </div>

  <?php include 'footer.php'; ?>
    <script>
        // --- CÓDIGO JAVASCRIPT ORIGINAL (CON MEJORA EN "COMPRAR") ---

      let configuracion = {
            destino: null,
            fechaIda: null,
            fechaVuelta: null,
            personas: 2,
            tipoViaje: 'economico',
            hospedaje: null,
            guia: null,
            actividades: []
        };

        let precios = {
            destino: 0,
            hospedaje: 0,
            guia: 0,
            actividades: 0,
            multiplicador: 1,
            total: 0
        };

        // Initialize Stripe with your publishable key
        // Replace 'YOUR_STRIPE_PUBLISHABLE_KEY' with your actual publishable key.
        const stripe = Stripe('pk_test_51RZUQGPFcLpCsDmjzWAwiDgPnrLdziqaOSANfQKDNVuHQITr18XA3Z8WEZLGeYX60VhYGO59NyqsD25QP4gEQBuU00XZgBhiVD'); //

        function selectGuia(element, guiaId, precio) {
            document.querySelectorAll('.guia-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            if (configuracion.guia === guiaId) {
                // If the same guide is clicked, deselect
                configuracion.guia = null;
                precios.guia = 0;
            } else {
                // If a new guide is selected
                element.classList.add('selected');
                configuracion.guia = guiaId;
                precios.guia = precio;
            }
            
            updatePrice();
        }

        function toggleActividad(element, actividadId, precio) {
            element.classList.toggle('selected');
            
            const index = configuracion.actividades.findIndex(act => act.id === actividadId);

            if (index > -1) {
                configuracion.actividades.splice(index, 1);
            } else {
                configuracion.actividades.push({id: actividadId, precio: precio});
            }
            
            updatePrice();
        }

        function updatePrice() {
            const destino = document.getElementById('destino');
            const fechaIda = document.getElementById('fecha-ida').value;
            const fechaVuelta = document.getElementById('fecha-vuelta').value;
            const personas = parseInt(document.getElementById('personas').value) || 1;
            const tipoViaje = document.getElementById('tipo-viaje');
            const hospedaje = document.getElementById('hospedaje');

            precios.destino = destino.selectedOptions[0]?.dataset.precio ? parseInt(destino.selectedOptions[0].dataset.precio) : 0;
            precios.hospedaje = hospedaje.selectedOptions[0]?.dataset.precio ? parseInt(hospedaje.selectedOptions[0].dataset.precio) : 0;
            precios.multiplicador = parseFloat(tipoViaje.selectedOptions[0]?.dataset.multiplicador) || 1;

            let dias = 0;
            if (fechaIda && fechaVuelta) {
                const fecha1 = new Date(fechaIda);
                const fecha2 = new Date(fechaVuelta);
                if (fecha2 > fecha1) {
                    dias = Math.ceil((fecha2 - fecha1) / (1000 * 60 * 60 * 24));
                }
            }
            // The guide and accommodation are charged per day/night. If days are 0, cost is 0.
            const costoHospedaje = precios.hospedaje * dias;
            const costoGuia = precios.guia * dias;

            const precioActividades = configuracion.actividades.reduce((sum, act) => sum + act.precio, 0);
            precios.actividades = precioActividades;

            // Note: Guide cost is not multiplied by persons, assuming it's a fixed daily rate for the group.
            const subtotal = (precios.destino * personas) + (costoHospedaje * personas) + costoGuia + (precios.actividades * personas);
            const total = subtotal * precios.multiplicador;
            precios.total = total; // Save the total in the prices object

            document.getElementById('precio-total').textContent = `$${total.toFixed(0)}`;
            document.getElementById('precio-destino').textContent = `$${(precios.destino * personas).toFixed(0)}`;
            document.getElementById('precio-hospedaje').textContent = `$${(costoHospedaje * personas).toFixed(0)}`;
            document.getElementById('precio-guia').textContent = `$${costoGuia.toFixed(0)}`; // The guide is not multiplied by person
            document.getElementById('precio-actividades').textContent = `$${(precios.actividades * personas).toFixed(0)}`;
            document.getElementById('multiplicador-tipo').textContent = `x${precios.multiplicador}`;

            document.getElementById('destino-seleccionado').textContent = `Destino: ${destino.value ? destino.selectedOptions[0].text : 'No seleccionado'}`;
            document.getElementById('personas-seleccionadas').textContent = `Personas: ${personas}`;
            document.getElementById('tipo-seleccionado').textContent = `Tipo: ${tipoViaje.selectedOptions[0].text}`;
            document.getElementById('hospedaje-seleccionado').textContent = `Hospedaje: ${hospedaje.value ? hospedaje.selectedOptions[0].text : 'No seleccionado'}`;
            document.getElementById('guia-seleccionado').textContent = `Guía: ${configuracion.guia ? configuracion.guia : 'No seleccionado'}`;
            document.getElementById('actividades-seleccionadas').textContent = `Actividades: ${configuracion.actividades.length > 0 ? configuracion.actividades.length + ' seleccionadas' : 'Ninguna'}`;

            const puedeReservar = destino.value && hospedaje.value && fechaIda && fechaVuelta && dias > 0 && total > 0;
            document.getElementById('btn-reservar').disabled = !puedeReservar;
            document.getElementById('btn-comprar').disabled = !puedeReservar;

            configuracion.destino = destino.value;
            configuracion.fechaIda = fechaIda;
            configuracion.fechaVuelta = fechaVuelta;
            configuracion.personas = personas;
            configuracion.tipoViaje = tipoViaje.value;
            configuracion.hospedaje = hospedaje.value;
        }

        function procesarReserva() {
            if (validarConfiguracion()) {
                alert('¡Reserva iniciada! Te contactaremos para confirmar los detalles.');
                console.log('Configuración de reserva:', configuracion);
            }
        }
        
        // =========================================================
        // ===== FUNCIÓN "COMPRAR" MEJORADA CON MÉTODO DE PAGO =====
        // =========================================================
        function comprarViaje() {
            if (!validarConfiguracion()) {
                return; // Stop if configuration is not valid
            }

            const botonComprar = document.getElementById('btn-comprar');
            botonComprar.disabled = true;
            botonComprar.textContent = 'Procesando...';

            // Use 'fetch' to send data to our own PHP file.
            fetch('turismo_pers.php', { // Ensure this path is correct
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ // Convert the configuration to a JSON string
                    config: configuracion,
                    precios: precios
                }),
            })
            .then(response => response.json()) // Expect a JSON response from the server
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.status === 'success' && data.checkoutUrl) {
                    // Redirect to Stripe Checkout page
                    window.location.href = data.checkoutUrl; //
                } else {
                    alert('Hubo un problema procesando tu solicitud: ' + (data.message || 'Error desconocido.'));
                    botonComprar.disabled = false;
                    botonComprar.textContent = 'Comprar Ahora';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión. Por favor, intenta de nuevo.');
                botonComprar.disabled = false;
                botonComprar.textContent = 'Comprar Ahora';
            });
        }

        function validarConfiguracion() {
            if (!configuracion.destino) {
                alert('Por favor selecciona un destino');
                return false;
            }
            if (!configuracion.fechaIda || !configuracion.fechaVuelta) {
                alert('Por favor selecciona las fechas de viaje');
                return false;
            }
             if (new Date(configuracion.fechaVuelta) <= new Date(configuracion.fechaIda)) {
                alert('La fecha de vuelta debe ser posterior a la fecha de ida.');
                return false;
            }
            if (!configuracion.hospedaje) {
                alert('Por favor selecciona un tipo de hospedaje');
                return false;
            }
            // Ensure a positive total price before attempting to purchase
            if (precios.total <= 0) {
                alert('El precio total del viaje debe ser mayor a $0 para poder comprar.');
                return false;
            }
            return true;
        }

        // Initialize on page load
        window.onload = updatePrice;

        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger-menu');
            const nav = document.querySelector('.header-nav');

            if (hamburger && nav) {
                hamburger.addEventListener('click', () => {
                    nav.classList.toggle('nav-open');
                    hamburger.querySelector('i').classList.toggle('fa-bars');
                    hamburger.querySelector('i').classList.toggle('fa-times');
                });
            }

            // Smooth scroll for hero button
            document.querySelectorAll('a.scroll-link').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });

    </script>
</body>
</html>