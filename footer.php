  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>
  <body>
    
<style>
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
      </body>
      </html>