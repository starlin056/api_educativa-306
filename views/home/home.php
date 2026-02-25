<!-- Vista Home: Página de inicio del sitio educativo -->
<?php include __DIR__ . '/../layouts/header.php'; ?>

    <!-- 2. Main Semántico -->
    <main class="container">
        
        <!-- Sección Hero -->
        <section class="hero">
            <h1>Bienvenidos al Futuro de la Educación</h1>
            <p>Formando líderes con valores y excelencia académica.</p>
        </section>

        <!-- Grid Layout: Contenido Principal + Aside -->
        <div class="content-grid">
            
            <!-- Área Principal de Servicios -->
            <section class="services-section">
                <h2>Nuestros Servicios</h2>
                <div class="card-grid">
                    <article class="card">
                        <i class="fas fa-graduation-cap fa-2x" style="color: var(--color-primary)"></i>
                        <h3>Educación Básica</h3>
                        <p>Programa integral enfocado en el desarrollo cognitivo y social.</p>
                    </article>
                    <article class="card">
                        <i class="fas fa-laptop-code fa-2x" style="color: var(--color-primary)"></i>
                        <h3>Tecnología</h3>
                        <p>Laboratorios de computación y robótica de última generación.</p>
                    </article>
                    <article class="card">
                        <i class="fas fa-futbol fa-2x" style="color: var(--color-primary)"></i>
                        <h3>Deportes</h3>
                        <p>Instalaciones deportivas para fútbol, baloncesto y atletismo.</p>
                    </article>
                </div>
            </section>

            <!-- 3. Aside Semántico (Barra lateral) -->
            <aside>
                <h3>Avisos Recientes</h3>
                <ul>
                    <li>
                        <strong>Matrícula 2026:</strong> Abierta hasta el 30 de Marzo.
                    </li>
                    <li>
                        <strong>Reunión de Padres:</strong> Se convoca a reunión general el próximo viernes.
                    </li>
                    <li>
                        <strong>Feria de Ciencias:</strong> Inscríbete en secretaría.
                    </li>
                </ul>
                
                <div style="margin-top: 2rem; background: var(--color-primary); color: white; padding: 1rem; border-radius: 5px;">
                    <h4>¿Necesitas ayuda?</h4>
                    <p>Contáctanos al (809) 123-0000</p>
                </div>
            </aside>

        </div>
    </main>

    <!-- 4. Footer Semántico -->
    <?php include __DIR__ . '/../layouts/footer.php'; ?>