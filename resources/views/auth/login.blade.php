<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        * {
            box-sizing: border-box;
        }

        body {
            /* Se remueve el background opaco fijo para que el canvas se muestre */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        h1 {
            font-weight: bold;
            margin: 0;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        span {
            font-size: 12px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #00b4d8;
            background-color: #00b4d8;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            border-radius: 5px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            position: relative;
            overflow: hidden;
            width: 850px;
            max-width: 100%;
            min-height: 600px;
            z-index: 10;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        /* LOGICA DE SLIDER */
        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {

            0%,
            49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%,
            100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: #00b4d8;
            background: linear-gradient(to right, #90e0ef, #0077b6);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0%);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 12px;
            width: 100%;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        #canvas1 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: linear-gradient(135deg, #f6f5f7 0%, #eef7fb 100%);
            pointer-events: none;
        }

        #page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(to right, #90e0ef, #0077b6);
            z-index: 9999;
            transform: translateX(0);
            /* Empieza cubriendo la pantalla */
            transition: transform 0.6s ease-in-out;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- Cortina de Transición a Pantalla Completa -->
    <div id="page-transition"></div>

    <div class="container" id="container">

        <div class="form-container sign-up-container">
            {{-- El formulario de registro fue movido a /register para mejor experiencia --}}
            <div
                style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; padding: 40px; text-align:center;">
                <i class="fas fa-clinic-medical" style="font-size: 3rem; color: #00b4d8; margin-bottom: 20px;"></i>
                <h2 style="font-size: 1.3rem; color: #333; font-weight: 800; margin-bottom: 8px;">Registra tu Clínica
                </h2>
                <p style="font-size: 13px; color: #888; margin-bottom: 24px; line-height: 1.6;">Crea tu cuenta de doctor
                    con todos los datos de tu clínica dental.</p>
                <a href="{{ route('register') }}"
                    style="background: linear-gradient(90deg, #00b4d8, #0077b6); color: white; padding: 12px 36px; border-radius: 25px; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,119,182,0.3);">
                    <i class="fas fa-user-plus"></i> Registrarse ahora
                </a>
            </div>
        </div>


        <div class="form-container sign-in-container">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <h1>Iniciar Sesión</h1>
                <div class="social-container" style="margin: 20px 0;">
                    <a href="#"
                        style="border: 1px solid #ddd; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; height: 40px; width: 40px; color: #333;"><i
                            class="fab fa-google"></i></a>
                </div>
                <span>usa tu cuenta registrada</span>

                @if(session('success'))
                    <div class="alert alert-success" style="display:flex; flex-direction:column; align-items:center;">
                        {{ session('success') }}
                        <button type="button" class="ghost" onclick="document.getElementById('signIn').click();"
                            style="margin-top: 10px; background-color: #28a745; border-color: #28a745; color: white;">Ir a
                            Inicio de Sesión</button>
                    </div>
                @endif
                @if(!session('show_register') && $errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Contraseña" required />
                <a href="/olvide-password">¿Olvidaste tu contraseña?</a>
                <button type="submit">Ingresar</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Para mantenerte conectado con tu clínica, inicia sesión aquí.</p>
                    <button class="ghost" id="signIn">Ingresar</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>¡Hola!</h1>
                    <p>¿Aún no tienes cuenta? Registra tu clínica dental gratis.</p>
                    <a href="{{ route('register') }}" class="ghost"
                        style="display:inline-block; padding: 12px 36px; border-radius: 25px; text-decoration:none; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; border: 1px solid white; color:white; margin-top:4px;">Registrarse</a>
                </div>
            </div>
        </div>
    </div>

    <canvas id="canvas1"></canvas>

    <script>
        // Lógica del Slider
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        if (signUpButton) {
            signUpButton.addEventListener('click', () => {
                container.classList.add("right-panel-active");
            });
        }

        if (signInButton) {
            signInButton.addEventListener('click', () => {
                container.classList.remove("right-panel-active");
            });
        }

        // Si hay errores de registro, abrir automáticamente el panel de registro
        @if(session('show_register') && $errors->any())
            container.classList.add("right-panel-active");
        @endif

        // Animación de Dientes (Tu código original)
        const canvas = document.getElementById("canvas1");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        let particlesArray;

        class Tooth {
            constructor(x, y, directionX, directionY, size, color) {
                this.x = x; this.y = y; this.directionX = directionX; this.directionY = directionY;
                this.size = size; this.color = color;
                this.spinSpeed = (Math.random() - 0.5) * 0.02; this.angle = Math.random() * 360;
            }
            draw() {
                ctx.save(); ctx.translate(this.x, this.y); ctx.rotate(this.angle); ctx.scale(this.size / 10, this.size / 10);
                ctx.beginPath(); ctx.fillStyle = this.color;
                ctx.moveTo(-10, -10); ctx.quadraticCurveTo(-5, -15, 0, -10); ctx.quadraticCurveTo(5, -15, 10, -10);
                ctx.quadraticCurveTo(12, 0, 10, 10); ctx.lineTo(5, 20); ctx.lineTo(0, 10); ctx.lineTo(-5, 20); ctx.lineTo(-10, 10); ctx.quadraticCurveTo(-12, 0, -10, -10);
                ctx.closePath(); ctx.fill(); ctx.restore();
            }
            update() {
                if (this.x > canvas.width || this.x < 0) this.directionX = -this.directionX;
                if (this.y > canvas.height || this.y < 0) this.directionY = -this.directionY;
                this.x += this.directionX; this.y += this.directionY; this.angle += this.spinSpeed; this.draw();
            }
        }
        function init() {
            particlesArray = [];
            for (let i = 0; i < 20; i++) {
                let size = (Math.random() * 5) + 3;
                let x = Math.random() * innerWidth; let y = Math.random() * innerHeight;
                let color = 'rgba(0, 180, 216, 0.3)';
                particlesArray.push(new Tooth(x, y, (Math.random() - 0.5), (Math.random() - 0.5), size, color));
            }
        }
        function animate() {
            requestAnimationFrame(animate); ctx.clearRect(0, 0, innerWidth, innerHeight);
            particlesArray.forEach(p => p.update());
        }
        init(); animate();

        // Lógica de transición animada de página completa (de Login a Register)
        document.addEventListener('DOMContentLoaded', () => {
            const transitionEl = document.getElementById('page-transition');
            if (transitionEl) {
                // Al inicio, la cortina está cubriendo (0). La deslizamos hacia la derecha (100%) para revelar el Login
                setTimeout(() => {
                    transitionEl.style.transform = 'translateX(100%)';
                }, 50);
            }

            // Seleccionar todos los enlaces que van a /register
            const registerLinks = document.querySelectorAll('a[href="{{ route("register") }}"]');
            registerLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = link.href;
                    if (transitionEl) {
                        transitionEl.style.transition = 'transform 0.6s ease-in-out';
                        transitionEl.style.transform = 'translateX(0)'; // La traemos de regreso a cubrir
                        setTimeout(() => {
                            window.location.href = href;
                        }, 550); // Redirigimos un pelín antes de que acabe para optimizar el flasheo blanco
                    } else {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</body>

</html>