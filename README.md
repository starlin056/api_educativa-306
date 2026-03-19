<!-- @format -->

# Centro Educativo Digital

<p align="center">
  <img src="Imagenes de capturas del proyecto/Panel de Administración.jpeg" alt="Panel de Administración" width="800"/>
</p>

---

## Proyecto Integrador — ISW-306

| Campo           | Detalle                                 |
| --------------- | --------------------------------------- |
| **Universidad** | Universidad Abierta Para Adultos (UAPA) |
| **Asignatura**  | Desarrollo de Aplicaciones Web ISW-306  |
| **Profesor**    | Marco Rodríguez                         |
| **Unidad**      | III                                     |
| **Grupo**       | 03                                      |
| **Fecha**       | 24/02/2026                              |

---

## Integrantes del Grupo 03

| Nombre                             | Matrícula |
| ---------------------------------- | --------- |
| Pedro Starlin Ureña Cruz           | 100063671 |
| Enderson Estrella                  | 100071565 |
| Jonathan Marte Vásquez             | 100057813 |
| Elbin Collado                      | 100072614 |
| Luis Manuel Cabrera                | 100067787 |
| Leodis Reynaldo Rodríguez Calderón | 100063024 |

---

## Descripción del Proyecto

El **Centro Educativo Digital** es una aplicación web orientada a la gestión integral de un centro educativo, desarrollada como proyecto integrador de la asignatura ISW-306.

La implementación actual cubre la estructura base del sistema utilizando **HTML5, CSS3 y PHP**, organizados bajo el patrón de arquitectura **Modelo–Vista–Controlador (MVC)**, proporcionando una base sólida, escalable y preparada para futuras integraciones con backend y base de datos.

---

## Objetivo General

Desarrollar la estructura base de la aplicación web mediante una interfaz visual funcional, utilizando HTML5 semántico, CSS externo y PHP, estableciendo los cimientos para integraciones posteriores con backend y base de datos bajo una arquitectura escalable.

---

## Objetivos Específicos

- Aplicar estructura semántica con etiquetas HTML5 para garantizar accesibilidad y correcta organización del contenido.
- Diseñar e implementar la interfaz visual usando CSS externo, respetando los colores institucionales del centro educativo.
- Incorporar diseño responsive mediante Flexbox y Media Queries para compatibilidad con múltiples dispositivos.
- Organizar el proyecto bajo el patrón MVC, separando lógica de negocio, presentación y gestión de datos.
- Implementar formularios funcionales (login, registro y navegación) preparados para integración con backend.

---

## Implementaciones

- **Estructura MVC:** organización completa en controladores, vistas, layouts y modelos.
- **Ruteo básico:** `index.php` como punto de entrada para gestionar la carga y navegación entre vistas.
- **Vistas implementadas:** `home`, `login`, `register`, `nosotros`, dashboards de Admin, Docente y Estudiante, panel de servicios, admisiones y manejo de errores 404.
- **Diseño responsive:** CSS3, Flexbox y Media Queries para interfaz adaptable a múltiples dispositivos.
- **Configuración del sistema:** archivos de configuración base (entorno, constantes, rutas) y gestión de sesiones.
- **Seguridad de credenciales:** hash de contraseñas con `password_hash` / `password_verify`.
- **Middleware:** autenticación (`AuthMiddleware.php`) y protección CSRF (`Csrf.php`).
- **Flujo de autenticación y roles:** lógica inicial para inicio de sesión y redirección por rol hacia los dashboards correspondientes (Administrador, Docente, Estudiante).
- **Base de datos:** diseño del esquema SQL inicial (`schema.sql`) para integración en la siguiente etapa.
- **Dashboard de Administración:** panel funcional para administrar usuarios y servicios, siendo el usuario Root del sistema.

---

## Arquitectura del Sistema

El proyecto implementa el patrón **Modelo–Vista–Controlador (MVC)**:

| Capa            | Responsabilidad                      | Estado                           |
| --------------- | ------------------------------------ | -------------------------------- |
| **Modelo**      | Gestión de datos y lógica de negocio | Base implementada, en expansión  |
| **Vista**       | Interfaz visual del sistema          | Vistas principales implementadas |
| **Controlador** | Lógica de navegación y flujo         | Base implementada, en expansión  |

---

## Tecnologías Utilizadas

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)

- HTML5 semántico
- CSS3 + Flexbox + Media Queries
- PHP (MVC)
- JavaScript
- MySQL (esquema preparado)

---

## Estructura del Proyecto

```
api_educativa/
│
├── .env
├── home.jpeg
├── index.php                          # Punto de entrada / Router principal
├── README.md
│
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── img/
│   │   ├── fondo-escuela.jpg
│   │   └── logo.png
│   └── js/
│       └── app.js
│
├── config/
│   ├── config.php
│   └── database.php
│
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── Controller.php
│   ├── DocenteController.php
│   ├── EstudianteController.php
│   ├── HomeController.php
│   ├── ServiceController.php
│   └── UserController.php
│
├── database/
│   ├── estru-database.png
│   └── schema.sql
│
├── helpers/
│
├── middleware/
│   ├── AuthMiddleware.php
│   └── Csrf.php
│
├── models/
│   ├── Aula.php
│   ├── Auth.php
│   ├── Estudiante.php
│   ├── Inscripcion.php
│   ├── Model.php
│   ├── Service.php
│   └── User.php
│
├── Imagenes de capturas del proyecto/
│   ├── contraseña.jpeg
│   ├── docente panel.png
│   ├── home.jpeg
│   ├── js.jpeg
│   ├── Panel de Administración.jpeg
│   └── Panel del Estudiante.jpeg
│
└── views/
    ├── admin/
    │   ├── dashboard.php
    │   ├── services/
    │   │   ├── create.php
    │   │   ├── edit.php
    │   │   └── index.php
    │   └── users/
    │       ├── form.php
    │       └── index.php
    ├── Admisiones/
    │   └── index.php
    ├── docente/
    │   ├── aula.php
    │   └── dashboard.php
    ├── errors/
    │   └── 404.php
    ├── estudiante/
    │   └── dashboard.php
    ├── home/
    │   └── home.php
    ├── layouts/
    │   ├── footer.php
    │   └── header.php
    ├── login/
    │   ├── login.php
    │   └── register.php
    └── nosotros/
        └── index.php
```

---

## Funcionalidades Implementadas

- Router principal mediante `index.php`
- Página principal institucional (`home`)
- Formulario de login
- Formulario de registro
- Página Nosotros
- Dashboard de Administrador (CRUD usuarios y servicios)
- Dashboard de Docente (gestión de aula)
- Dashboard de Estudiante
- Panel de Admisiones
- Middleware de autenticación y protección CSRF
- Manejo de errores (404)
- Layout reutilizable (header / footer)
- Diseño responsive

---

## Diseño Responsive

Compatible con:

| Dispositivo          | Soporte |
| -------------------- | ------- |
| Computadoras         | ✅      |
| Dispositivos móviles | ✅      |
| Tablets              | ✅      |

---

## Instalación

**1. Clonar el repositorio**

```bash
git clone https://github.com/usuario/centro-educativo-digital.git
```

**2. Colocar el proyecto en el servidor local**

```
xampp/htdocs/api_educativa
```

**3. Ejecutar en el navegador**

```
http://localhost/api_educativa
```

---

## Plan de Desarrollo

| Etapa   | Descripción                                 | Estado          |
| ------- | ------------------------------------------- | --------------- |
| Etapa 1 | Estructura MVC + Interfaz visual + PHP base | ✅ Completada   |
| Etapa 2 | Implementación de JavaScript                | ✅ Completada o |
| Etapa 3 | Implementación de PHP y MySQL               | ✅ Completada   |
| Etapa 4 | Despliegue en servidor web                  | ⏳ Pendiente    |

---

## Estado Actual

> **Etapa 1 completada.** El sistema cuenta con arquitectura MVC, interfaz visual funcional, formularios listos para backend, dashboards por rol y diseño adaptable.

---

## Licencia

Proyecto desarrollado con fines académicos.
Uso exclusivo para la asignatura **Desarrollo de Aplicaciones Web ISW-306 — UAPA**.
Prohibida su distribución comercial.

---

## Autor Principal del Repositorio

**Pedro Starlin Ureña Cruz** — 100063671
