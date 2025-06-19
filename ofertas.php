<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangueAR - Ofertas Exclusivas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --color-petrol-green: #005047;
            --azul-profundo: #1e3a5f;
            --color-white: #ffffff;
            --color-light-gray: #f8f9fa;
            --color-gray: #6c757d;
            --color-success: #28a745;
            --color-warning: #ffc107;
            --color-danger: #dc3545;
            --color-orange: #ff6f00; /* Added for cart/favorite buttons */
            
               --font-family-base: 'Arial', sans-serif;
            --font-size-base: 1rem;
            --line-height-base: 1.5;
            
        }

       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--color-light-gray) 0%, #e9ecef 100%);
            color: var(--azul-profundo);
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            background-color: var(--color-petrol-green);
            color: var(--color-white);
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img img {
            display: block;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .tagline {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
        }

        .nav-item {
            color: var(--color-white);
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.9rem;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-icon {
            font-size: 1.2rem;
            margin-bottom: 3px;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .currency-selector {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--color-white);
            padding: 5px 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .hamburger {
            display: none; /* Hidden on desktop */
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .hamburger .bar {
            width: 25px;
            height: 3px;
            background-color: var(--color-white);
            border-radius: 2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            position: relative;
            color: var(--color-white);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .icon-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--color-danger);
            color: var(--color-white);
            font-size: 0.7rem;
            border-radius: 50%;
            padding: 2px 6px;
            line-height: 1;
        }

        .profile-menu {
            position: relative;
        }

        .profile-picture {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--color-white);
            object-fit: cover;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: 45px;
            right: 0;
            background-color: var(--color-white);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            min-width: 150px;
            z-index: 100;
            overflow: hidden;
        }

        .profile-dropdown a {
            display: block;
            padding: 10px 15px;
            color: var(--azul-profundo);
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .profile-dropdown a:hover {
            background-color: var(--color-light-gray);
        }

        /* End Header Styles */

        /* Cart Dropdown (Mini Cart) Styles */
        .cart-dropdown {
            display: none; /* Hidden by default */
            position: absolute;
            top: 45px; /* Adjust based on header height */
            right: 0;
            background-color: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 101;
            padding: 15px;
            animation: fadeIn 0.2s ease-out;
        }

        .cart-dropdown h4 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--azul-profundo);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .cart-items-mini {
            max-height: 200px; /* Scrollable area for items */
            overflow-y: auto;
            margin-bottom: 15px;
            padding-right: 5px; /* Space for scrollbar */
        }

        .cart-item-mini {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            background-color: var(--color-light-gray);
            border-radius: 5px;
        }

        .cart-item-mini img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 3px;
            margin-right: 10px;
        }

        .cart-item-mini-info {
            flex-grow: 1;
        }

        .cart-item-mini-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--azul-profundo);
            font-weight: 500;
        }

        .cart-item-mini-info span {
            color: var(--color-gray);
            font-size: 0.8rem;
        }

        .cart-item-mini-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cart-item-mini-actions button {
            background: none;
            border: 1px solid var(--color-gray);
            color: var(--color-gray);
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .cart-item-mini-actions button:hover {
            background-color: var(--color-gray);
            color: var(--color-white);
        }

        .cart-item-mini-actions .remove-btn-mini {
            background-color: var(--color-danger);
            border: none;
            color: var(--color-white);
            border-radius: 5px;
            padding: 4px 8px;
            font-size: 0.8rem;
            width: auto;
            height: auto;
        }
        .cart-item-mini-actions .remove-btn-mini:hover {
            background-color: #c82333;
            color: var(--color-white);
        }

        .cart-subtotal-mini {
            text-align: right;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--azul-profundo);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .cart-dropdown-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }

        .cart-dropdown-buttons button {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-dropdown-buttons .btn-clear-cart {
            background-color: var(--color-gray);
            color: var(--color-white);
            border: none;
        }
        .cart-dropdown-buttons .btn-clear-cart:hover {
            background-color: #5a6268;
        }

        .cart-dropdown-buttons .btn-continue-shopping {
            background-color: transparent;
            color: var(--azul-profundo);
            border: 1px solid var(--azul-profundo);
        }
        .cart-dropdown-buttons .btn-continue-shopping:hover {
            background-color: var(--azul-profundo);
            color: var(--color-white);
        }

        .cart-dropdown-buttons .btn-go-to-checkout {
            background: linear-gradient(135deg, var(--color-success) 0%, #218838 100%);
            color: var(--color-white);
            border: none;
        }
        .cart-dropdown-buttons .btn-go-to-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        /* End Cart Dropdown Styles */

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .filters-section {
            background: var(--color-white);
            padding: 2rem;
            margin: 2rem 0;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border-top: 4px solid var(--color-petrol-green);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--azul-profundo);
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--color-petrol-green);
            box-shadow: 0 0 0 3px rgba(0, 80, 71, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-petrol-green) 0%, var(--azul-profundo) 100%);
            color: var(--color-white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 80, 71, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--color-gray);
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: var(--color-light-gray);
            border-color: var(--color-petrol-green);
            color: var(--azul-profundo);
        }

        .stats-bar {
            background: var(--color-white);
            padding: 1rem 2rem;
            margin: 1rem 0;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .results-count {
            font-weight: 600;
            color: var(--azul-profundo);
        }

        .sort-options select {
            padding: 0.5rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            background: var(--color-white);
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .offer-card {
            background: var(--color-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .offer-image {
            height: 200px;
            background-color: #f0f0f0; /* Placeholder background */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            font-size: 1.5rem;
            font-weight: 600;
            overflow: hidden; /* Ensure image doesn't overflow */
        }
        .offer-image img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Cover the area, cropping if necessary */
        }

        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--color-danger);
            color: var(--color-white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            z-index: 1; /* Ensure badge is above image */
        }

        .offer-content {
            padding: 1.5rem;
        }

        .offer-type {
            display: inline-block;
            background: var(--color-petrol-green);
            color: var(--color-white);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .offer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--azul-profundo);
        }

        .offer-description {
            color: var(--color-gray);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .offer-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .offer-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .offer-detail-label {
            color: var(--color-gray);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .offer-detail-value {
            font-weight: 600;
            color: var(--azul-profundo);
        }

        .price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .original-price {
            text-decoration: line-through;
            color: var(--color-gray);
            font-size: 1rem;
        }

        .current-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-success);
        }

        .savings {
            background: var(--color-warning);
            color: var(--azul-profundo);
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .offer-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap; /* Allow buttons to wrap */
        }

        .btn-book, .btn-details, .btn-favorite, .btn-add-to-cart {
            flex: 1;
            background: linear-gradient(135deg, var(--color-petrol-green) 0%, var(--azul-profundo) 100%);
            color: var(--color-white);
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 120px; /* Ensure buttons don't get too small */
        }

        .btn-book:hover, .btn-details:hover, .btn-favorite:hover, .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 80, 71, 0.3);
        }
        
        /* Specific button styles */
        .btn-details {
            background: transparent;
            color: var(--color-petrol-green);
            border: 2px solid var(--color-petrol-green);
        }
        .btn-details:hover {
            background: var(--color-petrol-green);
            color: var(--color-white);
        }

        .btn-favorite {
            background-color: var(--color-gray); /* Default for favorite */
        }
        .btn-favorite.active {
            background-color: var(--color-danger); /* When added to favorites */
        }
        .btn-add-to-cart {
            background-color: var(--color-orange); /* Default for cart */
        }
        .btn-add-to-cart.active {
            background-color: var(--color-success); /* When added to cart */
        }


        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--color-gray);
        }

        .no-results h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Footer Styles */
        .footer {
            background: var(--azul-profundo);
            color: var(--color-white);
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
            margin-bottom: 1.5rem;
        }

        .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--color-white);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: var(--color-white);
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer-section ul li a:hover {
            opacity: 1;
        }

        .footer-social-icons {
            display: flex;
            gap: 15px;
            margin-top: 1rem;
        }

        .footer-social-icons a {
            color: var(--color-white);
            font-size: 1.5rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer-social-icons a:hover {
            opacity: 1;
        }

        .footer-bottom-text {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            opacity: 0.7;
        }
        /* End Footer Styles */

        /* Modal Styles */
        .modal {
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
            padding-top: 60px; /* Location of the box */
        }

        .modal-content {
            background-color: var(--color-white);
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 600px;
            position: relative;
            animation: fadeIn 0.3s ease-out;
            max-height: 90vh; /* Limit height */
            display: flex;
            flex-direction: column;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close-button {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-button:hover,
        .close-button:focus {
            color: var(--color-danger);
            text-decoration: none;
        }

        .modal-content h2 {
            margin-top: 0;
            color: var(--azul-profundo);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--color-petrol-green);
            padding-bottom: 10px;
        }

        .modal-items-container {
            flex-grow: 1;
            overflow-y: auto; /* Enable scrolling for items */
            margin-bottom: 20px;
            padding-right: 10px; /* Space for scrollbar */
        }

        .modal-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: var(--color-light-gray);
        }

        .modal-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .modal-item-info {
            flex-grow: 1;
        }

        .modal-item-info h4 {
            margin: 0 0 5px 0;
            color: var(--azul-profundo);
            font-size: 1.1rem;
        }

        .modal-item-info p {
            margin: 0;
            color: var(--color-gray);
            font-size: 0.9rem;
        }

        .modal-item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-item-actions .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .modal-item-actions .quantity-control button {
            background-color: var(--color-petrol-green);
            color: var(--color-white);
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease;
        }
        .modal-item-actions .quantity-control button:hover {
            background-color: var(--azul-profundo);
        }

        .modal-item-actions .remove-btn {
            background-color: var(--color-danger);
            color: var(--color-white);
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }

        .modal-item-actions .remove-btn:hover {
            background-color: #c82333;
        }

        .cart-total {
            text-align: right;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--azul-profundo);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .modal-footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-modal-clear {
            background-color: var(--color-gray);
            color: var(--color-white);
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-modal-clear:hover {
            background-color: #5a6268;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--color-success) 0%, #218838 100%);
            color: var(--color-white);
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        /* End Modal Styles */


        @media (max-width: 768px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .offers-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .header-content {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-menu {
                display: none; /* Hide on small screens, will be toggled by hamburger */
                flex-direction: column;
                width: 100%;
                text-align: center;
                margin-top: 15px;
            }
            .nav-menu.active {
                display: flex;
            }
            .nav-item {
                padding: 10px;
                width: 100%;
            }

            .user-section {
                width: 100%;
                justify-content: center;
                margin-top: 15px;
            }

            .hamburger {
                display: flex; /* Show hamburger on small screens */
            }

            .header-right {
                flex-grow: 1;
                justify-content: flex-end;
            }

            .footer-content {
                flex-direction: column;
                align-items: center;
            }
            .footer-section {
                width: 100%;
                text-align: center;
            }
            .footer-social-icons {
                justify-content: center;
            }

            .modal-content {
                width: 95%; /* Adjust for smaller screens */
                padding: 20px;
            }

            .modal-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .modal-item img {
                margin-right: 0;
                margin-bottom: 10px;
            }
            .modal-item-actions {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }
            .modal-item-actions .quantity-control {
                justify-content: center;
                flex-grow: 1;
            }
            .modal-item-actions .remove-btn {
                flex-grow: 1;
            }

            .cart-dropdown {
                right: 5px; /* Adjust for small screens */
                width: calc(100% - 10px);
            }
        }

        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .filter-chip {
            background: var(--color-petrol-green);
            color: var(--color-white);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-chip button {
            background: none;
            border: none;
            color: var(--color-white);
            cursor: pointer;
            font-size: 1rem;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--color-petrol-green);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-img"><img src="Images/logomapache-removebg-preview.png" height="40px" width="40px" alt="MangueAR Logo"></div> <div>
                    <div class="brand-name">MangueAR</div>
                    <div class="tagline">Pagá menos por viajar</div>
                </div>
            </div>
            
            <nav class="nav-menu" id="navMenu">
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
  <a href="ofertas.php" class="nav-item">                    <i class="fa-solid fa-tag nav-icon"></i>
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
                <div class="hamburger" id="hamburger">
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
                        <div class="cart-dropdown" id="cart-dropdown-menu">
                            <h4>Mi Carrito</h4>
                            <div class="cart-items-mini" id="cart-items-mini">
                                </div>
                            <div class="cart-subtotal-mini" id="cart-subtotal-mini">Subtotal: $0</div>
                            <div class="cart-dropdown-buttons">
                                <button class="btn-clear-cart" onclick="clearCartDropdown()">Vaciar Carrito</button>
                                <button class="btn-continue-shopping" onclick="continueShopping()">Seguir Comprando</button>
                                <button class="btn-go-to-checkout" onclick="goToCheckout()">Ir al Checkout</button>
                            </div>
                        </div>
                    </div>
                    <div class="profile-menu">
                        <img src="Images/perfil_usuario.png" id="profile-picture-trigger" class="profile-picture" alt="Profile Picture">
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
        </div>
    </header>

    <div class="container">
        <section class="filters-section">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="discount-filter">Filtrar por Descuento</label>
                    <select id="discount-filter">
                        <option value="">Todos los descuentos</option>
                        <option value="10">10% OFF</option>
                        <option value="15">15% OFF</option>
                        <option value="20">20% OFF</option>
                        <option value="25">25% OFF</option>
                        <option value="30">30% OFF</option>
                        <option value="35">35% OFF</option>
                        <option value="40">40% OFF</option>
                        <option value="50">50% OFF</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="type-filter">Tipo de Oferta</label>
                    <select id="type-filter">
                        <option value="">Todos</option>
                        <option value="paquete">Paquetes</option>
                        <option value="vuelo">Vuelos</option>
                        <option value="hotel">Hoteles</option>
                        <option value="crucero">Cruceros</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="destination-filter">Destino</label>
                    <select id="destination-filter">
                        <option value="">Todos los destinos</option>
                        <option value="europa">Europa</option>
                        <option value="america">América</option>
                        <option value="asia">Asia</option>
                        <option value="oceania">Oceanía</option>
                        <option value="africa">África</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="price-max">Precio Máximo</label>
                    <input type="number" id="price-max" placeholder="ej: 1000" min="0">
                </div>
                
                <div class="filter-group">
                    <button class="btn btn-primary" onclick="applyFilters()">🔍 Buscar Ofertas</button>
                </div>
                
                <div class="filter-group">
                    <button class="btn btn-secondary" onclick="clearFilters()">🗑️ Limpiar Filtros</button>
                </div>
            </div>
            
            <div class="filter-chips" id="active-filters"></div>
        </section>

        <div class="stats-bar">
            <div class="results-count" id="results-count">Mostrando 12 ofertas disponibles</div>
            <div class="sort-options">
                <label for="sort-by">Ordenar por:</label>
                <select id="sort-by" onchange="sortOffers()">
                    <option value="discount">Mayor descuento</option>
                    <option value="price-low">Precio: menor a mayor</option>
                    <option value="price-high">Precio: mayor a menor</option>
                    <option value="duration">Duración</option>
                </select>
            </div>
        </div>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Buscando las mejores ofertas...</p>
        </div>

        <div class="offers-grid" id="offers-grid">
            </div>

        <div class="no-results" id="no-results" style="display: none;">
            <h3>😔 No se encontraron ofertas</h3>
            <p>Intenta ajustar tus filtros para encontrar más opciones</p>
        </div>
    </div>

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

    <div id="cart-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('cart-modal')">&times;</span>
            <h2>🛒 Tu Carrito</h2>
            <div class="modal-items-container" id="cart-items-container">
                </div>
            <div class="cart-total" id="cart-total">Total: $0</div>
            <div class="modal-footer-buttons">
                <button class="btn-modal-clear" onclick="clearCart()">Vaciar Carrito</button>
                <button class="btn-checkout" onclick="alert('Procediendo al pago...'); closeModal('cart-modal');">Pagar Ahora</button>
            </div>
        </div>
    </div>

    <div id="favorites-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('favorites-modal')">&times;</span>
            <h2>❤️ Tus Favoritos</h2>
            <div class="modal-items-container" id="favorites-items-container">
                </div>
            <div class="modal-footer-buttons">
                <button class="btn-modal-clear" onclick="clearFavorites()">Vaciar Favoritos</button>
            </div>
        </div>
    </div>

    <script>
        // Base de datos de ofertas
        const offers = [
            {
                id: 1,
                type: 'paquete',
                title: 'París + Londres - Tour Completo',
                description: 'Descubre dos de las ciudades más románticas de Europa con vuelos incluidos y guía especializado.',
                destination: 'europa',
                originalPrice: 1200,
                discount: 25,
                duration: '8 días',
                includes: 'Vuelos + Hotel + Tours',
                image: 'Images/pexels-dantemunozphoto-15941836.jpg' // Placeholder image
            },
            {
                id: 2,
                type: 'vuelo',
                title: 'Buenos Aires - Miami',
                description: 'Vuelos directos con la mejor aerolínea, equipaje incluido y asientos preferenciales.',
                destination: 'america',
                originalPrice: 800,
                discount: 40,
                duration: '8 horas',
                includes: 'Vuelo + Equipaje + Comida',
                image: 'Images/pexels-ahmetyuksek-30214899.jpg' // Placeholder image
            },
            {
                id: 3,
                type: 'paquete',
                title: 'Tokio - Experiencia Cultural',
                description: 'Sumérgete en la cultura japonesa con tours gastronómicos y visitas a templos tradicionales.',
                destination: 'asia',
                originalPrice: 1500,
                discount: 20,
                duration: '10 días',
                includes: 'Hotel + Tours + JR Pass',
                image: 'Images/pexels-asadphoto-1450360.jpg' // Placeholder image
            },
            {
                id: 4,
                type: 'hotel',
                title: 'Resort Caribe - Todo Incluido',
                description: 'Relájate en playas paradisíacas con servicio de lujo y actividades acuáticas.',
                destination: 'america',
                originalPrice: 900,
                discount: 35,
                duration: '7 noches',
                includes: 'Todo Incluido + Spa',
                image: 'Images/pexels-asadphoto-11118954.jpg' // Placeholder image
            },
            {
                id: 5,
                type: 'crucero',
                title: 'Crucero Mediterráneo',
                description: 'Navega por las aguas cristalinas del Mediterráneo visitando múltiples destinos.',
                destination: 'europa',
                originalPrice: 1100,
                discount: 30,
                duration: '12 días',
                includes: 'Crucero + Excursiones + Comidas',
                image: 'Images/pexels-athenea-codjambassis-rossitto-472760075-26977242.jpg' // Placeholder image
            },
            {
                id: 6,
                type: 'vuelo',
                title: 'Buenos Aires - Barcelona',
                description: 'Vuelos con escalas cómodas, excelente servicio y precios imbatibles.',
                destination: 'europa',
                originalPrice: 950,
                discount: 15,
                duration: '14 horas',
                includes: 'Vuelo + Comida + Entretenimiento',
                image: 'Images/pexels-dantemunozphoto-15481505.jpg' // Placeholder image
            },
            {
                id: 7,
                type: 'paquete',
                title: 'Nueva York - Ciudad que Nunca Duerme',
                description: 'Explora la Gran Manzana con tours por Manhattan y espectáculos de Broadway.',
                destination: 'america',
                originalPrice: 1300,
                discount: 25,
                duration: '6 días',
                includes: 'Hotel + Broadway + Tours',
                image: 'Images/pexels-dantemunozphoto-15941831.jpg' // Placeholder image
            },
            {
                id: 8,
                type: 'paquete',
                title: 'Safari en Kenia',
                description: 'Aventura única en la sabana africana con los Big Five y cultura Masai.',
                destination: 'africa',
                originalPrice: 2000,
                discount: 20,
                duration: '14 días',
                includes: 'Safari + Lodge + Guía',
                image: 'Images/pexels-fabian-lozano-2152897796-32469373.jpg' // Placeholder image
            },
            {
                id: 9,
                type: 'hotel',
                title: 'Bali - Retiro Espiritual',
                description: 'Encuentra la paz interior en los templos de Bali con yoga y meditación.',
                destination: 'asia',
                originalPrice: 750,
                discount: 30,
                duration: '9 noches',
                includes: 'Yoga + Spa + Templos',
                image: 'Images/pexels-robimsel-32476254.jpg' // Placeholder image
            },
            {
                id: 10,
                type: 'paquete',
                title: 'Australia - Costa Este',
                description: 'Descubre Sydney, Melbourne y la Gran Barrera de Coral en un viaje inolvidable.',
                destination: 'oceania',
                originalPrice: 1800,
                discount: 25,
                duration: '16 días',
                includes: 'Vuelos + Hotels + Tours',
                image: 'Images/pexels-robimsel-32476256.jpg' // Placeholder image
            },
            {
                id: 11,
                type: 'vuelo',
                title: 'Buenos Aires - Cancún',
                description: 'Escapa al paraíso mexicano con vuelos directos y las mejores tarifas.',
                destination: 'america',
                originalPrice: 600,
                discount: 50,
                duration: '9 horas',
                includes: 'Vuelo + Comida + Bebidas',
                image: 'Images/pexels-dantemunozphoto-28821762.jpg' // Placeholder image
            },
            {
                id: 12,
                type: 'crucero',
                title: 'Fiordos Noruegos',
                description: 'Navega entre paisajes espectaculares y auroras boreales en Noruega.',
                destination: 'europa',
                originalPrice: 1400,
                discount: 35,
                duration: '11 días',
                includes: 'Crucero + Excursiones + Fotografía',
                image: 'Images/pexels-mikhail-nilov-9400917.jpg' // Placeholder image
            },
            {
                id: 13,
                type: 'paquete',
                title: 'Roma - Tour Histórico',
                description: 'Explora la Ciudad Eterna con visitas al Coliseo, Vaticano y Fontana di Trevi.',
                destination: 'europa',
                originalPrice: 1100,
                discount: 30,
                duration: '7 días',
                includes: 'Hotel + Tours + Guía',
                image: 'Images/pexels-njeromin-32416398.jpg' // Placeholder image
            },
            {
                id: 14,
                type: 'vuelo',
                title: 'Buenos Aires - Dubai',
                description: 'Vuelos de lujo con Emirates, asientos premium y servicio de primera clase.',
                destination: 'asia',
                originalPrice: 1200,
                discount: 20,
                duration: '16 horas',
                includes: 'Vuelo Premium + Lounge + Comida',
                image: 'Images/pexels-pho-tomass-883344227-32490384.jpg' // Placeholder image
            },
            {
                id: 15,
                type: 'hotel',
                title: 'Maldivas - Villa Overwater',
                description: 'Lujo absoluto en villas sobre el agua cristalina con servicio mayordomo.',
                destination: 'asia',
                originalPrice: 2500,
                discount: 25,
                duration: '5 noches',
                includes: 'Villa + Mayordomo + Spa',
                image: 'Images/pexels-pho-tomass-883344227-32477990 copy.jpg' // Placeholder image
            },
            {
                id: 16,
                type: 'paquete',
                title: 'Tailandia - Aventura Completa',
                description: 'Bangkok, Chiang Mai y Phuket. Templos, elefantes y playas paradisíacas.',
                destination: 'asia',
                originalPrice: 1400,
                discount: 35,
                duration: '12 días',
                includes: 'Vuelos + Hoteles + Tours',
                image: 'Images/thailand.jpg' // Placeholder image
            },
            {
                id: 17,
                type: 'crucero',
                title: 'Alaska - Glaciares y Vida Salvaje',
                description: 'Navega entre glaciares y observa ballenas, osos y águilas en su hábitat natural.',
                destination: 'america',
                originalPrice: 1800,
                discount: 20,
                duration: '10 días',
                includes: 'Crucero + Excursiones + Fotografía',
                image: 'Images/alaska.jpg' // Placeholder image
            },
            {
                id: 18,
                type: 'vuelo',
                title: 'Buenos Aires - Los Ángeles',
                description: 'Vuelos directos a la ciudad de los sueños con todas las comodidades.',
                destination: 'america',
                originalPrice: 900,
                discount: 30,
                duration: '11 horas',
                includes: 'Vuelo + Entretenimiento + WiFi',
                image: 'Images/los_angeles.jpg' // Placeholder image
            },
            {
                id: 19,
                type: 'paquete',
                title: 'Egipto - Misterios del Nilo',
                description: 'Pirámides, crucero por el Nilo y templos faraónicos en una aventura única.',
                destination: 'africa',
                originalPrice: 1600,
                discount: 25,
                duration: '10 días',
                includes: 'Crucero Nilo + Pirámides + Guía',
                image: 'Images/egypt.jpg' // Placeholder image
            },
            {
                id: 20,
                type: 'hotel',
                title: 'Santorini - Sunset Villa',
                description: 'Villa con vista al mar Egeo y los atardeceres más románticos del mundo.',
                destination: 'europa',
                originalPrice: 1300,
                discount: 40,
                duration: '6 noches',
                includes: 'Villa + Desayuno + Transfer',
                image: 'Images/santorini.jpg' // Placeholder image
            },
            {
                id: 21,
                type: 'paquete',
                title: 'Perú - Camino Inca a Machu Picchu',
                description: 'Trekking de 4 días por el legendario Camino Inca hasta la ciudadela perdida.',
                destination: 'america',
                originalPrice: 800,
                discount: 15,
                duration: '8 días',
                includes: 'Trekking + Camping + Guía',
                image: 'Images/machu_picchu.jpg' // Placeholder image
            },
            {
                id: 22,
                type: 'vuelo',
                title: 'Buenos Aires - Londres',
                description: 'Vuelos nocturnos con British Airways, camas horizontales y desayuno inglés.',
                destination: 'europa',
                originalPrice: 1100,
                discount: 35,
                duration: '13 horas',
                includes: 'Vuelo + Cama + Desayuno',
                image: 'Images/london.jpg' // Placeholder image
            },
            {
                id: 23,
                type: 'crucero',
                title: 'Caribe - Islas Paradisíacas',
                description: 'Recorre Barbados, Jamaica y Bahamas en un crucero todo incluido.',
                destination: 'america',
                originalPrice: 1200,
                discount: 45,
                duration: '9 días',
                includes: 'Todo Incluido + Excursiones + Shows',
                image: 'Images/caribbean_cruise.jpg' // Placeholder image
            },
            {
                id: 24,
                type: 'paquete',
                title: 'Islandia - Aurora Boreal',
                description: 'Caza auroras boreales, géiseres, cascadas y la famosa Laguna Azul.',
                destination: 'europa',
                originalPrice: 1700,
                discount: 20,
                duration: '8 días',
                includes: 'Hotel + Tours + Laguna Azul',
                image: 'Images/iceland_aurora.jpg' // Placeholder image
            }
        ];

        let filteredOffers = [...offers];
        let activeFilters = {};

        // Get favorites and cart from localStorage
        // Cart will store objects: {id: offerId, quantity: N}
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function updateCounts() {
            document.getElementById('favorites-count').textContent = favorites.length;
            const totalCartItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = totalCartItems;
            // Also update the mini-cart
            renderCartDropdownItems();
        }

        function renderOffers(offersToRender) {
            const grid = document.getElementById('offers-grid');
            const resultsCount = document.getElementById('results-count');
            
            if (offersToRender.length === 0) {
                grid.style.display = 'none';
                document.getElementById('no-results').style.display = 'block';
                resultsCount.textContent = 'No se encontraron ofertas';
                return;
            }

            grid.style.display = 'grid';
            document.getElementById('no-results').style.display = 'none';
            resultsCount.textContent = `Mostrando ${offersToRender.length} ofertas disponibles`;

            grid.innerHTML = offersToRender.map(offer => {
                const currentPrice = offer.originalPrice * (1 - offer.discount / 100);
                const savings = offer.originalPrice - currentPrice;
                const isFavorite = favorites.includes(offer.id);
                const isInCart = cart.some(item => item.id === offer.id); // Check if offer ID exists in cart

                return `
                    <div class="offer-card">
                        <div class="offer-image">
                            <img src="${offer.image}" alt="${offer.title}">
                            <div class="discount-badge">${offer.discount}% OFF</div>
                        </div>
                        <div class="offer-content">
                            <div class="offer-type">${offer.type}</div>
                            <h3 class="offer-title">${offer.title}</h3>
                            <p class="offer-description">${offer.description}</p>
                            
                            <div class="offer-details">
                                <div class="offer-detail">
                                    <div class="offer-detail-label">Duración</div>
                                    <div class="offer-detail-value">${offer.duration}</div>
                                </div>
                                <div class="offer-detail">
                                    <div class="offer-detail-label">Incluye</div>
                                    <div class="offer-detail-value">${offer.includes}</div>
                                </div>
                            </div>
                            
                            <div class="price-section">
                                <div>
                                    <div class="original-price">$${offer.originalPrice}</div>
                                    <div class="current-price">$${Math.round(currentPrice)}</div>
                                </div>
                                <div class="savings">Ahorras $${Math.round(savings)}</div>
                            </div>
                            
                            <div class="offer-actions">
                                <button class="btn-book" onclick="bookOffer(${offer.id})">Reservar Ahora</button>
                                <button class="btn-details" onclick="showDetails(${offer.id})">Ver Más</button>
                                <button class="btn-favorite ${isFavorite ? 'active' : ''}" onclick="toggleFavorite(${offer.id})">
                                    <i class="fas fa-heart"></i> Favorito
                                </button>
                                <button class="btn-add-to-cart ${isInCart ? 'active' : ''}" onclick="addToCart(${offer.id})">
                                    <i class="fas fa-shopping-cart"></i> ${isInCart ? 'En Carrito' : 'Añadir al Carrito'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            updateCounts();
        }

        function applyFilters() {
            showLoading();
            
            setTimeout(() => {
                const discountFilter = document.getElementById('discount-filter').value;
                const typeFilter = document.getElementById('type-filter').value;
                const destinationFilter = document.getElementById('destination-filter').value;
                const priceMax = document.getElementById('price-max').value;

                activeFilters = {};
                
                filteredOffers = offers.filter(offer => {
                    if (discountFilter && offer.discount < parseInt(discountFilter)) return false;
                    if (typeFilter && offer.type !== typeFilter) return false;
                    if (destinationFilter && offer.destination !== destinationFilter) return false;
                    if (priceMax && (offer.originalPrice * (1 - offer.discount / 100)) > parseInt(priceMax)) return false;
                    return true;
                });

                // Actualizar filtros activos
                if (discountFilter) activeFilters.discount = `${discountFilter}% OFF mínimo`;
                if (typeFilter) activeFilters.type = typeFilter.charAt(0).toUpperCase() + typeFilter.slice(1);
                if (destinationFilter) activeFilters.destination = destinationFilter.charAt(0).toUpperCase() + destinationFilter.slice(1);
                if (priceMax) activeFilters.price = `Hasta $${priceMax}`;

                updateActiveFilters();
                sortOffers();
                hideLoading();
            }, 1000);
        }

        function clearFilters() {
            document.getElementById('discount-filter').value = '';
            document.getElementById('type-filter').value = '';
            document.getElementById('destination-filter').value = '';
            document.getElementById('price-max').value = '';
            
            activeFilters = {};
            filteredOffers = [...offers];
            updateActiveFilters();
            renderOffers(filteredOffers);
        }

        function updateActiveFilters() {
            const container = document.getElementById('active-filters');
            const filterKeys = Object.keys(activeFilters);
            
            if (filterKeys.length === 0) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = filterKeys.map(key => `
                <div class="filter-chip">
                    ${activeFilters[key]}
                    <button onclick="removeFilter('${key}')">×</button>
                </div>
            `).join('');
        }

        function removeFilter(filterKey) {
            delete activeFilters[filterKey];
            
            // Limpiar el filtro correspondiente en el formulario
            switch(filterKey) {
                case 'discount':
                    document.getElementById('discount-filter').value = '';
                    break;
                case 'type':
                    document.getElementById('type-filter').value = '';
                    break;
                case 'destination':
                    document.getElementById('destination-filter').value = '';
                    break;
                case 'price':
                    document.getElementById('price-max').value = '';
                    break;
            }
            
            applyFilters();
        }

        function sortOffers() {
            const sortBy = document.getElementById('sort-by').value;
            
            filteredOffers.sort((a, b) => {
                switch(sortBy) {
                    case 'discount':
                        return b.discount - a.discount;
                    case 'price-low':
                        const priceA = a.originalPrice * (1 - a.discount / 100);
                        const priceB = b.originalPrice * (1 - b.discount / 100);
                        return priceA - priceB;
                    case 'price-high':
                        const priceA2 = a.originalPrice * (1 - a.discount / 100);
                        const priceB2 = b.originalPrice * (1 - b.discount / 100);
                        return priceB2 - priceA2;
                    case 'duration':
                        // Assuming duration is "X días" or "X horas"
                        const durationA = parseInt(a.duration);
                        const durationB = parseInt(b.duration);
                        return durationA - durationB;
                    default:
                        return 0;
                }
            });
            
            renderOffers(filteredOffers);
        }

        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('offers-grid').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('offers-grid').style.display = 'grid';
        }

        function bookOffer(offerId) {
            const offer = offers.find(o => o.id === offerId);
            const currentPrice = Math.round(offer.originalPrice * (1 - offer.discount / 100));
            alert(`¡Excelente elección! 🎉\n\nEstás reservando: ${offer.title}\nDescuento: ${offer.discount}% OFF\nPrecio final: $${currentPrice}\n\nTe contactaremos pronto para confirmar tu reserva.`);
        }

        function showDetails(offerId) {
            const offer = offers.find(o => o.id === offerId);
            const currentPrice = Math.round(offer.originalPrice * (1 - offer.discount / 100));
            const savings = offer.originalPrice - currentPrice;
            
            alert(`📋 DETALLES DE LA OFERTA\n\n🎯 ${offer.title}\n📍 Destino: ${offer.destination.charAt(0).toUpperCase() + offer.destination.slice(1)}\n⏱️ Duración: ${offer.duration}\n📦 Incluye: ${offer.includes}\n\n💰 PRECIOS:\n• Precio original: $${offer.originalPrice}\n• Descuento: ${offer.discount}% OFF\n• Precio final: $${currentPrice}\n• Ahorras: $${Math.round(savings)}\n\n📞 Para más información, contacta a nuestro equipo de ventas.`);
        }

        /* --- Favorites Functions --- */
        function toggleFavorite(offerId) {
            const index = favorites.indexOf(offerId);
            if (index > -1) {
                favorites.splice(index, 1); // Remove from favorites
            } else {
                favorites.push(offerId); // Add to favorites
            }
            localStorage.setItem('favorites', JSON.stringify(favorites));
            renderOffers(filteredOffers); // Re-render to update heart icon
            updateCounts();
        }

        function openFavoritesModal() {
            const modal = document.getElementById('favorites-modal');
            modal.style.display = 'flex'; // Use flex to center the modal
            renderFavoritesItems();
        }

        function renderFavoritesItems() {
            const container = document.getElementById('favorites-items-container');
            if (favorites.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: var(--color-gray);">No tienes ofertas en favoritos.</p>';
                return;
            }

            container.innerHTML = favorites.map(offerId => {
                const offer = offers.find(o => o.id === offerId);
                if (!offer) return ''; // Should not happen
                
                const currentPrice = Math.round(offer.originalPrice * (1 - offer.discount / 100));
                return `
                    <div class="modal-item">
                        <img src="${offer.image}" alt="${offer.title}">
                        <div class="modal-item-info">
                            <h4>${offer.title}</h4>
                            <p>$${currentPrice} <span style="text-decoration: line-through; color: var(--color-gray);">$${offer.originalPrice}</span></p>
                        </div>
                        <div class="modal-item-actions">
                            <button class="remove-btn" onclick="removeFromFavorites(${offer.id})"><i class="fas fa-trash-alt"></i> Eliminar</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function removeFromFavorites(offerId) {
            favorites = favorites.filter(id => id !== offerId);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            renderFavoritesItems(); // Re-render the modal
            renderOffers(filteredOffers); // Re-render main page to update button state
            updateCounts();
        }

        function clearFavorites() {
            if (confirm('¿Estás seguro de que quieres vaciar todos tus favoritos?')) {
                favorites = [];
                localStorage.setItem('favorites', JSON.stringify(favorites));
                renderFavoritesItems();
                renderOffers(filteredOffers);
                updateCounts();
            }
        }


        /* --- Cart Functions (Main Modal) --- */
        function addToCart(offerId) {
            const existingItemIndex = cart.findIndex(item => item.id === offerId);
            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += 1;
            } else {
                cart.push({ id: offerId, quantity: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            renderOffers(filteredOffers); // Re-render to update button state
            updateCounts(); // Update both header count and mini-cart
        }

        function openCartModal() {
            const modal = document.getElementById('cart-modal');
            modal.style.display = 'flex'; // Use flex to center the modal
            renderCartItems();
            document.getElementById('cart-dropdown-menu').style.display = 'none'; // Close mini-cart if open
        }

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const cartTotalElement = document.getElementById('cart-total');
            let total = 0;

            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: var(--color-gray);">Tu carrito está vacío.</p>';
                cartTotalElement.textContent = 'Total: $0';
                return;
            }

            container.innerHTML = cart.map(cartItem => {
                const offer = offers.find(o => o.id === cartItem.id);
                if (!offer) return ''; // Should not happen

                const itemPrice = Math.round(offer.originalPrice * (1 - offer.discount / 100));
                const subtotal = itemPrice * cartItem.quantity;
                total += subtotal;

                return `
                    <div class="modal-item">
                        <img src="${offer.image}" alt="${offer.title}">
                        <div class="modal-item-info">
                            <h4>${offer.title}</h4>
                            <p>$${itemPrice} c/u</p>
                            <p>Subtotal: $${subtotal}</p>
                        </div>
                        <div class="modal-item-actions">
                            <div class="quantity-control">
                                <button onclick="decreaseQuantity(${offer.id})">-</button>
                                <span>${cartItem.quantity}</span>
                                <button onclick="increaseQuantity(${offer.id})">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeFromCart(${offer.id})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `;
            }).join('');
            cartTotalElement.textContent = `Total: $${total}`;
        }

        function removeFromCart(offerId) {
            cart = cart.filter(item => item.id !== offerId);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartItems(); // Re-render the modal
            renderOffers(filteredOffers); // Re-render main page to update button state
            updateCounts();
        }

        function increaseQuantity(offerId) {
            const item = cart.find(item => item.id === offerId);
            if (item) {
                item.quantity++;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartItems();
                updateCounts();
            }
        }

        function decreaseQuantity(offerId) {
            const itemIndex = cart.findIndex(item => item.id === offerId);
            if (itemIndex > -1) {
                if (cart[itemIndex].quantity > 1) {
                    cart[itemIndex].quantity--;
                } else {
                    cart.splice(itemIndex, 1); // Remove if quantity is 1 and decreased
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartItems();
                renderOffers(filteredOffers); // Re-render main page if item removed
                updateCounts();
            }
        }

        function clearCart() {
            if (confirm('¿Estás seguro de que quieres vaciar tu carrito de compras?')) {
                cart = [];
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartItems();
                renderOffers(filteredOffers);
                updateCounts();
            }
        }
        /* --- End Cart Functions (Main Modal) --- */

        /* --- Cart Dropdown Functions (Mini Cart) --- */
        function toggleCartDropdown() {
            const cartDropdown = document.getElementById('cart-dropdown-menu');
            if (cartDropdown.style.display === 'block') {
                cartDropdown.style.display = 'none';
            } else {
                cartDropdown.style.display = 'block';
                renderCartDropdownItems();
            }
        }

        function renderCartDropdownItems() {
            const container = document.getElementById('cart-items-mini');
            const subtotalElement = document.getElementById('cart-subtotal-mini');
            let subtotal = 0;

            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: var(--color-gray); font-size: 0.9rem;">Tu carrito está vacío.</p>';
                subtotalElement.textContent = 'Subtotal: $0';
                return;
            }

            container.innerHTML = cart.map(cartItem => {
                const offer = offers.find(o => o.id === cartItem.id);
                if (!offer) return '';

                const itemPrice = Math.round(offer.originalPrice * (1 - offer.discount / 100));
                subtotal += itemPrice * cartItem.quantity;

                return `
                    <div class="cart-item-mini">
                        <img src="${offer.image}" alt="${offer.title}">
                        <div class="cart-item-mini-info">
                            <p>${offer.title}</p>
                            <span>$${itemPrice} x ${cartItem.quantity} = $${itemPrice * cartItem.quantity}</span>
                        </div>
                        <div class="cart-item-mini-actions">
                            <button onclick="decreaseQuantityDropdown(${offer.id})">-</button>
                            <span>${cartItem.quantity}</span>
                            <button onclick="increaseQuantityDropdown(${offer.id})">+</button>
                            <button class="remove-btn-mini" onclick="removeFromCartDropdown(${offer.id})"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                `;
            }).join('');
            subtotalElement.textContent = `Subtotal: $${subtotal}`;
        }

        function removeFromCartDropdown(offerId) {
            cart = cart.filter(item => item.id !== offerId);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartDropdownItems();
            renderOffers(filteredOffers);
            updateCounts();
        }

        function increaseQuantityDropdown(offerId) {
            const item = cart.find(item => item.id === offerId);
            if (item) {
                item.quantity++;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartDropdownItems();
                updateCounts();
            }
        }

        function decreaseQuantityDropdown(offerId) {
            const itemIndex = cart.findIndex(item => item.id === offerId);
            if (itemIndex > -1) {
                if (cart[itemIndex].quantity > 1) {
                    cart[itemIndex].quantity--;
                } else {
                    cart.splice(itemIndex, 1);
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartDropdownItems();
                renderOffers(filteredOffers);
                updateCounts();
            }
        }

        function clearCartDropdown() {
            if (confirm('¿Estás seguro de que quieres vaciar tu carrito?')) {
                cart = [];
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartDropdownItems();
                renderOffers(filteredOffers);
                updateCounts();
            }
        }

        function continueShopping() {
            document.getElementById('cart-dropdown-menu').style.display = 'none';
        }

        function goToCheckout() {
            if (cart.length > 0) {
                alert('Redirigiendo a la página de pago con los productos del carrito...');
                document.getElementById('cart-dropdown-menu').style.display = 'none';
                // En una aplicación real, aquí iría la lógica para redirigir
                // window.location.href = '/checkout.html'; 
            } else {
                alert('Tu carrito está vacío. ¡Añade algunos productos antes de ir al checkout!');
            }
        }
        /* --- End Cart Dropdown Functions --- */


        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Header dropdown and hamburger menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profilePictureTrigger = document.getElementById('profile-picture-trigger');
            const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('navMenu');
            const favoritesLink = document.getElementById('favorites-link');
            const cartLink = document.getElementById('cart-link'); // Parent for both icon and dropdown
            const cartDropdownMenu = document.getElementById('cart-dropdown-menu');

            profilePictureTrigger.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent click from closing immediately
                profileDropdownMenu.style.display = profileDropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });

            favoritesLink.addEventListener('click', openFavoritesModal);
            
            // Toggle cart dropdown on click
            cartLink.addEventListener('click', function(event) {
                // Check if the click originated from the icon, not the dropdown itself
                if (event.target.closest('#cart-link') === cartLink && !cartDropdownMenu.contains(event.target)) {
                     toggleCartDropdown();
                }
            });

            // Close dropdowns and modals if clicked outside
            document.addEventListener('click', function(event) {
                // Close profile dropdown
                if (!profilePictureTrigger.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
                    profileDropdownMenu.style.display = 'none';
                }
                
                // Close cart dropdown
                if (!cartLink.contains(event.target) && !cartDropdownMenu.contains(event.target)) {
                    cartDropdownMenu.style.display = 'none';
                }

                // Close main modals if click outside content
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (modal.style.display === 'flex' && !modal.querySelector('.modal-content').contains(event.target)) {
                        modal.style.display = 'none';
                    }
                });
            });

            // Initialize the page
            renderOffers(offers);
            updateCounts(); // Initial update of counts

            // Add animation effects on load
            setTimeout(() => {
                const cards = document.querySelectorAll('.offer-card');
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = 'all 0.5s ease';
                        
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    }, index * 100);
                });
            }, 100);
        });

        // Función para buscar ofertas en tiempo real
        function searchOffers(query) {
            if (!query) {
                filteredOffers = [...offers];
            } else {
                filteredOffers = offers.filter(offer => 
                    offer.title.toLowerCase().includes(query.toLowerCase()) ||
                    offer.description.toLowerCase().includes(query.toLowerCase()) ||
                    offer.destination.toLowerCase().includes(query.toLowerCase())
                );
            }
            renderOffers(filteredOffers);
        }

        // Simular actualizaciones en tiempo real de ofertas
        setInterval(() => {
            const randomOffer = offers[Math.floor(Math.random() * offers.length)];
            console.log(`🔥 Oferta destacada: ${randomOffer.title} con ${randomOffer.discount}% OFF`);
        }, 30000); // Cada 30 segundos
    </script>
</body>
</html>