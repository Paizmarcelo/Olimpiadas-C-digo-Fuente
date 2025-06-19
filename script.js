document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('login-btn');
    const registerBtn = document.getElementById('register-btn');
    const body = document.body;
    let flightDataInterval;
    let animationStartTime;
    let currentPhase = 'idle'; // idle, taxi, takeoff-roll, rotate, climb, cruise

    // Elementos del HUD y Entorno 3D
    const cockpitView = document.getElementById('cockpit-view');
    const latValue = document.getElementById('lat-value');
    const lonValue = document.getElementById('lon-value');
    const altValue = document.getElementById('alt-value');
    const vsiValue = document.getElementById('vsi-value');
    const attitudeHorizon = document.querySelector('.attitude-horizon');
    const speedometerNeedle = document.querySelector('.speedometer-needle');
    const speedValueDisplay = document.getElementById('speed-value');
    const radarSweep = document.querySelector('.radar-sweep');
    const radarBlips = document.querySelectorAll('.radar-blip');
    const gForceBar = document.querySelector('.g-force-bar');
    const headingDial = document.querySelector('.heading-dial');
    const hudGlitchOverlay = document.querySelector('.hud-glitch-overlay');
    const hudNotifications = document.getElementById('hud-notifications');

    // Elementos del entorno 3D
    const runwayCenterline = document.querySelector('.runway-centerline');
    const heatHaze = document.querySelector('.heat-haze');
    const distantTerrain = document.querySelector('.distant-terrain');
    const skyView = document.querySelector('.sky-view');
    const cloudsLayers = document.querySelectorAll('.clouds-layer'); // Para animar todas las capas

    // Elementos de audio
    const engineSound = document.getElementById('engine-sound');
    const v1Call = document.getElementById('v1-call');
    const rotateCall = document.getElementById('rotate-call');
    const positiveRateCall = document.getElementById('positive-rate-call');
    const windSound = document.getElementById('wind-sound');

    // Asignar rutas de audio (¡Asegúrate de que estos archivos existan en tu carpeta 'audio'!)
    engineSound.src = 'audio/engine_loop.mp3';
    v1Call.src = 'audio/v1.mp3';
    rotateCall.src = 'audio/rotate.mp3';
    positiveRateCall.src = 'audio/positive_rate.mp3';
    windSound.src = 'audio/wind_loop.mp3';

    // Datos de simulación del avión (valores que serán animados)
    let flightData = {
        latitude: 34.0522,
        longitude: -118.2437,
        altitude: 0, // Pies
        speed: 0, // Nudos
        pitch: 0, // Grados
        roll: 0,  // Grados
        heading: 90, // Grados (Este)
        verticalSpeed: 0, // Pies/min
        gForce: 0,
    };

    // Configuración de fases de vuelo (duraciones en milisegundos y targets de datos)
    const flightPhases = {
        idle: { duration: 0 },
        taxi: { duration: 3000, speedTarget: 30, gForceTarget: 0.1, pitchTarget: 0, rollTarget: 0, verticalSpeedTarget: 0, headingTarget: 90 },
        takeoff_roll: { duration: 7000, speedTarget: 180, gForceTarget: 0.9, pitchTarget: 0, rollTarget: 0, verticalSpeedTarget: 0, headingTarget: 90 },
        rotate: { duration: 2000, speedTarget: 200, gForceTarget: 1.2, pitchTarget: 10, rollTarget: 0, verticalSpeedTarget: 2500, headingTarget: 90 },
        climb: { duration: 5000, speedTarget: 250, gForceTarget: 0.5, pitchTarget: 7, rollTarget: 0, verticalSpeedTarget: 1800, headingTarget: 95 }, // Leve cambio de rumbo
        cruise: { duration: Infinity, speedTarget: 300, gForceTarget: 0.2, pitchTarget: 3, rollTarget: 0, verticalSpeedTarget: 0, headingTarget: 100 }
    };

    // Duración total de la animación CSS de despegue (debe coincidir con style.css)
    const totalTakeoffAnimationDuration = 15000; // 15 segundos

    loginBtn.addEventListener('click', () => {
        if (body.classList.contains('takeoff-active')) return;

        // Resetear algunos valores iniciales para asegurar una reproducción limpia
        flightData.altitude = 0;
        flightData.speed = 0;
        flightData.pitch = 0;
        flightData.roll = 0;
        flightData.heading = 90;
        flightData.verticalSpeed = 0;
        flightData.gForce = 0;
        updateHUD(true); // Actualiza el HUD a valores iniciales de forma instantánea

        body.classList.add('takeoff-active');
        loginBtn.disabled = true;
        loginBtn.style.cursor = 'wait';
        loginBtn.textContent = 'INICIANDO SECUENCIA...';

        animationStartTime = performance.now();
        startTakeoffSequence();
    });

    function startTakeoffSequence() {
        showNotification('SISTEMAS INICIANDO...', 2000);

        // Fase 1: Taxi (3 segundos)
        setTimeout(() => {
            currentPhase = 'taxi';
            showNotification('AUTORIZADO PARA RODAR. VELOCIDAD DE TAXI.', 3000);
            engineSound.play();
            engineSound.volume = 0.3;
            animateFlightData(flightPhases.taxi.duration, flightPhases.taxi);
        }, 1000);

        // Fase 2: Takeoff Roll (7 segundos)
        setTimeout(() => {
            currentPhase = 'takeoff_roll';
            showNotification('DESPEGUE AUTORIZADO. ABRE MOTORES.', 3000);
            engineSound.volume = 0.8;
            heatHaze.style.opacity = 1; // Activar efecto de calor
            hudGlitchOverlay.style.animationPlayState = 'running'; // Activar glitch
            hudGlitchOverlay.style.opacity = 0.3; // Hacerlo más visible al inicio

            animateFlightData(flightPhases.takeoff_roll.duration, flightPhases.takeoff_roll);

            setTimeout(() => {
                v1Call.play();
                showNotification('V1 - VELOCIDAD DE DECISIÓN', 2000);
            }, flightPhases.takeoff_roll.duration * 0.7);

            setTimeout(() => {
                rotateCall.play();
                showNotification('ROTAR - ELEVAR NARIZ', 2000);
            }, flightPhases.takeoff_roll.duration * 0.85);

        }, 1000 + flightPhases.taxi.duration); // Después del taxi

        // Fase 3: Rotate / Initial Climb (2 segundos)
        setTimeout(() => {
            currentPhase = 'rotate';
            engineSound.volume = 1.0;
            windSound.play();
            windSound.volume = 0.5;
            animateFlightData(flightPhases.rotate.duration, flightPhases.rotate);
            
            setTimeout(() => {
                positiveRateCall.play();
                showNotification('ASCENSO POSITIVO. TREN ARRIBA.', 2000);
            }, flightPhases.rotate.duration * 0.5);

        }, 1000 + flightPhases.taxi.duration + flightPhases.takeoff_roll.duration);

        // Fase 4: Climb (5 segundos)
        setTimeout(() => {
            currentPhase = 'climb';
            showNotification('ASCENSO INICIAL A ALTITUD DE CRUCERO.', 3000);
            engineSound.volume = 0.7;
            windSound.volume = 0.7;
            hudGlitchOverlay.style.animationPlayState = 'paused'; // Desactivar glitch
            hudGlitchOverlay.style.opacity = 0;
            animateFlightData(flightPhases.climb.duration, flightPhases.climb);

            // Transición del cielo a un tono más oscuro o nocturno
            skyView.style.backgroundImage = 'linear-gradient(to bottom, #00001a, #1a2a45 70%, #3a6a8a)';
            distantTerrain.style.filter = 'brightness(0.3)';
            cloudsLayers.forEach(cloud => {
                cloud.style.filter = 'brightness(0.5)'; // Oscurecer nubes
            });

        }, 1000 + flightPhases.taxi.duration + flightPhases.takeoff_roll.duration + flightPhases.rotate.duration);

        // Fase 5: Cruise (Después del ascenso)
        setTimeout(() => {
            currentPhase = 'cruise';
            showNotification('VUELO DE CRUCERO. DISFRUTE SU VIAJE.', 3000);
            engineSound.volume = 0.5;
            windSound.volume = 0.8;
            // Aquí, la API se encargará de los datos de pitch, roll y heading.
            // Para velocidad, altitud, VSI, G-Force, podemos dejar los últimos valores animados
            // o permitir que la API los controle si son más "aleatorios" para el crucero.

            // Mostrar el overlay de login después de un tiempo en crucero
            setTimeout(() => {
                document.getElementById('login-overlay').classList.add('visible');
                loginBtn.textContent = 'INICIAR VUELO';
                loginBtn.disabled = false;
                loginBtn.style.cursor = 'pointer';
            }, 3000); // 3 segundos después de entrar en fase de crucero

        }, totalTakeoffAnimationDuration); // Al final de todas las animaciones CSS
    }

    // Función para mostrar notificaciones en el HUD
    function showNotification(message, duration) {
        const notification = document.createElement('div');
        notification.classList.add('hud-notification');
        notification.textContent = message;
        hudNotifications.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, duration);
    }

    // Función para animar los datos de vuelo de forma suave
    function animateFlightData(duration, targetData) {
        const startTimestamp = performance.now();
        
        // Captura los valores iniciales de flightData al comienzo de CADA FASE
        const initialSpeed = flightData.speed;
        const initialPitch = flightData.pitch;
        const initialRoll = flightData.roll;
        const initialVerticalSpeed = flightData.verticalSpeed;
        const initialGForce = flightData.gForce;
        const initialAltitude = flightData.altitude;
        const initialHeading = flightData.heading;

        const updateFrame = (currentTime) => {
            const elapsed = currentTime - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);
            const easing = easeOutCubic(progress);

            // Interpolación de los valores
            flightData.speed = initialSpeed + (targetData.speedTarget - initialSpeed) * easing;
            flightData.pitch = initialPitch + (targetData.pitchTarget - initialPitch) * easing;
            flightData.roll = initialRoll + (targetData.rollTarget - initialRoll) * easing;
            flightData.verticalSpeed = initialVerticalSpeed + (targetData.verticalSpeedTarget - initialVerticalSpeed) * easing;
            flightData.gForce = initialGForce + (targetData.gForceTarget - initialGForce) * easing;
            
            // Altitud: se calcula en función de la velocidad vertical y el tiempo transcurrido
            flightData.altitude = initialAltitude + (flightData.verticalSpeed / 60) * (elapsed / 1000);

            // Rumbo: puede cambiar para simular virajes
            flightData.heading = initialHeading + (targetData.headingTarget - initialHeading) * easing;

            // Ajustes adicionales para la vista de cabina (sutiles movimientos)
            const cockpitPitch = 5 - (flightData.pitch * 0.5); // Invertir y escalar ligeramente
            const cockpitRoll = flightData.roll * 0.1; // Muy sutil
            cockpitView.style.transform = `rotateX(${cockpitPitch}deg) rotateZ(${cockpitRoll}deg) translateZ(0px)`;


            updateHUD();

            if (progress < 1) {
                requestAnimationFrame(updateFrame);
            }
            // Cuando la animación de fase termina, los valores de flightData se mantienen
            // y la siguiente llamada a animateFlightData usará esos valores como "initial".
        };

        requestAnimationFrame(updateFrame);
    }

    // Función principal para actualizar el HUD
    function updateHUD(instant = false) {
        // Fetch de coordenadas de la API
        fetch('http://127.0.0.1:5000/get_coordinates')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Actualizar latitud y longitud siempre desde la API
                flightData.latitude = data.latitude;
                flightData.longitude = data.longitude;
                latValue.textContent = `LAT: ${flightData.latitude.toFixed(4)}° N`;
                lonValue.textContent = `LON: ${flightData.longitude.toFixed(4)}° W`;

                // Durante las fases de despegue, la velocidad y altitud se controlan con la animación local.
                // Una vez en "cruise", podríamos usar los valores de la API para simular variaciones reales.
                if (currentPhase === 'cruise') {
                    flightData.pitch = data.pitch;
                    flightData.roll = data.roll;
                    flightData.heading = data.heading;
                    flightData.speed = data.speed; // Si la API simula velocidad de crucero
                    flightData.altitude = data.altitude; // Si la API simula altitud de crucero
                    flightData.verticalSpeed = data.vertical_speed;
                    flightData.gForce = data.g_force;
                }

                // Actualizar Altímetro y VSI
                altValue.textContent = `ALT: ${Math.max(0, Math.round(flightData.altitude))} FT`;
                vsiValue.textContent = `VSI: ${Math.round(flightData.verticalSpeed)} FT/MIN`;

                // Actualizar Horizonte Artificial (Attitude Indicator)
                const horizonPitchOffset = flightData.pitch * -2; // Ajustar sensibilidad
                const horizonRollRotation = flightData.roll;
                attitudeHorizon.style.transform = `translateY(${horizonPitchOffset}%) rotateZ(${horizonRollRotation}deg)`;
                
                // Actualizar Velocímetro
                const maxSpeed = 300;
                const minAngle = -135; // Ángulo para 0 KNOTS
                const maxAngle = 90;  // Ángulo para 300 KNOTS
                const speedRange = maxSpeed;
                const angleRange = maxAngle - minAngle;
                const normalizedSpeed = Math.min(Math.max(flightData.speed, 0), maxSpeed);
                const speedRotation = minAngle + (normalizedSpeed / speedRange) * angleRange;
                speedometerNeedle.style.transform = `translateX(-50%) rotate(${speedRotation}deg)`;
                speedValueDisplay.textContent = `${Math.round(flightData.speed)} KNOTS`;

                // Actualizar G-Force Bar
                const normalizedGForce = Math.min(Math.max(flightData.gForce / 1.5, 0), 1);
                gForceBar.style.transform = `scaleX(${normalizedGForce})`;

                // Actualizar Heading Indicator
                const headingRotation = -flightData.heading; // Invertir para que el dial gire y la aguja apunte
                headingDial.style.transform = `rotate(${headingRotation}deg)`;
            })
            .catch(error => {
                console.error('Error fetching flight data:', error);
                // Si la API falla, muestra "Comms Offline"
                latValue.textContent = "LAT: Comms Offline";
                lonValue.textContent = "LON: Comms Offline";
                altValue.textContent = "ALT: Comms Offline";
                vsiValue.textContent = "VSI: Comms Offline";
                // No detener el intervalo, solo mostrar el error.
            });
    }

    // Funciones de easing
    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    // Iniciar la actualización de datos de vuelo cada 100ms (para el HUD)
    // Esto se ejecutará incluso si no hay animación de despegue activa.
    flightDataInterval = setInterval(updateHUD, 100);

    // Asegurarse de que el HUD se inicialice al cargar la página
    updateHUD(true); // Pasar true para que sea una actualización instantánea al inicio

    // Manejar el formulario de login (solo para mostrar el overlay, sin lógica de autenticación)
    const loginForm = document.getElementById('login-form');
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Intentando acceder... (Simulación)');
        // Aquí iría tu lógica de autenticación real
        document.getElementById('login-overlay').classList.remove('visible'); // Ocultar el overlay
    });

    const showRegisterLink = document.getElementById('show-register');
    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Redirigiendo a página de registro... (Simulación)');
        // Aquí iría la redirección a una página de registro
    });
});