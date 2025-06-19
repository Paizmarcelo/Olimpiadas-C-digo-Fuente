<?php
// PHP logic to handle form submissions or dynamic content generation can go here.
// For a single file, you might process forms at the top and then render HTML.

// Example: Simple form processing for the booking section
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_submit'])) {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $tipo_consulta = htmlspecialchars($_POST['tipo_consulta']);
    $fecha = htmlspecialchars($_POST['fecha']);
    $horario = htmlspecialchars($_POST['horario']);
    $consulta_detalle = htmlspecialchars($_POST['consulta_detalle']);

    // In a real application, you would save this to a database, send an email, etc.
    // For this example, we'll just simulate a successful booking and provide feedback.
    $booking_message = "¡Reserva confirmada para " . $nombre . " el " . $fecha . " a las " . $horario . "! Recibirás un email con los detalles.";
    $booking_success = true;
}

// Example: Simulate ticket data for the status tracking section
$ticket_data = [
    'TK-2024-001234' => [
        'status' => 'En progreso',
        'priority' => 'Media',
        'assigned_to' => 'Juan Pérez',
        'timeline' => [
            ['title' => 'Ticket creado', 'date' => '15 Jun 2025, 10:30 AM', 'description' => 'Tu solicitud ha sido recibida y asignada', 'active' => true],
            ['title' => 'En revisión', 'date' => '15 Jun 2025, 2:15 PM', 'description' => 'Nuestro equipo está analizando tu consulta', 'active' => true],
            ['title' => 'Solución propuesta', 'date' => 'Pendiente', 'description' => 'Te contactaremos con una solución', 'active' => false],
            ['title' => 'Resuelto', 'date' => 'Pendiente', 'description' => 'Confirmación de resolución', 'active' => false],
        ]
    ],
    // Add more dummy tickets as needed
    'TK-2024-005678' => [
        'status' => 'Resuelto',
        'priority' => 'Baja',
        'assigned_to' => 'María García',
        'timeline' => [
            ['title' => 'Ticket creado', 'date' => '10 Jun 2025, 09:00 AM', 'description' => 'Tu solicitud ha sido recibida y asignada', 'active' => true],
            ['title' => 'En revisión', 'date' => '10 Jun 2025, 11:00 AM', 'description' => 'Nuestro equipo está analizando tu consulta', 'active' => true],
            ['title' => 'Solución propuesta', 'date' => '11 Jun 2025, 01:00 PM', 'description' => 'Se ha enviado una solución a tu email', 'active' => true],
            ['title' => 'Resuelto', 'date' => '11 Jun 2025, 03:00 PM', 'description' => 'Confirmación de resolución', 'active' => true],
        ]
    ]
];

// PHP logic for ticket search (example, usually done via AJAX or form submission)
$searched_ticket = null;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ticket_number'])) {
    $search_term = htmlspecialchars($_GET['ticket_number']);
    if (isset($ticket_data[$search_term])) {
        $searched_ticket = $ticket_data[$search_term];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Centro de Soporte - Tu Empresa</title>
    <style>
        /* All your existing CSS goes here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --verde-petroleo: #2c5f5c;
            --azul-profundo: #1e3a5f;
            --verde-hover: #245550;
            --azul-hover: #162d4a;
            --blanco: #ffffff;
            --gris-claro: #f8f9fa;
            --gris-medio: #e9ecef;
            --gris-oscuro: #6c757d;
            --texto-oscuro: #2c3e50;
            --borde: #dee2e6;
            --sombra: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--texto-oscuro);
            background: var(--gris-claro);
            font-size: 16px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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

        /* Navigation */
        .nav-tabs {
            background: var(--blanco);
            padding: 1.5rem 0;
            border-bottom: 2px solid var(--borde);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px var(--sombra);
        }

        .nav-tabs ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .nav-tabs li {
            cursor: pointer;
            padding: 1rem 2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: var(--gris-claro);
            color: var(--texto-oscuro);
            font-weight: 600;
            font-size: 0.95rem;
            border: 2px solid transparent;
        }

        .nav-tabs li:hover {
            background: var(--verde-petroleo);
            color: var(--blanco);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--sombra);
        }

        .nav-tabs li.active {
            background: var(--azul-profundo);
            color: var(--blanco);
            border-color: var(--azul-profundo);
        }

        /* Content Sections */
        .content {
            padding: 3rem 0;
        }

        .section {
            display: none;
            animation: fadeIn 0.5s ease-in;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FAQ Section */
        .faq-container {
            background: var(--blanco);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px var(--sombra);
            border: 1px solid var(--borde);
        }

        .faq-container h2 {
            color: var(--azul-profundo);
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 700;
        }

        .faq-search {
            margin-bottom: 2rem;
            position: relative;
        }

        .faq-search input {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: 2px solid var(--borde);
            border-radius: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: var(--gris-claro);
        }

        .faq-search input:focus {
            outline: none;
            border-color: var(--verde-petroleo);
            background: var(--blanco);
            box-shadow: 0 0 0 3px rgba(44, 95, 92, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gris-oscuro);
            pointer-events: none;
        }

        .faq-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .category-btn {
            padding: 1rem 1.5rem;
            background: var(--gris-claro);
            border: 2px solid var(--borde);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--texto-oscuro);
        }

        .category-btn:hover {
            background: var(--verde-petroleo);
            color: var(--blanco);
            border-color: var(--verde-petroleo);
        }

        .category-btn.active {
            background: var(--azul-profundo);
            color: var(--blanco);
            border-color: var(--azul-profundo);
        }

        .faq-item {
            margin-bottom: 1rem;
            border: 2px solid var(--borde);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: var(--blanco);
        }

        .faq-item:hover {
            border-color: var(--verde-petroleo);
            box-shadow: 0 2px 8px var(--sombra);
        }

        .faq-question {
            padding: 1.5rem;
            background: var(--gris-claro);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            color: var(--texto-oscuro);
        }

        .faq-question:hover {
            background: var(--verde-petroleo);
            color: var(--blanco);
        }

        .faq-question.active {
            background: var(--azul-profundo);
            color: var(--blanco);
        }

        .faq-answer {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            background: var(--blanco);
            color: var(--texto-oscuro);
        }

        .faq-answer.active {
            padding: 1.5rem;
            max-height: 300px;
            border-top: 1px solid var(--borde);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--gris-oscuro);
            font-style: italic;
        }

        /* Chatbot */
        .chatbot-container {
            background: var(--blanco);
            border-radius: 12px;
            height: 600px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px var(--sombra);
            overflow: hidden;
            border: 1px solid var(--borde);
        }

        .chatbot-header {
            background: var(--azul-profundo);
            color: var(--blanco);
            padding: 1.5rem;
            text-align: center;
            border-bottom: 2px solid var(--verde-petroleo);
        }

        .chatbot-header h3 {
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .chat-messages {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            background: var(--gris-claro);
        }

        .message {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 75%;
            padding: 1.2rem;
            border-radius: 12px;
            word-wrap: break-word;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .message.bot .message-content {
            background: var(--blanco);
            border: 2px solid var(--borde);
            color: var(--texto-oscuro);
        }

        .message.user .message-content {
            background: var(--verde-petroleo);
            color: var(--blanco);
        }

        .chat-input-container {
            padding: 1.5rem;
            background: var(--blanco);
            border-top: 2px solid var(--borde);
            display: flex;
            gap: 1rem;
        }

        .chat-input {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--borde);
            border-radius: 8px;
            outline: none;
            font-size: 1rem;
            background: var(--gris-claro);
        }

        .chat-input:focus {
            border-color: var(--verde-petroleo);
            background: var(--blanco);
        }

        .chat-send {
            padding: 1rem 2rem;
            background: var(--azul-profundo);
            color: var(--blanco);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .chat-send:hover {
            background: var(--azul-hover);
            transform: translateY(-1px);
        }

        /* Call Booking */
        .booking-container {
            background: var(--blanco);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .booking-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--texto-oscuro);
        }

        .form-group input, .form-group select, .form-group textarea {
            padding: 1rem;
            border: 2px solid var(--gris-medio);
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--verde-petroleo);
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .time-slot {
            padding: 0.8rem;
            border: 2px solid var(--gris-medio);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--blanco);
        }

        .time-slot:hover, .time-slot.selected {
            background: var(--verde-claro);
            color: var(--blanco);
            border-color: var(--verde-claro);
        }

        .time-slot.unavailable {
            background: var(--gris-medio);
            color: #666;
            cursor: not-allowed;
        }

        .btn-primary {
            padding: 1.2rem 2.5rem;
            background: var(--verde-petroleo);
            color: var(--blanco);
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid var(--verde-petroleo);
        }

        .btn-primary:hover {
            background: var(--verde-hover);
            border-color: var(--verde-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--sombra);
        }

        /* Status Tracking */
        .status-container {
            background: var(--blanco);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .status-search {
            margin-bottom: 2rem;
        }

        .status-result {
            background: var(--gris-claro);
            padding: 2rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .status-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--verde-petroleo);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            background: var(--verde-petroleo);
            border-radius: 50%;
        }

        .timeline-item.active::before {
            background: var(--azul-profundo);
            box-shadow: 0 0 10px rgba(30, 58, 95, 0.5);
        }

        /* Knowledge Base */
        .knowledge-container {
            background: var(--blanco);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .knowledge-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .knowledge-card {
            background: var(--gris-claro);
            padding: 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .knowledge-card:hover {
            background: var(--verde-claro);
            color: var(--blanco);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(44, 95, 92, 0.2);
        }

        .knowledge-card h3 {
            margin-bottom: 1rem;
            color: inherit;
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

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs ul {
                gap: 1rem;
            }
            
            .nav-tabs li {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .chatbot-container {
                height: 500px;
            }
            
            .time-slots {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }
        }
        
        /* Styles for notifications */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
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
                <a href="principal/hospedajes_usuario.php" class="nav-item">
                    <i class="fa-solid fa-hotel nav-icon"></i>
                    <div class="nav-text" >Hospedaje</div>
                </a>
                <a href="principal/vuelos_usuario.php" class="nav-item">
                    <i class="fa-solid fa-plane nav-icon"></i>
                    <div class="nav-text">Vuelos</div>
                </a>
                <a href="principal/index_usuario.php" class="nav-item active"> <i class="fa-solid fa-box-archive nav-icon"></i>
                    <div class="nav-text">Paquetes</div>
                </a>
                <a href="ofertas.php" class="nav-item">
                    <i class="fa-solid fa-tag nav-icon"></i>
                    <div class="nav-text">Ofertas</div>
                </a>
                <a href="turismo_pers.php" class="nav-item">
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

    <nav class="nav-tabs">
        <div class="container">
            <ul>
                <li class="nav-item active" data-section="faq">Preguntas Frecuentes</li>
                <li class="nav-item" data-section="chatbot">Chat de Soporte</li>
                <li class="nav-item" data-section="booking">Reservar Llamada</li>
                <li class="nav-item" data-section="status">Estado de Ticket</li>
                <li class="nav-item" data-section="knowledge">Base de Conocimiento</li>
            </ul>
        </div>
    </nav>

    <main class="content">
        <div class="container">
            <section id="faq" class="section active">
                <div class="faq-container">
                    <h2>Preguntas Frecuentes</h2>
                    <div class="faq-search">
                        <input type="text" id="faq-search-input" placeholder="🔍 Buscar en preguntas frecuentes...">
                        <div id="search-results-count" class="search-results-count"></div>
                    </div>
                    
                    <div class="faq-categories">
                        <button class="category-btn active" data-category="all">Todas</button>
                        <button class="category-btn" data-category="account">Cuenta</button>
                        <button class="category-btn" data-category="billing">Facturación</button>
                        <button class="category-btn" data-category="technical">Técnico</button>
                        <button class="category-btn" data-category="general">General</button>
                    </div>

                    <div id="faq-list">
                        <div class="faq-item" data-category="account" data-keywords="contraseña password cambiar modificar seguridad login acceso">
                            <div class="faq-question">¿Cómo puedo cambiar mi contraseña? <span>+</span></div>
                            <div class="faq-answer">Para cambiar tu contraseña, ve a Configuración > Seguridad > Cambiar contraseña. Introduce tu contraseña actual y la nueva contraseña dos veces para confirmar. Te recomendamos usar una contraseña fuerte con al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos.</div>
                        </div>
                        
                        <div class="faq-item" data-category="billing" data-keywords="facturas facturación billing pagos recibos descargar email">
                            <div class="faq-question">¿Cómo puedo ver mis facturas? <span>+</span></div>
                            <div class="faq-answer">Puedes acceder a todas tus facturas desde tu panel de usuario en la sección "Facturación". También recibirás una copia por email cada mes. Las facturas están disponibles en formato PDF y puedes descargarlas en cualquier momento. Si necesitas facturas de meses anteriores, también las encontrarás en esta sección.</div>
                        </div>

                        <div class="faq-item" data-category="technical" data-keywords="acceso login problemas técnicos no puedo entrar cuenta bloqueada">
                            <div class="faq-question">¿Por qué no puedo acceder a mi cuenta? <span>+</span></div>
                            <div class="faq-answer">Verifica que estés usando el email y contraseña correctos. Si continúas con problemas, usa la opción "Recuperar contraseña" o contacta con soporte. También revisa si tu cuenta no está temporalmente bloqueada por intentos fallidos. En caso de persistir el problema, nuestro equipo técnico puede ayudarte a restablecer el acceso.</div>
                        </div>

                        <div class="faq-item" data-category="general" data-keywords="horarios atención soporte horario chat llamadas disponibilidad">
                            <div class="faq-question">¿Cuáles son los horarios de atención? <span>+</span></div>
                            <div class="faq-answer">Nuestro horario de atención telefónica es de lunes a viernes de 9:00 AM a 6:00 PM (GMT-3). El chat está disponible 24/7 con respuestas automáticas y soporte humano durante horario laboral. Las llamadas programadas se pueden reservar dentro del horario de atención. Los fines de semana tenemos soporte limitado solo para emergencias críticas.</div>
                        </div>

                        <div class="faq-item" data-category="account" data-keywords="eliminar cuenta borrar datos privacidad cancelar suscripción">
                            <div class="faq-question">¿Cómo elimino mi cuenta? <span>+</span></div>
                            <div class="faq-answer">Para eliminar tu cuenta, contacta con nuestro equipo de soporte a través del chat o programa una llamada. Te ayudaremos con el proceso y te informaremos sobre la retención de datos según nuestras políticas de privacidad. Ten en cuenta que este proceso es irreversible y perderás acceso a todos tus datos y configuraciones.</div>
                        </div>

                        <div class="faq-item" data-category="billing" data-keywords="planes precios costo cambiar plan upgrade downgrade suscripción">
                            <div class="faq-question">¿Puedo cambiar mi plan en cualquier momento? <span>+</span></div>
                            <div class="faq-answer">Sí, puedes cambiar tu plan en cualquier momento desde la sección "Suscripción" en tu panel de usuario. Los cambios a planes superiores se activan inmediatamente, mientras que las downgrades se aplican en el próximo ciclo de facturación. No hay penalidades por cambios de plan y puedes cancelar en cualquier momento.</div>
                        </div>

                        <div class="faq-item" data-category="technical" data-keywords="lento rendimiento performance optimizar velocidad conexión">
                            <div class="faq-question">¿Por qué va lento el sistema? <span>+</span></div>
                            <div class="faq-answer">La lentitud puede deberse a varios factores: conexión a internet, cache del navegador, o carga alta del servidor. Te recomendamos limpiar el cache, usar una conexión estable, y cerrar pestañas innecesarias. Si el problema persiste, verifica nuestro estado del servicio o contacta soporte técnico para asistencia personalizada.</div>
                        </div>

                        <div class="faq-item" data-category="general" data-keywords="funciones características nuevas updates actualizaciones roadmap">
                            <div class="faq-question">¿Cómo me entero de las nuevas funciones? <span>+</span></div>
                            <div class="faq-answer">Enviamos notificaciones por email sobre nuevas funciones y actualizaciones importantes. También puedes seguir nuestros anuncios en el panel de usuario y nuestras redes sociales. Mantenemos un blog con tutoriales y novedades. Activando las notificaciones push también recibirás alertas inmediatas sobre nuevas características.</div>
                        </div>

                        <div class="faq-item" data-category="technical" data-keywords="backup respaldo datos exportar importar seguridad copias">
                            <div class="faq-question">¿Hacen respaldo de mis datos? <span>+</span></div>
                            <div class="faq-answer">Sí, realizamos respaldos automáticos diarios de todos los datos de usuarios. Los backups se mantienen por 30 días y están encriptados. También puedes exportar tus datos en cualquier momento desde la sección "Datos" en configuración. Para restaurar datos específicos de un backup, contacta con soporte técnico con los detalles de lo que necesitas recuperar.</div>
                        </div>

                        <div class="faq-item" data-category="billing" data-keywords="refund reembolso devolución cancelar pago garantía">
                            <div class="faq-question">¿Ofrecen garantía de reembolso? <span>+</span></div>
                            <div class="faq-answer">Ofrecemos una garantía de reembolso de 30 días para nuevos usuarios. Si no estás satisfecho con el servicio, puedes solicitar un reembolso completo dentro de los primeros 30 días. Para solicitar un reembolso, contacta con soporte con tu información de cuenta y el motivo de la solicitud. Los reembolsos se procesan en 5-7 días hábiles.</div>
                        </div>
                    </div>
                    
                    <div id="no-results" class="no-results" style="display: none;">
                        <h3>🔍 Sin resultados</h3>
                        <p>No se encontraron preguntas que coincidan con tu búsqueda.</p>
                        <p>Intenta con términos diferentes o <strong>contacta con nuestro equipo de soporte</strong>.</p>
                    </div>
                </div>
            </section>

            <section id="chatbot" class="section">
                <div class="chatbot-container">
                    <div class="chatbot-header">
                        <h3>💬 Asistente Virtual</h3>
                        <p>¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?</p>
                    </div>
                    <div class="chat-messages" id="chat-messages">
                        <div class="message bot">
                            <div class="message-content">
                                ¡Bienvenido! Estoy aquí para ayudarte con cualquier pregunta. Puedes preguntarme sobre:
                                <br>• Problemas técnicos
                                <br>• Información de cuenta
                                <br>• Facturación
                                <br>• Funcionalidades del servicio
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-container">
                        <input type="text" class="chat-input" id="chat-input" placeholder="Escribe tu pregunta aquí...">
                        <button class="chat-send" id="chat-send">Enviar</button>
                    </div>
                </div>
            </section>

            <section id="booking" class="section">
                <div class="booking-container">
                    <h2>Reservar Llamada 1:1</h2>
                    <p>Programa una sesión personalizada con uno de nuestros asesores especializados</p>
                    
                    <form class="booking-form" id="booking-form" method="POST" action="">
                        <input type="hidden" name="booking_submit" value="1">
                        <div class="form-group">
                            <label for="nombre">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono">
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_consulta">Tipo de consulta *</label>
                            <select id="tipo_consulta" name="tipo_consulta" required>
                                <option value="">Selecciona una opción</option>
                                <option value="technical">Soporte técnico</option>
                                <option value="billing">Facturación</option>
                                <option value="onboarding">Configuración inicial</option>
                                <option value="training">Capacitación</option>
                                <option value="general">Consulta general</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="booking-date">Fecha preferida</label>
                            <input type="date" id="booking-date" name="fecha" min="">
                        </div>
                        
                        <div class="form-group">
                            <label for="time-slots">Horario disponible</label>
                            <div class="time-slots" id="time-slots">
                                <div class="time-slot" data-time="09:00">9:00 AM</div>
                                <div class="time-slot" data-time="10:00">10:00 AM</div>
                                <div class="time-slot" data-time="11:00">11:00 AM</div>
                                <div class="time-slot" data-time="14:00">2:00 PM</div>
                                <div class="time-slot" data-time="15:00">3:00 PM</div>
                                <div class="time-slot" data-time="16:00">4:00 PM</div>
                                <input type="hidden" id="selected-time" name="horario">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="consulta_detalle">Describe tu consulta</label>
                            <textarea id="consulta_detalle" name="consulta_detalle" rows="4" placeholder="Proporciona detalles sobre lo que necesitas discutir..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-primary">Reservar Llamada</button>
                    </form>
                    <?php if (isset($booking_success) && $booking_success): ?>
                        <script>
                            // Using JavaScript to show a more user-friendly notification
                            document.addEventListener('DOMContentLoaded', function() {
                                showNotification("<?php echo $booking_message; ?>", 'success');
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </section>

            <section id="status" class="section">
                <div class="status-container">
                    <h2>Estado de tu Ticket</h2>
                    <p>Consulta el progreso de tu solicitud de soporte</p>
                    
                    <form class="status-search" method="GET" action="#status">
                        <div class="form-group">
                            <label for="ticket-search-input">Número de ticket</label>
                            <input type="text" id="ticket-search-input" name="ticket_number" placeholder="Ej: TK-2024-001234" value="<?php echo isset($_GET['ticket_number']) ? htmlspecialchars($_GET['ticket_number']) : ''; ?>">
                        </div>
                        <button type="submit" class="btn-primary" id="search-ticket-btn">Buscar Ticket</button>
                    </form>
                    
                    <div id="status-result" class="status-result" style="display: <?php echo ($searched_ticket) ? 'block' : 'none'; ?>;">
                        <?php if ($searched_ticket): ?>
                            <h3>Ticket: <?php echo htmlspecialchars($_GET['ticket_number']); ?></h3>
                            <p><strong>Estado:</strong> <?php echo $searched_ticket['status']; ?></p>
                            <p><strong>Prioridad:</strong> <?php echo $searched_ticket['priority']; ?></p>
                            <p><strong>Asignado a:</strong> <?php echo $searched_ticket['assigned_to']; ?></p>
                            
                            <div class="status-timeline">
                                <?php foreach ($searched_ticket['timeline'] as $item): ?>
                                    <div class="timeline-item <?php echo $item['active'] ? 'active' : ''; ?>">
                                        <h4><?php echo $item['title']; ?></h4>
                                        <p><?php echo $item['date']; ?></p>
                                        <small><?php echo $item['description']; ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>No se encontró ningún ticket con ese número o el formato es inválido.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section id="knowledge" class="section">
                <div class="knowledge-container">
                    <h2>Base de Conocimiento</h2>
                    <p>Explora nuestros recursos y guías detalladas</p>
                    
                    <div class="faq-search">
                        <input type="text" placeholder="Buscar en la base de conocimiento...">
                    </div>
                    
                    <div class="knowledge-categories">
                        <div class="knowledge-card">
                            <h3>📚 Guías de Inicio</h3>
                            <p>Primeros pasos y configuración inicial de tu cuenta</p>
                        </div>
                        
                        <div class="knowledge-card">
                            <h3>🔧 Tutoriales Técnicos</h3>
                            <p>Guías paso a paso para funciones avanzadas</p>
                        </div>
                        
                        <div class="knowledge-card">
                            <h3>💳 Facturación y Pagos</h3>
                            <p>Todo sobre planes, pagos y facturación</p>
                        </div>
                        
                        <div class="knowledge-card">
                            <h3>🛡️ Seguridad</h3>
                            <p>Mejores prácticas de seguridad y privacidad</p>
                        </div>
                        
                        <div class="knowledge-card">
                            <h3>📊 Reportes y Analytics</h3>
                            <p>Cómo interpretar y usar tus datos</p>
                        </div>
                        
                        <div class="knowledge-card">
                            <h3>🔗 Integraciones</h3>
                            <p>Conecta con tus herramientas favoritas</p>
                        </div>
                    </div>
                </div>
            </section>
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
     <script>
        // Función para normalizar texto (quitar acentos, convertir a minúsculas, quitar ¿)
        function normalizeText(text) {
            return text
                .toLowerCase()
                .normalize("NFD") // Descompone caracteres con acentos en caracter base + diacrítico
                .replace(/[\u0300-\u036f]/g, "") // Elimina los diacríticos
                .replace(/\¿/g, ''); // Elimina el signo de interrogación inicial
        }

        // Navigation functionality
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                const targetSection = item.dataset.section;
                
                // Update active nav item
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
                
                // Show target section
                document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
                document.getElementById(targetSection).classList.add('active');

                // If the status section is selected via navigation, ensure the ticket search result is shown/hidden based on PHP data
                if (targetSection === 'status') {
                    const statusResult = document.getElementById('status-result');
                    const ticketSearchInput = document.getElementById('ticket-search-input');
                    if (ticketSearchInput.value.trim() !== '' && <?php echo json_encode($searched_ticket !== null); ?>) {
                        statusResult.style.display = 'block';
                        animateTimeline(); // Call animation if ticket is found
                    } else {
                        statusResult.style.display = 'none';
                    }
                }
            });
        });

        // FAQ functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const isActive = answer.classList.contains('active');
                
                // Close all FAQ answers
                document.querySelectorAll('.faq-answer').forEach(ans => ans.classList.remove('active'));
                document.querySelectorAll('.faq-question span').forEach(span => span.textContent = '+');
                
                // Toggle current answer
                if (!isActive) {
                    answer.classList.add('active');
                    question.querySelector('span').textContent = '-';
                }
            });
        });

        // FAQ search - MODIFICADO AQUÍ
        document.getElementById('faq-search-input').addEventListener('input', (e) => {
            const searchTerm = normalizeText(e.target.value); // Normalizar el término de búsqueda
            let resultsCount = 0;
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = normalizeText(item.querySelector('.faq-question').textContent); // Normalizar la pregunta
                const answer = normalizeText(item.querySelector('.faq-answer').textContent); // Normalizar la respuesta
                const keywords = normalizeText(item.dataset.keywords || ''); // Normalizar las palabras clave
                
                if (question.includes(searchTerm) || answer.includes(searchTerm) || keywords.includes(searchTerm)) {
                    item.style.display = 'block';
                    resultsCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            const noResultsDiv = document.getElementById('no-results');
            if (resultsCount === 0 && searchTerm !== '') {
                noResultsDiv.style.display = 'block';
            } else {
                noResultsDiv.style.display = 'none';
            }
        });

        // FAQ category filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.dataset.category;
                
                // Update active category
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Filter FAQ items
                let resultsCount = 0;
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                        resultsCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                const noResultsDiv = document.getElementById('no-results');
                if (resultsCount === 0) {
                    noResultsDiv.style.display = 'block';
                } else {
                    noResultsDiv.style.display = 'none';
                }
                document.getElementById('faq-search-input').value = ''; // Clear search when category changes
            });
        });

        // Chatbot functionality
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');

        const botResponses = {
            'hola': '¡Hola! ¿En qué puedo ayudarte hoy?',
            'ayuda': 'Estoy aquí para ayudarte. Puedes preguntarme sobre problemas técnicos, tu cuenta, facturación o cualquier otra consulta.',
            'contraseña': 'Para cambiar tu contraseña, ve a Configuración > Seguridad > Cambiar contraseña. Si no puedes acceder, usa "Olvidé mi contraseña".',
            'factura': 'Puedes ver todas tus facturas en tu panel de usuario, sección "Facturación". También las enviamos por email.',
            'precio': 'Nuestros planes incluyen opciones flexibles. Te recomiendo hablar con un asesor para encontrar la mejor opción para ti.',
            'soporte': 'Ofrecemos soporte 24/7 por chat y llamadas programadas de lunes a viernes de 9AM a 6PM.',
            'problema': 'Cuéntame más detalles sobre el problema que estás experimentando. ¿Es técnico, de cuenta o de facturación?',
            'default': 'Entiendo tu consulta. Para brindarte la mejor ayuda, te recomiendo programar una llamada con nuestro equipo especializado o crear un ticket de soporte.'
        };

        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.innerHTML = `<div class="message-content">${content}</div>`;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function getBotResponse(userMessage) {
            const message = normalizeText(userMessage); // Normalizar el mensaje del usuario
            for (let key in botResponses) {
                if (message.includes(normalizeText(key))) { // Normalizar las claves de las respuestas
                    return botResponses[key];
                }
            }
            return botResponses.default;
        }

        chatSend.addEventListener('click', () => {
            const message = chatInput.value.trim();
            if (message) {
                addMessage(message, true);
                chatInput.value = '';
                
                setTimeout(() => {
                    const response = getBotResponse(message);
                    addMessage(response, false);
                }, 1000);
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                chatSend.click();
            }
        });

        // Booking functionality
        const bookingDate = document.getElementById('booking-date');
        const timeSlotsContainer = document.getElementById('time-slots');
        const selectedTimeInput = document.getElementById('selected-time');
        
        // Set minimum date to today
        bookingDate.min = new Date().toISOString().split('T')[0];
        
        // Time slot selection
        timeSlotsContainer.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', () => {
                if (!slot.classList.contains('unavailable')) {
                    timeSlotsContainer.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    slot.classList.add('selected');
                    selectedTimeInput.value = slot.dataset.time; // Update hidden input with selected time
                }
            });
        });

        // Simulate availability check when date changes
        bookingDate.addEventListener('change', () => {
            // In a real application, you'd make an AJAX call here to fetch available slots for the selected date
            // For now, we simulate random availability.
            document.querySelectorAll('.time-slot').forEach(slot => {
                const random = Math.random();
                if (random < 0.3) { // 30% chance of being unavailable
                    slot.classList.add('unavailable');
                    slot.classList.remove('selected');
                } else {
                    slot.classList.remove('unavailable');
                }
            });
            selectedTimeInput.value = ''; // Clear selected time when date changes
        });

        // Booking form submission (PHP handles it now)
        // The original JS alert is replaced by PHP's notification system.
        document.getElementById('booking-form').addEventListener('submit', (e) => {
            const selectedSlot = document.querySelector('.time-slot.selected');
            if (!selectedSlot) {
                showNotification('Por favor selecciona un horario disponible.', 'error');
                e.preventDefault(); // Prevent form submission if no time is selected
                return;
            }
            // If a slot is selected, PHP will handle the submission
        });

        // Ticket search functionality (now interacts with PHP GET parameters)
        // The form submission handles the redirection and PHP processes it.
        const searchTicketButton = document.getElementById('search-ticket-btn');
        const ticketSearchInput = document.getElementById('ticket-search-input');
        const statusResultDiv = document.getElementById('status-result');

        // Function to animate timeline items
        function animateTimeline() {
            document.querySelectorAll('#status-result .timeline-item').forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('active');
                }, index * 300);
            });
        }

        // On page load, if a ticket was searched, animate the timeline
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('ticket_number') && statusResultDiv.style.display === 'block') {
                animateTimeline();
            }
             // Ensure the correct tab is active if navigated via direct link with hash
             const hash = window.location.hash.substring(1); // Get hash without '#'
            if (hash) {
                document.querySelectorAll('.nav-item').forEach(navItem => {
                    if (navItem.dataset.section === hash) {
                        navItem.click(); // Simulate click to activate section and run associated JS
                    }
                });
            }
        });


        // Knowledge base search
        document.querySelector('#knowledge .faq-search input').addEventListener('input', (e) => {
            const searchTerm = normalizeText(e.target.value); // Normalizar el término de búsqueda
            document.querySelectorAll('.knowledge-card').forEach(card => {
                const title = normalizeText(card.querySelector('h3').textContent); // Normalizar el título
                const content = normalizeText(card.querySelector('p').textContent); // Normalizar el contenido
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = searchTerm ? 'none' : 'block';
                }
            });
        });

        // Knowledge card click functionality
        document.querySelectorAll('.knowledge-card').forEach(card => {
            card.addEventListener('click', () => {
                const title = card.querySelector('h3').textContent;
                // In a real scenario, this would navigate to a detailed article page
                showNotification(`Abriendo: ${title} (Simulado - esta funcionalidad conectaría con tu sistema de documentación)`, 'info');
            });
        });

        // Add some interactive feedback
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('focus', () => {
                element.style.transform = 'scale(1.02)';
                element.style.transition = 'transform 0.2s ease';
            });
            
            element.addEventListener('blur', () => {
                element.style.transform = 'scale(1)';
            });
        });

        // Add loading states for better UX
        function showLoading(element) {
            const originalText = element.textContent;
            element.textContent = 'Cargando...';
            element.disabled = true;
            
            return () => {
                element.textContent = originalText;
                element.disabled = false;
            };
        }

        // Add notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 2rem;
                background: ${type === 'success' ? 'var(--verde-petroleo)' : (type === 'error' ? '#dc3545' : '#17a2b8')}; /* Added 'info' color */
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Enhanced form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#dc3545';
                        isValid = false;
                    } else {
                        field.style.borderColor = 'var(--gris-medio)';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showNotification('Por favor completa todos los campos requeridos.', 'error');
                }
            });
        });

        // Add smooth scrolling for better navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                setTimeout(() => {
                    document.querySelector('.content').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            });
        });

        // Initialize tooltips for better UX
        document.querySelectorAll('[title]').forEach(element => {
            // Note: Native browser tooltips are usually sufficient and accessible.
            // This custom tooltip logic is kept for demonstration but can be complex
            // to get right for accessibility. For this exercise, we'll simplify.
            // Let's ensure the JS doesn't remove the original title unless it truly implements a better one.
            // For now, removing the custom tooltip logic to rely on native `title` attribute for simplicity.
        });
    </script>
</body>
</html>