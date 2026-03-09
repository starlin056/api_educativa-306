# Centro Educativo Digital

<p align="center">
  https://github.com/starlin056/api_educativa-306/blob/main/IMAGEN.jpeg
</p>

## Proyecto Integrador ISW-306

---

## Información Académica

**Universidad:** Universidad Abierta Para Adultos (UAPA)
---
**Asignatura:** Desarrollo de Aplicaciones Web ISW-306
---
**Profesor:** Marco Rodríguez
--
**Unidad:** III
**Grupo:** 03
**Fecha:** 24/02/2026

---

## Integrantes del grupo 03

* Pedro Starlin Ureña Cruz — 100063671
* Enderson Estrella — 100071565
* Jonathan Marte Vásquez — 100057813
* Elbin Collado — 100072614
* Luis Manuel Cabrera — 100067787
* Leodis Reynaldo Rodríguez Calderón — 100063024

---

## Descripción del Proyecto

El proyecto Centro Educativo Digital consiste en el desarrollo de una aplicación web orientada a la gestión de un centro educativo, como parte del proyecto integrador de la asignatura ISW-306.

En esta primera etapa se implementó la estructura base del sistema utilizando HTML5, CSS y PHP, organizados bajo el patrón de arquitectura Modelo–Vista–Controlador (MVC).

Esta implementación permite contar con una base sólida, escalable y preparada para futuras integraciones con backend y base de datos.

---


##  Objetivo General

Desarrollar la estructura base de la aplicación web mediante una interfaz visual funcional, utilizando HTML5 semántico, CSS externo y PHP, estableciendo los cimientos para integraciones posteriores con backend y base de datos bajo una arquitectura escalable.

---

##  Objetivos Específicos

* Aplicar una estructura semántica utilizando etiquetas HTML5 para garantizar accesibilidad y correcta organización del contenido.
* Diseñar e implementar la interfaz visual del sistema empleando CSS externo, respetando los colores institucionales del centro educativo.
* Incorporar un diseño responsive mediante Flexbox y Media Queries para asegurar compatibilidad con dispositivos móviles, tablets y computadoras.
* Organizar el proyecto bajo el patrón Modelo–Vista–Controlador (MVC), separando la lógica de negocio, la presentación y la gestión de datos para facilitar la escalabilidad y el mantenimiento.
* Implementar formularios funcionales (login, registro y navegación principal) preparados para la integración con backend y base de datos en las siguientes etapas del proyecto.

---

##  Implementaciones

* **Estructura MVC inicial:** organización del proyecto en controladores, vistas, layouts y base para modelos.
* **Ruteo básico:** `index.php` como punto de entrada para gestionar la carga y navegación entre vistas.
* **Vistas iniciales:** `home`, `login`, `register` y `nosotros`, construidas con HTML5 semántico.
* **Diseño responsive:** uso de CSS3, Flexbox y Media Queries para una interfaz adaptable a múltiples dispositivos.
* **Configuración del sistema:** archivos de configuración base (entorno, constantes, rutas) y gestión inicial de sesiones.
* **Seguridad de credenciales:** implementación de hash de contraseñas (p. ej., `password_hash`/`password_verify`) como preparación para la autenticación.
* **Flujo de autenticación y roles preparado:** lógica inicial para inicio de sesión y redirección por rol hacia los dashboards correspondientes (Administrador, Profesor, Estudiante).
* **Base de datos:** diseño del esquema SQL inicial para su integración en la siguiente etapa.
* **Dashboards:** correspondientes (Administrador) funcionar donde se podran administrar todos los usuarios y servicios ofrecido siendo este el usuario Root del sistema.


---

## Arquitectura del Sistema

El proyecto utiliza el patrón Modelo–Vista–Controlador (MVC).

Componentes:

**Modelo**
Gestiona los datos (pendiente completar la de mas logicas de datos) 

**Vista**
Interfaz visual del sistema (pendiente diseñar la de mas vistas)

**Controlador**
Gestiona la lógica y navegación (pendiente completar la de mas logicas)

---

## Tecnologías Utilizadas

* HTML5
* CSS3
* PHP
* Flexbox
* Media Queries

---

## Estructura del Proyecto

```
centro-educativo-digital/

index.php

app/
 ├── views/
 │    ├── home.php
 │    ├── login.php
 │    ├── register.php
 │    └── nosotros/index.php
 │
 └── layouts/
      ├── header.php
      └── footer.php

assets/
 └── css/
      └── styles.css
```

---

## Funcionalidades Implementadas

Router principal mediante index.php

Página principal institucional

Formulario de login

Formulario de registro

Página nosotros

Layout reutilizable

Diseño responsive

---

## Diseño Responsive

Compatible con:

Computadoras
Tablets
Dispositivos móviles

---
## Estado Actual

Etapa 1 completada.

El sistema cuenta con:

Arquitectura MVC
Interfaz visual funcional
Formularios listos para backend
Diseño adaptable

---

## Instalación

1. Clonar el repositorio

```
git clone https://github.com/usuario/centro-educativo-digital.git
```

2. Colocar el proyecto en el servidor local

Ejemplo:xampp

```
htdocs/carpeta del proyecto
```

3. Ejecutar en el navegador

```
http://localhost/api_educativa
```

---

## Plan de Desarrollo

Etapa 2
Implementación de JavaScript

Etapa 3
Implementación de PHP y MySQL

Etapa 4
Despliegue en servidor web

---

## Licencia

Proyecto desarrollado con fines académicos.

Uso exclusivo para la asignatura Desarrollo de Aplicaciones Web ISW-306.

Prohibida su distribución comercial.

---

## Autor Principal del Repositorio

Pedro Starlin Ureña Cruz

---

## Estado del Proyecto

En desarrollo y en crecimiento presentacion final del proyecto en etapa 4

---











