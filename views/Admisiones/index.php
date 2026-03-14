<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admisiones - Centro Educativo</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    
    <style>
        .body-admisiones {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .hero-admisiones {
            background-image: linear-gradient(rgba(10, 17, 40, 0.85), rgba(30, 43, 69, 0.85)), url('http://localhost/api_educativa/assets/img/fondo-escuela.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            padding: 100px 20px; 
            border-radius: 12px;
            max-width: 1200px;
            margin: 40px auto; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        
        .hero-admisiones h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: white;
        }
        .hero-admisiones p {
            color: #e0e0e0;
        }

        .tabs-admisiones {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .tab-button {
            padding: 12px 24px;
            border: 2px solid #1e2b45;
            background: white;
            color: #1e2b45;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: all 0.3s;
        }
        .tab-button.active {
            background: #1e2b45;
            color: white;
        }
        
        .tab-content {
            display: none;
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .tab-content.active {
            display: block;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 30px 0;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
        }

        /* ==========================================================
           TARJETAS DIFUMINADAS DE COSTOS
        ========================================================== */
        .card-bg {
            padding: 50px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            text-align: center;
            background-size: cover;
            background-position: center;
            color: white; /* Texto blanco para contrastar */
        }
        .card-bg h3 {
            color: white;
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        
        .img-admisiones {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .tab-content ul {
            list-style: none;
            padding-left: 0;
        }
        .tab-content ul li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        .tab-content ul li:before {
            content: "✓";
            color: #e67e22;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .avisos {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .btn-naranja {
            display: inline-block; 
            padding: 15px 40px; 
            background: #e67e22; 
            color: white; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: bold; 
            font-size: 1.2em;
            transition: 0.3s;
        }
        .btn-naranja:hover {
            background: #cf6d18;
        }

        @media (max-width: 768px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .avisos { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="body-admisiones">

    <main>
        <section class="hero-admisiones">
            <h1>Sobre nuestras Admisiones</h1>
            <p>Bienvenido a API Educativa</p>
        </section>

        <section class="tabs-admisiones">
            <button class="tab-button active" onclick="showTab('requisitos')">Requisitos de Admisión</button>
            <button class="tab-button" onclick="showTab('costos')">Costos y Aranceles</button>
        </section>

        <section id="requisitos" class="tab-content active">
            <h2 style="text-align: center; margin-bottom: 30px; color: #1e2b45;">Requisitos de Admisión</h2>
            
            <div class="grid-2">
                <div>
                    <h3 style="color: #1e2b45; margin-bottom: 20px;">📋 Documentos Necesarios</h3>
                    <ul>
                        <li>Acta de nacimiento (original y copia)</li>
                        <li>Certificado de estudios anteriores</li>
                        <li>Cédula de identidad del estudiante</li>
                        <li>2 fotos tamaño carnet</li>
                        <li>Constancia de salud</li>
                        <li>Carta de conducta</li>
                    </ul>
                    
                    <h3 style="color: #1e2b45; margin: 30px 0 20px;">🎯 Perfil del Estudiante</h3>
                    <ul>
                        <li>Edad mínima: 6 años</li>
                        <li>Buen rendimiento académico</li>
                        <li>Valores y disciplina</li>
                    </ul>
                </div>
                
                <div>
                    <img src="http://localhost/api_educativa/assets/img/requisitos.jpg" 
                         alt="Documentos" 
                         class="img-admisiones"
                         style="margin-bottom: 15px;"
                         onerror="this.src='https://via.placeholder.com/600x400/1e2b45/ffffff?text=Requisitos'">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <img src="http://localhost/api_educativa/assets/img/estudiante.jpg" 
                             alt="Estudiantes" 
                             class="img-admisiones"
                             onerror="this.src='https://via.placeholder.com/300x200/e67e22/ffffff?text=Estudiantes'">
                        <img src="http://localhost/api_educativa/assets/img/Ingles.jpg" 
                             alt="Clases" 
                             class="img-admisiones"
                             onerror="this.src='https://via.placeholder.com/300x200/1e2b45/ffffff?text=Clases'">
                    </div>
                </div>
            </div>
        </section>

        <section id="costos" class="tab-content">
            <h2 style="text-align: center; margin-bottom: 30px; color: #1e2b45;">Costos y Aranceles 2026</h2>
            
            <div class="grid-3">
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Matricula.jpg');">
                    <h3>Matrícula</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$5,000</p>
                    <p style="color: #e0e0e0;">Pago único anual</p>
                </div>
                
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Mensualidad.jpg');">
                    <h3>Mensualidad</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$2,500</p>
                    <p style="color: #e0e0e0;">10 pagos al año</p>
                </div>
                
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Programas.jpg');">
                    <h3>Programas Especiales</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$1,500</p>
                    <p style="color: #e0e0e0;">Robótica, Inglés, etc.</p>
                </div>
            </div>

            <img src="http://localhost/api_educativa/assets/img/Instalacion.jpg" 
                 alt="Instalaciones" 
                 class="img-admisiones"
                 style="width: 100%; height: 300px; object-fit: cover; margin: 30px 0;"
                 onerror="this.src='https://via.placeholder.com/1200x300/1e2b45/ffffff?text=Nuestras+Instalaciones'">
        </section>

        <section style="max-width: 1200px; margin: 0 auto; padding: 0 30px;">
            <h2 style="text-align: center; margin-bottom: 20px; color: #1e2b45;">Avisos Recientes</h2>
            <div class="avisos">
                <div>
                    <img src="http://localhost/api_educativa/assets/img/Avisos%20importantes.jpg" 
                         alt="Avisos" 
                         class="img-admisiones"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/400x300/e67e22/ffffff?text=Avisos'">
                </div>
                <div style="padding: 10px;">
                    <p><strong style="color: #e67e22;">📢 Matrícula 2026:</strong> Abierta hasta el 30 de Marzo.</p>
                    <p><strong style="color: #e67e22;">📅 Pruebas de Admisión:</strong> 15 y 16 de Abril.</p>
                    <p><strong style="color: #e67e22;">🏫 Reunión informativa:</strong> Para padres de nuevos estudiantes, 5 de Abril.</p>
                    <p><strong style="color: #e67e22;">❓ ¿Necesitas ayuda?</strong> Contáctanos al (809) 123-0000</p>
                </div>
            </div>
        </section>

        <div style="text-align: center; margin: 40px 0;">
            <a href="#" class="btn-naranja">Solicitar información</a>
        </div>
    </main>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            document.getElementById(tabId).classList.add('active');
            
            event.target.classList.add('active');
        }
    </script>

</body>
</html>