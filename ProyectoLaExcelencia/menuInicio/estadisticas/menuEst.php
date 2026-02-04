<html>
    <head>
        <meta charset="utf-8">
        <title>Menú Estadísticas</title>
        <style>
        /* ========================================
        RESET Y CONFIGURACIÓN BASE
        ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ========================================
        BODY Y CONTENEDOR PRINCIPAL
        ======================================== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Efecto de partículas en el fondo */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 100px 100px, 150px 150px, 120px 120px;
            animation: particleFloat 20s infinite linear;
            pointer-events: none;
        }

        @keyframes particleFloat {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-100px);
            }
        }

        /* ========================================
        CONTENEDOR DE CONTENIDO
        ======================================== */
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 50px 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: slideUp 0.6s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========================================
        TÍTULOS
        ======================================== */
        h1 {
            color: #2c3e50;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        }

        h3 {
            color: #718096;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 40px;
            letter-spacing: 0.3px;
        }

        /* ========================================
        CONTENEDOR DE TARJETAS
        ======================================== */
        .cards-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 35px;
        }

        /* ========================================
        FORMULARIOS Y TARJETAS
        ======================================== */
        form {
            width: 100%;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            transition: all 0.3s ease;
        }

        /* Tarjeta de Compras - Color púrpura */
        .stat-card.compras::before {
            background: linear-gradient(180deg, #9f7aea 0%, #667eea 100%);
        }

        /* Tarjeta de Ventas - Color naranja */
        .stat-card.ventas::before {
            background: linear-gradient(180deg, #ed8936 0%, #dd6b20 100%);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover::before {
            width: 100%;
        }

        /* ========================================
        BOTONES DENTRO DE LAS TARJETAS
        ======================================== */
        input[type="submit"] {
            width: 100%;
            padding: 25px 30px;
            border: none;
            background: transparent;
            color: #2d3748;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        input[type="submit"]::after {
            content: '→';
            font-size: 24px;
            font-weight: 700;
            transition: all 0.3s ease;
            opacity: 0.6;
        }

        .stat-card:hover input[type="submit"] {
            color: white;
        }

        .stat-card:hover input[type="submit"]::after {
            transform: translateX(10px);
            opacity: 1;
        }

        /* Iconos específicos por tipo */
        input[type="submit"]::before {
            font-size: 32px;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        input[name="btnEV"]::before {
            content: '📦';
        }

        input[name="btnEC"]::before {
            content: '💰';
        }

        /* ========================================
        ENLACE VOLVER
        ======================================== */
        .btn-volver {
            display: inline-block;
            padding: 15px 40px;
            color: #667eea;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            background: white;
            border: 2px solid #667eea;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .btn-volver:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        /* ========================================
        DECORACIÓN ADICIONAL
        ======================================== */
        .decoration {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            opacity: 0.1;
            pointer-events: none;
        }

        .decoration-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .decoration-2 {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            bottom: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* ========================================
        DIVIDER DECORATIVO
        ======================================== */
        .divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 30px auto;
            border-radius: 2px;
        }

        /* ========================================
        RESPONSIVE
        ======================================== */
        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
                border-radius: 20px;
            }
            
            h1 {
                font-size: 32px;
            }
            
            h3 {
                font-size: 16px;
                margin-bottom: 30px;
            }
            
            input[type="submit"] {
                font-size: 18px;
                padding: 20px 25px;
            }
            
            input[type="submit"]::before {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            h3 {
                font-size: 14px;
            }
            
            input[type="submit"] {
                font-size: 16px;
                padding: 18px 20px;
                flex-direction: row;
            }
            
            .btn-volver {
                padding: 12px 30px;
                font-size: 14px;
            }
        }

        /* ========================================
        ANIMACIONES DE ENTRADA ESCALONADAS
        ======================================== */
        .stat-card:nth-child(1) {
            animation: slideInLeft 0.6s ease 0.2s both;
        }

        .stat-card:nth-child(2) {
            animation: slideInLeft 0.6s ease 0.4s both;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .btn-volver {
            animation: fadeIn 0.6s ease 0.6s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* ========================================
        EFECTOS ADICIONALES
        ======================================== */
        /* Efecto ripple al hacer click */
        .stat-card:active {
            transform: scale(0.98);
        }

        /* Mejora de accesibilidad */
        *:focus-visible {
            outline: 3px solid rgba(102, 126, 234, 0.5);
            outline-offset: 2px;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="decoration decoration-1"></div>
            <div class="decoration decoration-2"></div>
            
            <!-- Contenido principal -->
            <h1>Estadísticas</h1>
            <div class="divider"></div>
            <h3>Seleccione las estadísticas que desee visualizar</h3>
            
            <div class="cards-container">
                <div class="stat-card compras">
                    <form action="com/estCompras.php">
                        <input type="submit" name="btnEV" id="btnEV" value="Estadísticas de Compras">
                    </form>
                </div>
                
                <div class="stat-card ventas">
                    <form action="ven/estVentas.php">
                        <input type="submit" name="btnEC" id="btnEC" value="Estadísticas de Ventas">
                    </form>
                </div>
            </div>
            
            <a href="../menu.php" class="btn-volver">Volver al Menú</a>
        </div>
    </body>
</html>