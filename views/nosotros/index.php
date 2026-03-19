<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros - API Educativa</title>
    <!-- <link rel="stylesheet" href="../../assets/css/styles.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>


    <main>
        <section class="hero">
            <h1>Sobre Nosotros</h1>
            <p>Bienvenido a API Educativa</p>
        </section>

        <!-- Botones de navegación -->
        <section class="tabs">
            <button class="btn btn-primary tab-button active" data-tab="quienes-somos">Quiénes Somos</button>
            <button class="btn btn-primary tab-button" data-tab="servicios">Servicios</button>
            <button class="btn btn-primary tab-button" data-tab="compromiso">Nuestro Compromiso</button>
        </section>

        <section id="quienes-somos" class="content tab-content active">
            <h2>Quiénes Somos</h2>
            <p>Somos una plataforma académica diseñada para apoyar los procesos de enseñanza y aprendizaje dentro de un entorno educativo moderno.</p>
            <h3>Nuestros Objetivos Principales</h3>
            <ul>
                <li>Ofrecer un espacio digital donde estudiantes, docentes y administradores puedan interactuar con la información académica de forma organizada, accesible y eficiente.</li>
                <li>Integrar herramientas tecnológicas que faciliten el acceso al conocimiento y promuevan la participación de los estudiantes.</li>
                <li>Mejorar la gestión de los recursos educativos dentro de la institución.</li>
                <li>Desarrollar el sistema utilizando tecnologías web actuales y una arquitectura estructurada que permita su crecimiento y adaptación a nuevas necesidades educativas.</li>
            </ul>
        </section>

        <section id="servicios" class="content2 tab-content">
            <h2>¿Qué ofrece nuestra plataforma?</h2>
            <h3><i class="fas fa-book"></i> Aprendizaje organizado</h3>
            <ul>
                <li>Estructurar la información académica de forma clara.</li>
                <li>Facilitar el acceso a contenidos educativos, recursos y servicios para estudiantes y docentes.</li>
            </ul>
            <h3><i class="fas fa-cogs"></i> Integración tecnológica</h3>
            <ul>
                <li>Incorporar herramientas digitales para modernizar la gestión educativa.</li>
                <li>Mejorar la experiencia de aprendizaje mediante el uso de tecnología.</li>
            </ul>
            <h3><i class="fas fa-users"></i> Apoyo a la comunidad educativa</h3>
            <ul>
                <li>Apoyar a estudiantes y profesores en la organización de actividades académicas.</li>
                <li>Fomentar la comunicación dentro del entorno educativo.</li>
            </ul>
            <h3><i class="fas fa-mobile-alt"></i> Acceso desde cualquier dispositivo</h3>
            <ul>
                <li>Diseño adaptable para computadoras, tablets y teléfonos móviles.</li>
                <li>Facilitar el acceso a la información en cualquier momento y lugar.</li>
            </ul>
        </section>

        <section id="compromiso" class="content3 tab-content">
            <h2>Compromiso Educativo</h2>
            <h3>Nuestra Creencia</h3>
            <p>En el Centro Educativo Digital creemos que la educación es una herramienta fundamental para el desarrollo personal, académico y social de los estudiantes.</p>
            <h3>Nuestra Responsabilidad</h3>
            <ul>
                <li>Ofrecer un entorno educativo apoyado en la tecnología.</li>
                <li>Facilitar el acceso al conocimiento y promover un aprendizaje dinámico, participativo y accesible para todos.</li>
            </ul>
            <h3>Nuestros Objetivos</h3>
            <ul>
                <li>Fomentar el desarrollo de habilidades críticas, el pensamiento creativo y la capacidad de adaptación a los cambios del mundo moderno.</li>
                <li>Brindar recursos digitales y herramientas que mejoren la experiencia de aprendizaje.</li>
                <li>Fortalecer la comunicación entre estudiantes, docentes y la institución.</li>
                <li>Promover el uso responsable de la tecnología para impulsar la innovación, la investigación y el crecimiento académico.</li>
                <li>Preparar a los estudiantes para enfrentar los retos del futuro en una sociedad cada vez más digital.</li>
            </ul>
            <h3>Compromiso Continuo</h3>
            <p>Nuestro compromiso es seguir desarrollando y mejorando esta plataforma educativa, con el objetivo de apoyar la formación integral de los estudiantes y contribuir al fortalecimiento de la educación en el entorno digital.</p>
        </section>

    </main>

    <script>
        // JavaScript para la función de manejar las pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    tabContents.forEach(content => content.classList.remove('active'));
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>

</body>

</html>