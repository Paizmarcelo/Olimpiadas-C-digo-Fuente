// vuelos_usuario.js

document.addEventListener('DOMContentLoaded', () => {

    // =========================================================================
    // 1. SELECTORES DOM
    // =========================================================================
    const profilePictureTrigger = document.getElementById('profile-picture-trigger');
    const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
    const addSegmentBtn = document.querySelector('.add-segment-btn');
    // const searchForm = document.querySelector('.search-form'); // No se usa directamente en este contexto

    // Selectores para Carrito
    const cartLink = document.getElementById('cart-link');
    const cartCountSpan = document.getElementById('cart-count');
    const cartModalOverlay = document.getElementById('cart-modal-overlay');
    const closeCartModalBtn = document.querySelector('#cart-modal-overlay .close-modal-btn');
    const cartItemsList = document.getElementById('cart-items-list');
    const cartTotalSpan = document.getElementById('cart-total');
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn'); // Botones "Reservar" en cada tarjeta

    // Botones del modal de carrito
    const clearCartBtn = document.getElementById('clear-cart-btn');
    const continueShoppingBtn = document.getElementById('continue-shopping-btn');

    // Selectores para Favoritos
    const favoritesLink = document.getElementById('favorites-link');
    const favoritesCountSpan = document.getElementById('favorites-count');
    const favoriteButtons = document.querySelectorAll('.btn-favorite'); // Botones "Favoritos" en cada tarjeta

    // Selectores para el Modal de Favoritos
    const favoritesModalOverlay = document.getElementById('favorites-modal-overlay');
    const closeFavoritesModalBtn = document.querySelector('#favorites-modal-overlay .close-modal-btn');
    const favoritesItemsList = document.getElementById('favorites-items-list');

    // Botones del modal de favoritos
    const clearFavoritesBtn = document.getElementById('clear-favorites-btn');
    const continueViewingBtn = document.getElementById('continue-viewing-btn');

    // Botón para cambiar imagen de perfil (si lo deseas implementar)
    const changeProfileImageBtn = document.getElementById('change-profile-image-btn');

    // =========================================================================
    // 2. ESTADO GLOBAL (Simulación de datos del lado del cliente)
    // =========================================================================
    // Intentar cargar desde localStorage, si no hay, inicializar como array vacío
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let favoriteItems = JSON.parse(localStorage.getItem('favoriteItems')) || [];

    // =========================================================================
    // 3. FUNCIONES DE UTILIDAD
    // =========================================================================

    // Función para actualizar los contadores de carrito y favoritos en el header
    const updateCounts = () => {
        if (cartCountSpan) {
            cartCountSpan.textContent = cartItems.length;
        }
        if (favoritesCountSpan) {
            favoritesCountSpan.textContent = favoriteItems.length;
        }
    };

    // Función para guardar en Local Storage
    const saveToLocalStorage = () => {
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        localStorage.setItem('favoriteItems', JSON.stringify(favoriteItems));
    };

    // =========================================================================
    // 4. LÓGICA DEL CARRITO DE COMPRAS
    // =========================================================================

    // Abre el modal del carrito
    const openCartModal = (event) => {
        event.preventDefault(); // Evita el comportamiento predeterminado del enlace
        if (cartModalOverlay) {
            cartModalOverlay.style.display = 'flex';
            renderCartItems(); // Renderiza los ítems cada vez que se abre el modal
        }
    };

    // Cierra el modal del carrito
    const closeCartModal = () => {
        if (cartModalOverlay) {
            cartModalOverlay.style.display = 'none';
        }
    };

    // Renderiza los ítems en el modal del carrito
    const renderCartItems = () => {
        if (!cartItemsList) return;

        cartItemsList.innerHTML = ''; // Limpia la lista actual
        let total = 0;

        if (cartItems.length === 0) {
            cartItemsList.innerHTML = '<p style="text-align: center; color: var(--color-grey-dark);">El carrito está vacío.</p>';
        } else {
            cartItems.forEach(item => {
                const cartItemDiv = document.createElement('div');
                cartItemDiv.classList.add('cart-item');
                // Usamos item.image_origin como la imagen principal para el carrito
                cartItemDiv.style.cssText = `
                    display: flex;
                    align-items: center;
                    padding: 10px 0;
                    border-bottom: 1px solid #eee;
                `;
                cartItemDiv.innerHTML = `
                    <img src="${item.image_origin}" alt="${item.name}" class="cart-item-image" style="
                        width: 60px;
                        height: 60px;
                        object-fit: cover;
                        border-radius: 4px;
                        margin-right: 15px;
                    ">
                    <div class="cart-item-details" style="flex-grow: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; color: var(--color-dark);">${item.name}</h3>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--color-grey-dark);">${item.description.split('|')[0].trim()}</p>
                    </div>
                    <span class="cart-item-price" style="
                        font-weight: bold;
                        font-size: 1rem;
                        color: var(--color-petrol-green);
                        margin-right: 15px;
                    ">$${item.price.toFixed(2)}</span>
                    <button class="remove-from-cart-btn" data-id="${item.id}" style="
                        background: none;
                        border: none;
                        font-size: 1.5rem;
                        color: var(--color-danger);
                        cursor: pointer;
                        padding: 5px;
                    ">&times;</button>
                `;
                cartItemsList.appendChild(cartItemDiv);
                total += item.price;
            });
        }
        if (cartTotalSpan) {
            cartTotalSpan.textContent = `$${total.toFixed(2)}`;
        }
    };

    // Añade un ítem al carrito
    const addItemToCart = (event) => {
        const button = event.target.closest('.add-to-cart-btn');
        if (!button) return;

        const card = button.closest('.flight-card');
        if (!card) return;

        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const image_origin = card.dataset.imageOrigin;
        const image_destination = card.dataset.imageDestination;
        const description = card.dataset.description;

        const existingItem = cartItems.find(item => item.id === id);

        if (!existingItem) {
            cartItems.push({ id, name, price, image_origin, image_destination, description });
            alert(`"${name}" ha sido añadido al carrito.`);
        } else {
            alert(`"${name}" ya está en el carrito.`);
        }
        saveToLocalStorage();
        updateCounts();
    };

    // Elimina un ítem del carrito
    const removeItemFromCart = (event) => {
        if (event.target.classList.contains('remove-from-cart-btn')) {
            const idToRemove = event.target.dataset.id;
            cartItems = cartItems.filter(item => item.id !== idToRemove);
            saveToLocalStorage();
            updateCounts();
            renderCartItems(); // Vuelve a renderizar los ítems en el modal
        }
    };

    // Vaciar todo el carrito
    const clearCart = () => {
        if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
            cartItems = [];
            saveToLocalStorage();
            updateCounts();
            renderCartItems();
            alert('El carrito ha sido vaciado.');
        }
    };

    // =========================================================================
    // 5. LÓGICA DE FAVORITOS
    // =========================================================================

    // Abre el modal de favoritos
    const openFavoritesModal = (event) => {
        event.preventDefault();
        if (favoritesModalOverlay) {
            favoritesModalOverlay.style.display = 'flex';
            renderFavoritesItems(); // Renderiza los ítems favoritos cada vez que se abre
        }
    };

    // Cierra el modal de favoritos
    const closeFavoritesModal = () => {
        if (favoritesModalOverlay) {
            favoritesModalOverlay.style.display = 'none';
        }
    };

    // Renderiza los ítems en el modal de favoritos
    const renderFavoritesItems = () => {
        if (!favoritesItemsList) return;

        favoritesItemsList.innerHTML = ''; // Limpia la lista actual

        if (favoriteItems.length === 0) {
            favoritesItemsList.innerHTML = '<p style="text-align: center; color: var(--color-grey-dark);">No tienes vuelos favoritos guardados.</p>';
        } else {
            favoriteItems.forEach(item => {
                const favoriteItemDiv = document.createElement('div');
                favoriteItemDiv.classList.add('favorite-item');
                favoriteItemDiv.style.cssText = `
                    display: flex;
                    align-items: center;
                    padding: 10px 0;
                    border-bottom: 1px solid #eee;
                `;
                favoriteItemDiv.innerHTML = `
                    <img src="${item.image_origin}" alt="${item.name}" class="favorite-item-image" style="
                        width: 60px;
                        height: 60px;
                        object-fit: cover;
                        border-radius: 4px;
                        margin-right: 15px;
                    ">
                    <div class="favorite-item-details" style="flex-grow: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; color: var(--color-dark);">${item.name}</h3>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--color-grey-dark);">${item.description.split('|')[0].trim()}</p>
                    </div>
                    <button class="remove-from-favorites-btn" data-id="${item.id}" style="
                        background: none;
                        border: none;
                        font-size: 1.5rem;
                        color: var(--color-danger);
                        cursor: pointer;
                        padding: 5px;
                    ">&times;</button>
                `;
                favoritesItemsList.appendChild(favoriteItemDiv);
            });
        }
    };

    // Elimina un ítem de favoritos desde el modal
    const removeItemFromFavorites = (event) => {
        if (event.target.classList.contains('remove-from-favorites-btn')) {
            const idToRemove = event.target.dataset.id;
            favoriteItems = favoriteItems.filter(item => item.id !== idToRemove);
            saveToLocalStorage();
            updateCounts();
            renderFavoritesItems(); // Vuelve a renderizar los ítems en el modal de favoritos
            updateFavoriteButtonsState(); // Actualiza el estado del botón en la tarjeta de vuelo
        }
    };

    // Vaciar toda la lista de favoritos
    const clearFavorites = () => {
        if (confirm('¿Estás seguro de que quieres vaciar tu lista de favoritos?')) {
            favoriteItems = [];
            saveToLocalStorage();
            updateCounts();
            renderFavoritesItems();
            updateFavoriteButtonsState(); // Para desmarcar los corazones de las tarjetas
            alert('La lista de favoritos ha sido vaciada.');
        }
    };

    // Alterna el estado de favorito
    const toggleFavorite = (event) => {
        const button = event.target.closest('.btn-favorite');
        if (!button) return;

        const card = button.closest('.flight-card');
        if (!card) return;

        // Data attributes from the flight card
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price); // Aunque no se usa directamente en favoritos, se mantiene para consistencia
        const image_origin = card.dataset.imageOrigin;
        const image_destination = card.dataset.imageDestination;
        const description = card.dataset.description;

        const index = favoriteItems.findIndex(item => item.id === id);
        const icon = button.querySelector('i');

        if (index === -1) {
            // No está en favoritos, añadirlo
            favoriteItems.push({ id, name, price, image_origin, image_destination, description });
            button.classList.add('active');
            if (icon) {
                icon.classList.remove('far'); // Corazón vacío
                icon.classList.add('fas'); // Corazón sólido
            }
            alert(`"${name}" ha sido añadido a favoritos.`);
        } else {
            // Ya está en favoritos, eliminarlo
            favoriteItems.splice(index, 1);
            button.classList.remove('active');
            if (icon) {
                icon.classList.remove('fas'); // Corazón sólido
                icon.classList.add('far'); // Corazón vacío
            }
            alert(`"${name}" ha sido eliminado de favoritos.`);
        }
        saveToLocalStorage();
        updateCounts();
        // Si el modal de favoritos está abierto, renderiza de nuevo para reflejar el cambio
        if (favoritesModalOverlay && favoritesModalOverlay.style.display === 'flex') {
            renderFavoritesItems();
        }
    };

    // Actualiza el estado visual de los botones de favoritos al cargar la página
    // y después de cualquier cambio en la lista de favoritos
    const updateFavoriteButtonsState = () => {
        favoriteButtons.forEach(button => {
            const id = button.dataset.id;
            const icon = button.querySelector('i');
            if (favoriteItems.some(item => item.id === id)) {
                button.classList.add('active');
                if (icon) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }
            } else {
                button.classList.remove('active');
                if (icon) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            }
        });
    };

    // =========================================================================
    // 6. EVENT LISTENERS
    // =========================================================================

    // TOGGLE DEL MENÚ DE PERFIL
    if (profilePictureTrigger && profileDropdownMenu) {
        profilePictureTrigger.addEventListener('click', (event) => {
            event.stopPropagation(); // Evita que el clic se propague al documento
            profileDropdownMenu.classList.toggle('active'); // Usar 'active' para mostrar/ocultar
        });

        document.addEventListener('click', (event) => {
            // Cierra el menú si se hace clic fuera de él
            if (!profileDropdownMenu.contains(event.target) && !profilePictureTrigger.contains(event.target)) {
                profileDropdownMenu.classList.remove('active');
            }
        });
    }

    // Lógica para añadir tramo (ejemplo, puede ser más compleja)
    if (addSegmentBtn) {
        addSegmentBtn.addEventListener('click', () => {
            alert('Funcionalidad de añadir tramo aún no implementada.');
            // Aquí iría la lógica para añadir más campos de vuelo
        });
    }

    // Event listeners para Carrito
    if (cartLink) {
        cartLink.addEventListener('click', openCartModal);
    }
    if (closeCartModalBtn) {
        closeCartModalBtn.addEventListener('click', closeCartModal);
    }
    if (cartModalOverlay) {
        // Cierra el modal si se hace clic fuera del contenido del modal
        cartModalOverlay.addEventListener('click', (event) => {
            if (event.target === cartModalOverlay) {
                closeCartModal();
            }
        });
    }

    // Añadir ítems al carrito (delegación de eventos en los botones "Reservar")
    addToCartButtons.forEach(button => {
        button.addEventListener('click', addItemToCart);
    });

    // Eliminar ítems del carrito (delegación de eventos dentro del modal)
    if (cartItemsList) {
        cartItemsList.addEventListener('click', removeItemFromCart);
    }

    // Botones de acción del carrito
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', closeCartModal);
    }


    // Event listeners para Favoritos
    // Abrir modal de favoritos
    if (favoritesLink) {
        favoritesLink.addEventListener('click', openFavoritesModal);
    }
    // Cerrar modal de favoritos
    if (closeFavoritesModalBtn) {
        closeFavoritesModalBtn.addEventListener('click', closeFavoritesModal);
    }
    if (favoritesModalOverlay) {
        favoritesModalOverlay.addEventListener('click', (event) => {
            if (event.target === favoritesModalOverlay) {
                closeFavoritesModal();
            }
        });
    }
    // Añadir/quitar de favoritos desde las tarjetas
    favoriteButtons.forEach(button => {
        button.addEventListener('click', toggleFavorite);
    });
    // Eliminar ítems de favoritos desde el modal (usando delegación de eventos)
    if (favoritesItemsList) {
        favoritesItemsList.addEventListener('click', removeItemFromFavorites);
    }

    // Botones de acción de favoritos
    if (clearFavoritesBtn) {
        clearFavoritesBtn.addEventListener('click', clearFavorites);
    }
    if (continueViewingBtn) {
        continueViewingBtn.addEventListener('click', closeFavoritesModal);
    }


    // Event listener para el botón "Cambiar Imagen" (si existe)
    if (changeProfileImageBtn) {
        changeProfileImageBtn.addEventListener('click', (event) => {
            event.preventDefault(); // Evita que el enlace recargue la página
            alert('Funcionalidad para cambiar imagen de perfil aún no implementada.');
            // Aquí puedes agregar un modal para subir imagen, etc.
        });
    }


    // =========================================================================
    // 7. INICIALIZACIÓN AL CARGAR LA PÁGINA
    // =========================================================================
    updateCounts(); // Actualiza los contadores al cargar la página
    updateFavoriteButtonsState(); // Actualiza el estado visual de los botones de favoritos (corazones)
});