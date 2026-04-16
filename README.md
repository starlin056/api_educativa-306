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

Participación en el Desarrollo

El desarrollo del sistema fue realizado de manera colaborativa utilizando control de versiones con Git.

Las contribuciones individuales pueden ser verificadas en el historial del repositorio en GitHub, donde se reflejan los commits, cambios y mejoras realizadas durante el desarrollo del proyecto.

Cada integrante participó en distintas áreas como:

Desarrollo de vistas (interfaz gráfica)
Implementación de controladores
Modelado de base de datos
Integración de funcionalidades
Pruebas del sistema

---

## Descripción del Proyecto

El **Centro Educativo Digital** es una aplicación web orientada a la gestión integral de un centro educativo.

El sistema permite administrar usuarios, servicios, aulas y procesos académicos mediante una arquitectura organizada y escalable basada en el patrón **Modelo–Vista–Controlador (MVC)**, utilizando **PHP, MySQL, JavaScript y Tailwind CSS**.

---

## Objetivo General

Desarrollar una aplicación web funcional para la gestión educativa, implementando una arquitectura escalable con integración de frontend moderno y backend estructurado.

---

## Objetivos Específicos

- Implementar arquitectura MVC separando lógica, vistas y datos.
- Diseñar interfaz moderna y responsive utilizando Tailwind CSS.
- Desarrollar sistema de autenticación con manejo de roles.
- Integrar base de datos relacional con MySQL.
- Implementar seguridad mediante CSRF y hash de contraseñas.
- Preparar el sistema para despliegue en servidor web.

---

## Implementaciones

- Arquitectura MVC completamente estructurada.
- Sistema de autenticación con roles:
  - Administrador
  - Docente
  - Estudiante

- Middleware de seguridad:
  - Autenticación (`AuthMiddleware`)
  - Protección CSRF

- CRUD de:
  - Usuarios
  - Servicios
- Dashboards por rol
- Sistema de rutas centralizado (`index.php`)
- Integración con base de datos MySQL
- Uso de Tailwind CSS para estilos modernos
- Diseño responsive adaptable a múltiples dispositivos

---

## Base de Datos y Migraciones

El sistema cuenta con un esquema SQL inicial (`schema.sql`) que define la estructura de la base de datos.

### Tablas principales:

- `users` → gestión de usuarios y roles
- `services` → servicios ofrecidos
- `estudiantes` → información académica
- `aulas` → gestión de clases
- `inscripciones` → relación estudiante–aula

### Características implementadas:

- Relaciones entre tablas (claves foráneas)
- Normalización básica
- Preparado para escalabilidad
- Compatible con MySQL

---

## Integración de Tailwind CSS

Se implementó Tailwind CSS mediante CLI para optimizar el flujo de estilos.

### Instalación

```bash
npm install -D tailwindcss @tailwindcss/cli postcss autoprefixer
```

### Archivo de entrada

`assets/css/tailwind.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@import './custom.css';
```

### Compilación

```bash
npx @tailwindcss/cli -i ./assets/css/tailwind.css -o ./assets/css/styles.css --watch
```

### Configuración

`tailwind.config.js`

```js
module.exports = {
  content: ['./views/**/*.php', './index.php'],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

### Resultado

- Se genera `styles.css` con:
  - Utilidades de Tailwind
  - Estilos personalizados

---

## Estructura del Proyecto

```
api_educativa/
│
├── index.php
├── README.md
├── .env
│
├── assets/
│   ├── css/
│   │   ├── tailwind.css
│   │   ├── custom.css
│   │   └── styles.css
│   ├── js/
│   │   └── app.js
│   └── img/
│
├── config/
│   ├── config.php
│   └── database.php
│
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── DocenteController.php
│   ├── EstudianteController.php
│   ├── ServiceController.php
│   └── UserController.php
│
├── models/
│   ├── User.php
│   ├── Service.php
│   ├── Estudiante.php
│   ├── Aula.php
│   ├── Inscripcion.php
│
├── middleware/
│   ├── AuthMiddleware.php
│   └── Csrf.php
│
├── database/
│   └── schema.sql
│
└── views/
    ├── admin/
    ├── docente/
    ├── estudiante/
    ├── login/
    ├── home/
    ├── layouts/
    ├── errores/
    └── nosotros/
```

---

## Funcionalidades Implementadas

- Sistema de login y registro
- Control de acceso por roles
- Dashboard de administrador
- Gestión de usuarios y servicios
- Panel docente (aula)
- Panel estudiante
- Módulo de admisiones
- Manejo de errores 404
- Protección CSRF
- Layout reutilizable
- Interfaz moderna con Tailwind

---

## Diseño Responsive

| Dispositivo          | Soporte |
| -------------------- | ------- |
| Computadoras         | Sí      |
| Dispositivos móviles | Sí      |
| Tablets              | Sí      |

---

## Instalación

### 1. Clonar repositorio

```bash
git clone https://github.com/usuario/centro-educativo-digital.git
```

### 2. Ubicar en XAMPP

```
C:\xampp\htdocs\api_educativa
```

### 3. Configurar base de datos

- Crear base de datos en MySQL
- Importar archivo:

```
database/schema.sql
```

### 4. Ejecutar Tailwind

```bash
npx @tailwindcss/cli -i ./assets/css/tailwind.css -o ./assets/css/styles.css --watch
```

### 5. Ejecutar en navegador

```
http://localhost/api_educativa
```

---

## Plan de Desarrollo

| Etapa   | Descripción                 | Estado                 |
| ------- | --------------------------- | ---------------------- |
| Etapa 1 | Estructura MVC + UI         | Completada             |
| Etapa 2 | Integración JavaScript      | Completada             |
| Etapa 3 | Backend + MySQL + Seguridad | Completada             |
| Etapa 4 | Despliegue                  | Pendiente / solo local |

---

## Estado Actual

El sistema se encuentra funcional con:

- Arquitectura MVC completa
- Backend conectado a base de datos
- Autenticación con roles
- Interfaz moderna con Tailwind
- CRUD operativos

---

## Licencia

Proyecto desarrollado con fines académicos para la asignatura:

**Desarrollo de Aplicaciones Web ISW-306 — UAPA**

---

## Autor Principal del Repositorio

**Pedro Starlin Ureña Cruz**
Matrícula: 100063671

---
