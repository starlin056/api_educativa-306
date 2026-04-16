<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admisiones - Centro Educativo</title>
    <!-- CSS principal -->

    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="body-admisiones">

    <main>
        <!-- Hero Section -->
        <section class="hero-admisiones">
            <h1>Sobre nuestras Admisiones</h1>
            <p>Bienvenido a API Educativa</p>
        </section>

        <!-- Pestañas de navegación -->
        <section class="tabs-admisiones">
            <button class="tab-button active" onclick="showTab('requisitos')">Requisitos de Admisión</button>
            <button class="tab-button" onclick="showTab('costos')">Costos y Aranceles</button>
        </section>

        <!-- SECCIÓN 1: REQUISITOS DE ADMISIÓN -->
        <section id="requisitos" class="tab-content active">
            <div class="titulo-admisiones">
                <h2>Requisitos de Admisión</h2>
                <div class="subrayado-admisiones"></div>
            </div>

            <div class="grid-2">
                <!-- Columna izquierda: Documentos -->
                <div>
                    <h3 style="color: #1e2b45; margin-bottom: 20px;">
                        <i class="fas fa-file-alt"></i> Documentos Necesarios
                    </h3>
                    <ul>
                        <li>Acta de nacimiento (original y copia)</li>
                        <li>Certificado de estudios anteriores</li>
                        <li>Cédula de identidad del estudiante</li>
                        <li>2 fotos tamaño carnet</li>
                        <li>Constancia de salud</li>
                        <li>Carta de conducta</li>
                    </ul>

                    <h3 style="color: #1e2b45; margin: 30px 0 20px;">
                        <i class="fas fa-user-graduate"></i> Perfil del Estudiante
                    </h3>
                    <ul>
                        <li>Edad mínima: 6 años</li>
                        <li>Buen rendimiento académico</li>
                        <li>Valores y disciplina</li>
                    </ul>
                </div>

                <!-- Columna derecha: Imágenes -->
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

        <!-- SECCIÓN 2: COSTOS Y ARANCELES -->
        <section id="costos" class="tab-content">
            <div class="titulo-admisiones">
                <h2>Costos y Aranceles 2026</h2>
                <div class="subrayado-admisiones"></div>
            </div>

            <div class="grid-3">
                <!-- Matrícula -->
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Matricula.jpg');">
                    <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3>Matrícula</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$5,000</p>
                    <p style="color: #e0e0e0;">Pago único anual</p>
                </div>

                <!-- Mensualidad -->
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Mensualidad.jpg');">
                    <i class="fas fa-calendar-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3>Mensualidad</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$2,500</p>
                    <p style="color: #e0e0e0;">10 pagos al año</p>
                </div>

                <!-- Programas Especiales -->
                <div class="card-bg" style="background-image: linear-gradient(rgba(10, 17, 40, 0.75), rgba(30, 43, 69, 0.75)), url('http://localhost/api_educativa/assets/img/Programas.jpg');">
                    <i class="fas fa-laptop-code" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3>Programas Especiales</h3>
                    <p style="font-size: 28px; color: #e67e22; font-weight: bold; margin: 10px 0;">$1,500</p>
                    <p style="color: #e0e0e0;">Robótica, Inglés, etc.</p>
                </div>
            </div>

            <!-- Imagen de instalaciones -->
            <img src="http://localhost/api_educativa/assets/img/Instalaciones.jpg"
                alt="Instalaciones"
                class="img-admisiones"
                style="width: 100%; height: 300px; object-fit: cover; margin: 30px 0;"
                onerror="this.src='https://via.placeholder.com/1200x300/1e2b45/ffffff?text=Nuestras+Instalaciones'">
        </section>

        <!-- AVISOS RECIENTES -->
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px;">
            <div class="titulo-admisiones">
                <h2>Avisos Recientes</h2>
                <div class="subrayado-admisiones"></div>
            </div>

            <div class="avisos">
                <div>
                    <img src="http://localhost/api_educativa/assets/img/Avisos%20importantes.jpg"
                        alt="Avisos"
                        class="img-admisiones"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        onerror="this.src='https://via.placeholder.com/400x300/e67e22/ffffff?text=Avisos'">
                </div>
                <div style="padding: 10px;">
                    <p><i class="fas fa-calendar-alt" style="color: #e67e22;"></i> <strong>Matrícula 2026:</strong> Abierta hasta el 30 de Marzo.</p>
                    <p><i class="fas fa-pencil-alt" style="color: #e67e22;"></i> <strong>Pruebas de Admisión:</strong> 15 y 16 de Abril.</p>
                    <p><i class="fas fa-chalkboard-user" style="color: #e67e22;"></i> <strong>Reunión informativa:</strong> Para padres de nuevos estudiantes, 5 de Abril.</p>
                    <p><i class="fas fa-phone-alt" style="color: #e67e22;"></i> <strong>¿Necesitas ayuda?</strong> Contáctanos al (809) 123-0000</p>
                </div>
            </div>
        </div>

        <!-- BOTÓN DE CONTACTO -->
        <div style="text-align: center; margin: 40px 0;">
            <a href="#" class="btn-naranja">
                <i class="fas fa-envelope"></i> Solicitar información
            </a>
        </div>
    </main>

    <script>
        function showTab(tabId) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Desactivar todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Mostrar el contenido seleccionado
            document.getElementById(tabId).classList.add('active');

            // Activar el botón clickeado
            event.target.classList.add('active');
        }
    </script>

</body>

</html>