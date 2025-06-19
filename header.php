  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>
  <body>

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
</style>
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
  </body>
  </html>

  