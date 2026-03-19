 <footer>
     <div class="container">
         <p>&copy; 2026 Centro Educativo ISW-306. Todos los derechos reservados.</p>
         <p>Grupo: 03</p>
     </div>
 </footer>


 <!-- scripts generales de la aplicación -->
 <script src="<?php echo $ruta_base; ?>/assets/js/app.js"></script>


 <?php if (isset($pageJs) && $pageJs): ?>
     <script src="<?php echo $ruta_base; ?>/assets/js/pages/<?php echo htmlspecialchars($pageJs); ?>.js"></script>
 <?php endif; ?>
 </body>

 </html>