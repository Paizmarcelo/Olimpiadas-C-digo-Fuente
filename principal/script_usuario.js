/**
 * @author MangueAR Development Team
 * @version 4.0.0
 * @description Archivo JavaScript principal para MangueAR.
 * Maneja la interactividad del usuario, renderizado dinámico de contenido,
 * gestión de carritos y favoritos, y efectos visuales.
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
        { id: 1, name: 'Riviera Maya de Lujo', price: 1500.00, imageUrl: '../Images/pexels-pho-tomass-883344227-32490384.jpg', description: '7 días en resorts exclusivos. Disfruta de playas de arena blanca, aguas turquesa y un servicio inigualable.', rating: 5, category: 'Lujo' },
        { id: 2, name: 'Safari Fotográfico en Tanzania', price: 3200.00, imageUrl: '../Images/pexels-saeb-mahajna-14125913-6297105.jpg', description: 'Captura la majestuosidad de la vida salvaje africana en un safari de 6 días en el Parque Nacional Serengeti.', rating: 5, category: 'Aventura' },
        { id: 3, name: 'Crucero por las Islas Griegas', price: 1800.00, imageUrl: '../Images/pexels-robimsel-32476257.jpg', description: '8 días de ensueño navegando por Santorini, Mykonos y Creta con todo incluido.', rating: 4, category: 'Crucero' },
        { id: 4, name: 'Maldivas Paradisíacas', price: 2800.00, imageUrl: '../Images/pexels-robimsel-32476256.jpg', description: '5 días en un bungalow sobre el agua, buceo y relax total en el paraíso.', rating: 5, category: 'Relax' },
        { id: 5, name: 'Recorrido por Vietnam y Camboya', price: 2100.00, imageUrl: '../Images/pexels-pho-tomass-883344227-32490398.jpg', description: '10 días explorando templos antiguos, mercados vibrantes y paisajes impresionantes.', rating: 4, category: 'Cultura' },

        // Sudamérica y Aventura
        { id: 6, name: 'Glaciares de la Patagonia Argentina', price: 1200.00, imageUrl: '../Images/pexels-pho-tomass-883344227-32477990.jpg', description: 'Aventura de 5 días en El Calafate, explorando el majestuoso Glaciar Perito Moreno y más.', rating: 5, category: 'Aventura' },
        { id: 7, name: 'Playas de Buzios y Río de Janeiro', price: 950.00, imageUrl: '../Images/pexels-njeromin-32416398.jpg', description: '7 días de sol, samba y playas icónicas en Brasil.', rating: 4, category: 'Playa' },
        { id: 8, name: 'Senderismo en la Quebrada de Humahuaca', price: 700.00, imageUrl: '../Images/pexels-njeromin-29376561.jpg', description: '4 días descubriendo los colores y la cultura del Norte Argentino.', rating: 4, category: 'Nacional' },
        { id: 9, name: 'Ciudad de Buenos Aires y Tango', price: 600.00, imageUrl: '../Images/pexels-njeromin-16572489.jpg', description: '3 días en la capital argentina, con shows de tango y tour cultural.', rating: 4, category: 'Urbano' },
        { id: 10, name: 'Cataratas del Iguazú Completo', price: 850.00, imageUrl: '../Images/pexels-mikhail-nilov-9400917.jpg', description: '4 días explorando ambos lados (Arg/Bra) de las imponentes cataratas.', rating: 5, category: 'Naturaleza' },

        // Europa y Cultura
        { id: 11, name: 'Capitales Imperiales (Europa)', price: 1600.00, imageUrl: '../Images/pexels-m-emre_celik-2054744248-32496666.jpg', description: '9 días recorriendo Viena, Praga y Budapest, un viaje al corazón de Europa.', rating: 4, category: 'Cultura' },
        { id: 12, name: 'Romance en París y Roma', price: 1900.00, imageUrl: '../Images/pexels-lavdrim-mustafi-337111893-14529445.jpg', description: '7 días de pura magia en las ciudades más románticas del mundo.', rating: 5, category: 'Romance' },
        { id: 13, name: 'Aventura Nórdica: Fiordos y Auroras', price: 2700.00, imageUrl: '../Images/pexels-fabian-lozano-2152897796-32469373.jpg', description: '7 días explorando los espectaculares fiordos de Noruega y la búsqueda de la aurora boreal.', rating: 5, category: 'Naturaleza' },
        { id: 14, name: 'Ruta del Vino por La Toscana', price: 1400.00, imageUrl: '../Images/pexels-dantemunozphoto-28821762.jpg', description: '5 días de degustaciones, paisajes y pueblos medievales en la campiña italiana.', rating: 4, category: 'Gastronomía' },
        { id: 15, name: 'Explorando Islandia: Tierra de Hielo y Fuego', price: 2300.00, imageUrl: '../Images/pexels-dantemunozphoto-15481505.jpg', description: '6 días descubriendo géiseres, glaciares y paisajes volcánicos únicos.', rating: 5, category: 'Aventura' },
    ];

    const flightsData = [
        { id: 1, destination: 'Tokio, Japón', price: 980.00, imageUrl: '../Images/pexels-azaay14-32435851.jpg', description: 'Vuelo ida y vuelta a la vibrante capital japonesa.' },
        { id: 2, destination: 'Nueva York, EE. UU.', price: 750.00, imageUrl: '../Images/pexels-athenea-codjambassis-rossitto-472760075-26977242.jpg', description: 'Descubre la Gran Manzana con nuestros mejores precios.' },
        { id: 3, destination: 'Londres, Reino Unido', price: 680.00, imageUrl: '../Images/pexels-asadphoto-11118954.jpg', description: 'Explora la historia y cultura de la capital británica.' },
        { id: 4, destination: 'Sídney, Australia', price: 1100.00, imageUrl: '../Images/pexels-asadphoto-1450360.jpg', description: 'Aventura al otro lado del mundo en la icónica Sídney.' },
        { id: 5, destination: 'Ciudad del Cabo, Sudáfrica', price: 920.00, imageUrl: '../Images/pexels-ahmetyuksek-30214899.jpg', description: 'Un viaje único a la puerta de África, con vistas impresionantes.' },
        { id: 6, destination: 'Ciudad de México, México', price: 450.00, imageUrl: '../Images/catarata de iguazu - misiones.jpg', description: 'Sumérgete en la rica historia y gastronomía mexicana.' },
        { id: 7, destination: 'Dubái, EAU', price: 880.00, imageUrl: '../Images/salta-argentina.jpg', description: 'Lujo y modernidad en el corazón del desierto.' },
        { id: 8, destination: 'Roma, Italia', price: 600.00, imageUrl: '../Images/Paso del Córdoba en la Ruta Provincial 63 de la….jpg', description: 'Viaja a la Ciudad Eterna y sus maravillas antiguas.' },
        { id: 9, destination: 'Bangkok, Tailandia', price: 790.00, imageUrl: '../Images/jujuy argenina.jpg', description: 'Descubre templos majestuosos y vibrantes mercados flotantes.' },
        { id: 10, destination: 'Toronto, Canadá', price: 580.00, imageUrl: '../Images/pexels-dantemunozphoto-15941836.jpg', description: 'Explora la multicultural ciudad de Toronto, ideal para todos.' },
    ];

    const reviewsData = [
        { id: 1, author: 'Ana G.', rating: 5, text: '¡MangueAR superó mis expectativas! Mi viaje a la Patagonia fue inolvidable. La organización impecable y el soporte 24/7 me dieron mucha tranquilidad.', avatar: '../Images/fotodeperfil4m.jpeg' },
        { id: 2, author: 'Carlos R.', rating: 4, text: 'Reservé un vuelo a Nueva York y todo fue muy sencillo. El precio fue el mejor que encontré. ¡Definitivamente los volveré a usar!', avatar: '../Images/fotodeperfil1.jpeg' },
        { id: 3, author: 'María L.', rating: 5, text: 'El paquete a las Islas Griegas fue un sueño hecho realidad. Cada detalle cuidado, desde los hoteles hasta las excursiones. ¡Altamente recomendado!', avatar: '../Images/fotodeperfil5m.jpeg' },
        { id: 4, author: 'Juan P.', rating: 3, text: 'El servicio de soporte es bueno, aunque tuve un pequeño inconveniente con un traslado. Lo resolvieron rápidamente, pero me hubiera gustado que fuera más fluido desde el inicio.', avatar: '../Images/fotodeperfil2.jpeg' },
        { id: 5, author: 'Sofía D.', rating: 5, text: '¡Mi safari en Tanzania fue la aventura de mi vida! La elección de los lodges y los guías fue excepcional. MangueAR me brindó una experiencia única.', avatar: '../Images/fotodeperfil6m.jpeg' },
        { id: 6, author: 'Pedro F.', rating: 4, text: 'Buen servicio y precios competitivos. La web es fácil de usar. Quizás podrían tener más opciones de paquetes personalizados, pero en general, muy bien.', avatar: '../Images/fotodeperfil3.jpeg' },
        { id: 7, author: 'Laura C.', rating: 5, text: 'La atención al cliente es fantástica. Me ayudaron a planificar un viaje familiar a Iguazú y todo salió perfecto. ¡Gracias por la paciencia y el profesionalismo!', avatar: '../Images/fotodeperfil7m.jpeg' },
    ];


    // =========================================================================
    // 2. SELECTORES DOM
    // =========================================================================
    const header = document.querySelector('.header');
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');

    const packagesGridContainer = document.getElementById('paquetes-grid-container');
    const viewMorePackagesBtn = document.getElementById('viewMorePackages');

    const flightsCarouselWrapper = document.querySelector('.flights-carousel .swiper-wrapper');
    const reviewsCarouselWrapper = document.querySelector('.reviews-carousel .swiper-wrapper');

    const favoriteIcon = document.getElementById('favoriteIcon');
    const favoriteCountSpan = document.getElementById('favoriteCount');
    const favoriteModal = document.getElementById('favoriteModal');
    const favoriteItemsContainer = document.getElementById('favoriteItemsContainer');
    const clearFavoritesBtn = document.getElementById('clearFavoritesBtn');

    const cartIcon = document.getElementById('cartIcon');
    const cartCountSpan = document.getElementById('cartCount');
    const cartModal = document.getElementById('cartModal');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const cartTotalElement = document.getElementById('cartTotal');
    const clearCartBtn = document.getElementById('clearCartBtn');
    const checkoutBtn = document.getElementById('checkoutBtn');

    const modalCloseButtons = document.querySelectorAll('.modal .close-button, .modal .close-modal-btn');

    const profileMenu = document.querySelector('.profile-menu');
    const profileDropdown = document.querySelector('.profile-dropdown');

    const faqQuestions = document.querySelectorAll('.faq-question');


    // =========================================================================
    // 3. ESTADO GLOBAL (Simulación de persistencia con localStorage)
    // =========================================================================
    let currentCart = JSON.parse(localStorage.getItem('manguearCart')) || [];
    let currentFavorites = JSON.parse(localStorage.getItem('manguearFavorites')) || [];
    let displayedPackagesCount = 6; // Cuántos paquetes se muestran inicialmente
    const packagesPerLoad = 6; // Cuántos paquetes se cargan con "Ver más"

    // =========================================================================
    // 4. FUNCIONES DE UTILIDAD
    // =========================================================================

    /**
     * Guarda el carrito en localStorage.
     */
    const saveCart = () => {
        localStorage.setItem('manguearCart', JSON.stringify(currentCart));
        updateCartCount();
    };

    /**
     * Guarda los favoritos en localStorage.
     */
    const saveFavorites = () => {
        localStorage.setItem('manguearFavorites', JSON.stringify(currentFavorites));
        updateFavoriteCount();
    };

    /**
     * Actualiza el contador del carrito en el header.
     */
    const updateCartCount = () => {
        cartCountSpan.textContent = currentCart.length;
    };

    /**
     * Actualiza el contador de favoritos en el header.
     */
    const updateFavoriteCount = () => {
        favoriteCountSpan.textContent = currentFavorites.length;
    };

    /**
     * Abre un modal específico.
     * @param {HTMLElement} modalElement - El elemento del modal a abrir.
     */
    const openModal = (modalElement) => {
        if (modalElement) {
            modalElement.classList.add('active');
            document.body.style.overflow = 'hidden'; // Evita scroll en el body
            // Renderizar contenido del modal al abrir
            if (modalElement.id === 'favoriteModal') {
                renderFavoriteItems();
            } else if (modalElement.id === 'cartModal') {
                renderCartItems();
            }
        }
    };

    /**
     * Cierra cualquier modal activo.
     */
    const closeModal = () => {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            activeModal.classList.remove('active');
            document.body.style.overflow = ''; // Habilita scroll en el body
        }
    };

    // =========================================================================
    // 5. RENDERIZADO DINÁMICO DE CONTENIDO
    // =========================================================================

    /**
     * Genera las estrellas de calificación.
     * @param {number} rating - La calificación (ej: 4.5).
     * @returns {string} HTML con los iconos de estrellas.
     */
    const generateStars = (rating) => {
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsHtml += '<i class="fas fa-star"></i>'; // Estrella llena
            } else if (i - 0.5 === rating) {
                starsHtml += '<i class="fas fa-star-half-alt"></i>'; // Media estrella
            } else {
                starsHtml += '<i class="far fa-star"></i>'; // Estrella vacía
            }
        }
        return starsHtml;
    };

    /**
     * Renderiza los paquetes turísticos en el grid.
     * @param {boolean} loadMore - Si se deben cargar más paquetes o reiniciar el grid.
     */
    const renderPackages = (loadMore = false) => {
        if (!loadMore) {
            packagesGridContainer.innerHTML = ''; // Limpiar si no es una carga adicional
        }

        const packagesToRender = packagesData.slice(0, displayedPackagesCount);

        packagesToRender.forEach(pkg => {
            const isFavorite = currentFavorites.some(fav => fav.id === pkg.id && fav.type === 'package');
            const packageCard = document.createElement('div');
            packageCard.classList.add('package-card');
            packageCard.dataset.id = pkg.id;
            packageCard.dataset.type = 'package';

            packageCard.innerHTML = `
                <div class="package-image">
                    <img src="${pkg.imageUrl}" alt="${pkg.name}">
                    <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${pkg.id}" data-type="package">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="package-content">
                    <span class="package-category">${pkg.category}</span>
                    <h3 class="package-title">${pkg.name}</h3>
                    <p class="package-description">${pkg.description}</p>
                    <div class="package-rating">
                        ${generateStars(pkg.rating)}
                    </div>
                    <div class="package-footer">
                        <span class="package-price">$${pkg.price.toLocaleString('es-AR')}</span>
                        <button class="btn btn-primary add-to-cart-btn" data-id="${pkg.id}" data-type="package">
                            Añadir al Carrito
                        </button>
                    </div>
                </div>
            `;
            packagesGridContainer.appendChild(packageCard);
        });

        // Ocultar botón "Ver más" si no hay más paquetes
        if (displayedPackagesCount >= packagesData.length) {
            viewMorePackagesBtn.style.display = 'none';
        } else {
            viewMorePackagesBtn.style.display = 'block';
        }
    };

    /**
     * Renderiza los vuelos en el carrusel.
     */
    const renderFlights = () => {
        flightsCarouselWrapper.innerHTML = '';
        flightsData.forEach(flight => {
            const isFavorite = currentFavorites.some(fav => fav.id === flight.id && fav.type === 'flight');
            const flightSlide = document.createElement('div');
            flightSlide.classList.add('swiper-slide');
            flightSlide.dataset.id = flight.id;
            flightSlide.dataset.type = 'flight';

            // Usar background-image para que ocupe todo el div
            flightSlide.innerHTML = `
                <div class="flight-card" style="background-image: url('${flight.imageUrl}');">
                    <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${flight.id}" data-type="flight">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div class="flight-card-content">
                        <h3>${flight.destination}</h3>
                        <p>${flight.description}</p>
                        <span class="flight-price">Desde $${flight.price.toLocaleString('es-AR')}</span>
                        <button class="btn btn-secondary add-to-cart-btn" data-id="${flight.id}" data-type="flight">
                            Reservar Vuelo
                        </button>
                    </div>
                </div>
            `;
            flightsCarouselWrapper.appendChild(flightSlide);
        });
        // Re-inicializar Swiper después de agregar slides
        initSwipers();
    };

    /**
     * Renderiza las reseñas en el carrusel.
     */
    const renderReviews = () => {
        reviewsCarouselWrapper.innerHTML = '';
        reviewsData.forEach(review => {
            const reviewSlide = document.createElement('div');
            reviewSlide.classList.add('swiper-slide');
            reviewSlide.innerHTML = `
                <div class="review-card">
                    <img src="${review.avatar}" alt="${review.author}" class="review-img">
                    <h4>${review.author}</h4>
                    <div class="review-rating">
                        ${generateStars(review.rating)}
                    </div>
                    <p>"${review.text}"</p>
                </div>
            `;
            reviewsCarouselWrapper.appendChild(reviewSlide);
        });
        // Re-inicializar Swiper después de agregar slides
        initSwipers();
    };

    /**
     * Renderiza los ítems en el modal de favoritos.
     */
    const renderFavoriteItems = () => {
        favoriteItemsContainer.innerHTML = '';
        if (currentFavorites.length === 0) {
            favoriteItemsContainer.innerHTML = '<p class="empty-message">Aún no tienes favoritos. ¡Explora y añade algunos!</p>';
            return;
        }

        currentFavorites.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('modal-item');
            itemElement.innerHTML = `
                <img src="${item.imageUrl}" alt="${item.name || item.destination}" class="modal-item-img">
                <div class="modal-item-details">
                    <h3>${item.name || item.destination}</h3>
                    <p>${item.type === 'package' ? 'Paquete' : 'Vuelo'}: $${(item.price || 0).toLocaleString('es-AR')}</p>
                </div>
                <button class="modal-item-remove" data-id="${item.id}" data-type="${item.type}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            favoriteItemsContainer.appendChild(itemElement);
        });
    };

    /**
     * Renderiza los ítems en el modal del carrito.
     */
    const renderCartItems = () => {
        cartItemsContainer.innerHTML = '';
        if (currentCart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="empty-message">Tu carrito está vacío. ¡Agrega tus próximos viajes!</p>';
            cartTotalElement.textContent = 'Total: $0';

           if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="empty-message">Tu carrito está vacío. ¡Agrega tus próximos viajes!</p>';
            cartTotalElement.textContent = 'Total: $0';
            // INICIO DE LA MODIFICACIÓN: DESHABILITAR BOTÓN DE COMPRA
            if (checkoutBtn) {
                checkoutBtn.disabled = true;
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.cursor = 'not-allowed';
            }
            // FIN DE LA MODIFICACIÓN
            return;
        }

        // INICIO DE LA MODIFICACIÓN: HABILITAR BOTÓN DE COMPRA
        if (checkoutBtn) {
            checkoutBtn.disabled = false;
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
        }
        }

        let total = 0;
        currentCart.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('modal-item');
            total += item.price || 0;

            itemElement.innerHTML = `
                <img src="${item.imageUrl}" alt="${item.name || item.destination}" class="modal-item-img">
                <div class="modal-item-details">
                    <h3>${item.name || item.destination}</h3>
                    <p>${item.type === 'package' ? 'Paquete' : 'Vuelo'}: $${(item.price || 0).toLocaleString('es-AR')}</p>
                </div>
                <button class="modal-item-remove" data-id="${item.id}" data-type="${item.type}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            cartItemsContainer.appendChild(itemElement);
        });
        cartTotalElement.textContent = `Total: $${total.toLocaleString('es-AR')}`;
    };


    // =========================================================================
    // 6. FUNCIONALIDAD DEL CARRITO Y FAVORITOS
    // =========================================================================

    /**
     * Alterna un paquete/vuelo en la lista de favoritos.
     * @param {number} id - ID del ítem.
     * @param {string} type - Tipo del ítem ('package' o 'flight').
     * @param {HTMLElement} buttonElement - El botón de favorito clickeado.
     */
    const toggleFavorite = (id, type, buttonElement) => {
        const isCurrentlyFavorite = currentFavorites.some(item => item.id === id && item.type === type);
        let itemToAdd = null;

        if (type === 'package') {
            itemToAdd = packagesData.find(pkg => pkg.id === id);
        } else if (type === 'flight') {
            itemToAdd = flightsData.find(flt => flt.id === id);
        }

        if (itemToAdd) {
            if (isCurrentlyFavorite) {
                currentFavorites = currentFavorites.filter(item => !(item.id === id && item.type === type));
                buttonElement.classList.remove('active');
                // Si el modal de favoritos está abierto, re-renderizar
                if (favoriteModal.classList.contains('active')) {
                    renderFavoriteItems();
                }
            } else {
                currentFavorites.push({ ...itemToAdd, type: type }); // Añadir tipo para diferenciar
                buttonElement.classList.add('active');
            }
            saveFavorites();
        }
    };

    /**
     * Añade un paquete/vuelo al carrito.
     * @param {number} id - ID del ítem.
     * @param {string} type - Tipo del ítem ('package' o 'flight').
     */
    const addToCart = (id, type) => {
        let itemToAdd = null;
        if (type === 'package') {
            itemToAdd = packagesData.find(pkg => pkg.id === id);
        } else if (type === 'flight') {
            itemToAdd = flightsData.find(flt => flt.id === id);
        }

        if (itemToAdd) {
            const existingItem = currentCart.find(item => item.id === id && item.type === type);
            if (!existingItem) { // Evita duplicados si solo quieres 1 por ID/tipo
                currentCart.push({ ...itemToAdd, type: type }); // Añadir tipo para diferenciar
                saveCart();
                alert(`${itemToAdd.name || itemToAdd.destination} ha sido añadido al carrito.`);
            } else {
                alert(`${itemToAdd.name || itemToAdd.destination} ya está en tu carrito.`);
            }
        }
    };

    /**
     * Elimina un ítem del carrito o favoritos.
     * @param {number} id - ID del ítem a eliminar.
     * @param {string} type - Tipo del ítem ('package' o 'flight').
     * @param {string} listType - 'cart' o 'favorites'.
     */
    const removeItem = (id, type, listType) => {
        if (listType === 'cart') {
            currentCart = currentCart.filter(item => !(item.id === id && item.type === type));
            saveCart();
            renderCartItems(); // Re-renderizar el modal
            alert('Elemento eliminado del carrito.');
        } else if (listType === 'favorites') {
            currentFavorites = currentFavorites.filter(item => !(item.id === id && item.type === type));
            saveFavorites();
            renderFavoriteItems(); // Re-renderizar el modal
            // Si el ítem eliminado es de un paquete, actualizar su botón de favorito en el grid
            const packageCard = document.querySelector(`.package-card[data-id="${id}"][data-type="${type}"] .favorite-btn`);
            if (packageCard) {
                packageCard.classList.remove('active');
            }
            const flightSlide = document.querySelector(`.swiper-slide[data-id="${id}"][data-type="${type}"] .favorite-btn`);
            if (flightSlide) {
                flightSlide.classList.remove('active');
            }
            alert('Elemento eliminado de favoritos.');
        }
    };

    /**
     * Vacía el carrito.
     */
    const clearCart = () => {
        if (confirm('¿Estás seguro de que quieres vaciar tu carrito?')) {
            currentCart = [];
            saveCart();
            renderCartItems();
            alert('El carrito ha sido vaciado.');
        }
    };

    /**
     * Vacía la lista de favoritos.
     */
    const clearFavorites = () => {
        if (confirm('¿Estás seguro de que quieres vaciar tus favoritos?')) {
            currentFavorites = [];
            saveFavorites();
            renderFavoriteItems();
            // Actualizar todos los botones de favoritos en la página
            document.querySelectorAll('.favorite-btn').forEach(btn => btn.classList.remove('active'));
            alert('La lista de favoritos ha sido vaciada.');
        }
    };


    // =========================================================================
    // 7. INICIALIZACIONES Y EVENT LISTENERS
    // =========================================================================

    // Cargar datos iniciales
    updateCartCount();
    updateFavoriteCount();

    // 7.1. Hero Section Content
    heroTitle.textContent = heroData.title;
    heroSubtitle.textContent = heroData.subtitle;

    // 7.2. Fixed Header on Scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // 7.3. Mobile Navigation (Hamburger)
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        document.body.classList.toggle('no-scroll'); // Añadir clase para evitar scroll en el body
    });

    // Cierra el menú móvil al hacer clic en un enlace de navegación
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            document.body.classList.remove('no-scroll');
        });
    });

    // 7.4. Profile Dropdown
    profileMenu.addEventListener('click', (event) => {
        event.stopPropagation(); // Evita que el clic se propague al documento
        profileDropdown.classList.toggle('active');
    });

    // Cierra el dropdown del perfil si se hace clic fuera
    document.addEventListener('click', (event) => {
        if (!profileMenu.contains(event.target) && profileDropdown.classList.contains('active')) {
            profileDropdown.classList.remove('active');
        }
    });

    // 7.5. Package Rendering and "View More"
    renderPackages();
    viewMorePackagesBtn.addEventListener('click', () => {
        displayedPackagesCount += packagesPerLoad;
        renderPackages(true); // Pasar true para indicar que es una carga adicional
    });

    // 7.6. Swiper.js Initialization
    let flightsSwiper = null; // Variable para almacenar la instancia de Swiper
    let reviewsSwiper = null;

    const initSwipers = () => {
        // Destruir instancias existentes si las hay antes de re-inicializar
        if (flightsSwiper) {
            flightsSwiper.destroy(true, true);
        }
        if (reviewsSwiper) {
            reviewsSwiper.destroy(true, true);
        }

        flightsSwiper = new Swiper('.flights-carousel', {
            loop: true, // Carrusel infinito
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: '.flights-carousel .swiper-button-next',
                prevEl: '.flights-carousel .swiper-button-prev',
            },
            pagination: {
                el: '.flights-carousel .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1440: { // Para pantallas más grandes, mostrar 4 o más
                    slidesPerView: 4,
                    spaceBetween: 30,
                }
            }
        });

        reviewsSwiper = new Swiper('.reviews-carousel', {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: '.reviews-carousel .swiper-button-next',
                prevEl: '.reviews-carousel .swiper-button-prev',
            },
            pagination: {
                el: '.reviews-carousel .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        });
    };

    // Renderizar vuelos y reseñas y luego inicializar Swiper
    renderFlights();
    renderReviews();


    // 7.7. Modals Event Listeners
    favoriteIcon.addEventListener('click', () => openModal(favoriteModal));
    cartIcon.addEventListener('click', () => openModal(cartModal));

    modalCloseButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });

    // Delegación de eventos para botones de carrito/favoritos (para elementos dinámicos)
    document.addEventListener('click', (event) => {
        // Botones de Favoritos
        if (event.target.closest('.favorite-btn')) {
            const btn = event.target.closest('.favorite-btn');
            const id = parseInt(btn.dataset.id);
            const type = btn.dataset.type;
            toggleFavorite(id, type, btn);
        }

        // Botones de Añadir al Carrito
        if (event.target.closest('.add-to-cart-btn')) {
            const btn = event.target.closest('.add-to-cart-btn');
            const id = parseInt(btn.dataset.id);
            const type = btn.dataset.type;
            addToCart(id, type);
        }

        // Botones de Eliminar de Carrito/Favoritos dentro de los modales
        if (event.target.closest('.modal-item-remove')) {
            const btn = event.target.closest('.modal-item-remove');
            const id = parseInt(btn.dataset.id);
            const type = btn.dataset.type;
            const parentModal = btn.closest('.modal');
            if (parentModal.id === 'cartModal') {
                removeItem(id, type, 'cart');
            } else if (parentModal.id === 'favoriteModal') {
                removeItem(id, type, 'favorites');
            }
        }
    });

    // Vaciar Carrito/Favoritos
    clearCartBtn.addEventListener('click', clearCart);
    clearFavoritesBtn.addEventListener('click', clearFavorites);

    // Funcionalidad de Checkout (ejemplo simple)
    checkoutBtn.addEventListener('click', () => {
        
        if (currentCart.length > 0) {
            alert('Procediendo al pago de tus artículos. ¡Gracias por tu compra!');
            currentCart = []; // Vaciar carrito después del "checkout"
            saveCart();
            closeModal();
        } else {
            alert('Tu carrito está vacío. ¡Añade algo antes de comprar!');
        }
        
    });

    // Cierra modal al hacer clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target === favoriteModal) {
            closeModal();
        }
        if (event.target === cartModal) {
            closeModal();
        }
    });


    // 7.8. FAQ Accordion
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const isExpanded = question.getAttribute('aria-expanded') === 'true';

            // Cierra todas las demás preguntas
            faqQuestions.forEach(q => {
                if (q !== question && q.getAttribute('aria-expanded') === 'true') {
                    q.setAttribute('aria-expanded', 'false');
                    q.nextElementSibling.style.maxHeight = null;
                    q.nextElementSibling.style.paddingTop = '0';
                    q.nextElementSibling.style.paddingBottom = '0';
                }
            });

            // Alterna la pregunta actual
            if (isExpanded) {
                question.setAttribute('aria-expanded', 'false');
                answer.style.maxHeight = null;
                answer.style.paddingTop = '0';
                answer.style.paddingBottom = '0';
            } else {
                question.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px'; // Ajusta la altura dinámicamente
                answer.style.paddingTop = '15px'; // Padding al expandir
                answer.style.paddingBottom = '25px'; // Padding al expandir
            }
        });
    });

// =========================================================================
    // 8. INTEGRACIÓN DE STRIPE (NUEVO)
    // =========================================================================

    // Inicializa Stripe con tu clave *publicable*
    // REEMPLAZA 'YOUR_STRIPE_PUBLISHABLE_KEY' con tu clave publicable real de Stripe
    const stripe = Stripe('pk_test_51RZUQGPFcLpCsDmjzWAwiDgPnrLdziqaOSANfQKDNVuHQITr18XA3Z8WEZLGeYX60VhYGO59NyqsD25QP4gEQBuU00XZgBhiVD'); // Ejemplo: pk_test_...

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', async () => {
            if (cart.length === 0) {
                alert('Tu carrito está vacío. ¡No hay nada que comprar!');
                return;
            }

            // Prepara los artículos para Stripe Checkout
            const lineItems = cart.map(item => {
                return {
                    price_data: {
                        currency: 'usd', // O tu moneda local (ej., 'ars' para Pesos Argentinos, pero confirma el soporte de Stripe para ello)
                        product_data: {
                            name: item.name,
                            images: item.imageUrl ? [item.imageUrl] : [], // Opcional: añadir imagen si está disponible
                            description: item.description || '',
                        },
                        unit_amount: Math.round(item.price * 100), // Precio en centavos (unidad monetaria más pequeña)
                    },
                    quantity: 1, // Asumiendo que cada artículo en el carrito es una sola cantidad
                };
            });

            try {
                // Envía los artículos del carrito a tu back-end PHP para crear una Sesión de Checkout
                const response = await fetch('create-checkout-session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ items: lineItems }),
                });

                const session = await response.json();

                if (session.error) {
                    alert(session.error);
                } else {
                    // Redirigir a Stripe Checkout
                    const result = await stripe.redirectToCheckout({
                        sessionId: session.id,
                    });

                    if (result.error) {
                        alert(result.error.message);
                    }
                }
            } catch (e) {
                console.error('Error durante el proceso de pago de Stripe:', e);
                alert('Hubo un error al procesar el pago. Por favor, inténtalo de nuevo.');
            }
        });
    }


    // =========================================================================
    // 10. CHATBOT INTEGRATION (NUEVO) - Lógica de Simulación
    // =========================================================================

    const chatbotToggleBtn = document.getElementById('chatbot-toggle-btn');
    const chatbotContainer = document.getElementById('chatbot-container');
    const closeChatbotBtn = document.querySelector('.close-chatbot-btn');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotInputField = document.getElementById('chatbot-input-field');
    const chatbotSendBtn = document.getElementById('chatbot-send-btn');
    const chatbotMenu = document.getElementById('chatbot-menu');

    // Mapeo de preguntas y respuestas predefinidas (puedes ampliar esto con más opciones)
    const chatbotResponses = {
        "vuelos": "Para información sobre vuelos, por favor visita nuestra sección de 'Vuelos' o usa el buscador en la parte superior de la página. Si necesitas ayuda con un destino específico, no dudes en preguntar.",
        "paquetes": "Explora nuestros emocionantes paquetes de viaje en la sección 'Paquetes Destacados'. Tenemos opciones para todos los gustos: lujo, aventura, cultural y más.",
        "soporte al cliente": "Puedes contactar a nuestro equipo de soporte las 24/7 a través de nuestro formulario de contacto en la sección 'Contacto' o llamando al 0800-MANGUEAR. ¿Cuál es tu consulta específica?",
        "reservas": "Para gestionar tus reservas, inicia sesión en tu cuenta o visita la sección 'Mis Reservas'. Si necesitas ayuda para reservar un nuevo viaje, podemos asistirte con el proceso de selección.",
        "cambios de pasajes": "Los cambios de pasajes están sujetos a las políticas de la aerolínea y la tarifa de tu boleto. Por favor, contacta a nuestro equipo de soporte al cliente con tu número de reserva para asistencia.",
        "métodos de pago": "Aceptamos pagos con tarjetas de crédito (Visa, MasterCard, American Express), débito y Mercado Pago. Todas las transacciones son seguras y procesadas a través de Stripe.",
        "preguntas frecuentes": "Te recomiendo visitar nuestra sección de 'Preguntas Frecuentes (FAQ)' al final de la página. Allí encontrarás respuestas a las dudas más comunes sobre reservas, pagos y servicios.",
        "hablar con un agente": "Si prefieres hablar con una persona, puedes llamarnos al 0800-MANGUEAR o solicitar una llamada de vuelta a través de nuestro formulario de contacto. Un agente estará contigo en breve para asistirte personalmente.",
        // Respuesta por defecto para entradas no reconocidas
        "default": "Disculpa, no entiendo tu pregunta. Por favor, intenta reformularla o elige una de las opciones del menú. ¿Hay algo más en lo que pueda ayudarte hoy?"
    };

    // Función para añadir un mensaje al contenedor del chat
    const addMessage = (text, sender) => {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${sender}-message`);
        messageDiv.innerHTML = text; // Permite HTML básico si lo necesitas (ej. <ul>)
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight; // Auto-scroll al final
    };

    // Función para obtener la respuesta del chatbot (simulada)
    const getBotResponse = (query) => {
        const normalizedQuery = query.toLowerCase().trim();

        // Busca coincidencias en las claves del objeto chatbotResponses
        for (const keyword in chatbotResponses) {
            if (normalizedQuery.includes(keyword)) {
                return chatbotResponses[keyword];
            }
        }
        return chatbotResponses["default"]; // Si no hay coincidencia, devuelve la respuesta por defecto
    };

    // Event Listener para abrir/cerrar el chatbot
    if (chatbotToggleBtn && chatbotContainer) {
        chatbotToggleBtn.addEventListener('click', () => {
            chatbotContainer.classList.toggle('active'); // Alterna la clase 'active'
            // Si el chatbot se abre, aseguramos el scroll al final
            if (chatbotContainer.classList.contains('active')) {
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }
        });
    }

    if (closeChatbotBtn && chatbotContainer) {
        closeChatbotBtn.addEventListener('click', () => {
            chatbotContainer.classList.remove('active'); // Cierra el chatbot
        });
    }

    // Event Listener para enviar mensaje (botón o Enter)
    const sendMessage = () => {
        const userQuery = chatbotInputField.value.trim();
        if (userQuery === "") return; // No enviar mensajes vacíos

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